<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Phase;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\StreamedResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Webkul\MUMBOS\Services\Payments\PaymentGatewayFactory;
use Webkul\MUMBOS\Services\Payments\MpesaGateway;
use App\Mail\ContributionApprovedMail;
use App\Mail\ContributionSubmittedMail;
use App\Mail\ContributionRejectedMail;

class ContributionController extends Controller
{
use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

public function recheckStatus(Contribution $contribution)
{

      \Log::info('RecheckStatus called for contribution: ' . $contribution->id);

    if (empty($contribution->payment_reference)) {
        return back()->withErrors([
            'payment_reference' => 'No M-Pesa CheckoutRequestID found for this contribution.'
        ]);
    }

    try {
        $gateway = app(MpesaGateway::class);
        $statusResponse = $gateway->checkTransactionStatus($contribution->payment_reference);

        $status = $statusResponse['status'] ?? null;
        $message = $statusResponse['message'] ?? 'Unknown response.';
        
        
        if ($status === 'Success') {
            $contribution->status = 'pending'; 
            $contribution->payment_status = 'completed';
            $contribution->payment_channel = 'mpesa';
          
            $contribution->paid_at = now();
            $contribution->save();

            return back()->with('success', $message);
        } elseif ($status === 'Cancelled') {
            $contribution->status = 'cancelled';
            $contribution->payment_status = 'failed';
              $contribution->payment_channel = 'mpesa';
          
            $contribution->save();

            return back()->withErrors(['mpesa' => $message]);
        } elseif ($status === 'Failed') {
            $contribution->status = 'failed';
            $contribution->payment_status = 'failed';
              $contribution->payment_channel = 'mpesa';
          
            $contribution->save();

            return back()->withErrors(['mpesa' => $message]);
        } else {
            return back()->withErrors(['mpesa' => 'Unknown transaction status.']);
        }

    } catch (\Exception $e) {
        \Log::error('M-Pesa Transaction Status Error: ' . $e->getMessage());
        return back()->withErrors(['mpesa' => 'Failed to check transaction status.']);
    }
}

public function index()
{
    $contributions = Contribution::with('shareholder.customer','recordedBy', 'phase')
        ->whereHas('shareholder.customer') // only those that have a customer
        ->orderBy('created_at', 'desc')    // order by newest first
        ->paginate(20);

    return view('mumbos::admin.contributions.index', compact('contributions'));
}



    public function create()
    {
        $shareholders = Shareholder::with('customer') 
        ->where('is_active', true)
        ->get();
         $phases       = Phase::orderBy('id')->get();
        return view('mumbos::admin.contributions.create', compact('shareholders','phases'));
    }




public function store(Request $request)
{
    $data = $request->validate([
        'shareholder_id'         => 'required|exists:shareholders,id',
        'phase_id'         => 'required|exists:phases,id',
        'amount'                 => 'required|numeric|min:0.01',
        'type'           => 'required|in:membership,regular,capital,other',
        'currency'               => 'required|string|size:3',
        'payment_method'         => 'required|in:cash,bank_transfer,mpesa,paypal',
        'payment_channel'        => 'nullable|string',
        'bank_payment_reference' => 'nullable|string',
        'paypal_payment_reference' => 'nullable|string',
        'contributed_at'         => 'required|date',
        'note'                   => 'nullable|string',
        'phone'                  => 'nullable|string',
    ]);

    $shareholder = \Webkul\MUMBOS\Models\Shareholder::find($data['shareholder_id']);
    if (! $shareholder) {
        return back()->withErrors(['shareholder_id' => 'Shareholder not found.']);
    }

    $shareholderNumber = $shareholder->shareholder_number ?? 'M-SH000';
    $source = auth('admin')->check() ? 'ADM' : 'WEB';

    $reference = $this->generateTransactionRef($shareholderNumber, $source);

    $data['payment_reference'] = $reference;

      if ($data['payment_method'] === 'mpesa') {
        if (empty($request->phone)) {
            return back()->withErrors(['phone' => 'Phone number is required for M-Pesa payments.'])->withInput();
        }


         $phone = preg_replace('/\D/', '', $request->phone);
    
        if (Str::startsWith($phone, '07') && strlen($phone) === 10) {
            $phone = '254' . substr($phone, 1);
        } elseif (Str::startsWith($phone, '7') && strlen($phone) === 9) {
            $phone = '254' . $phone;
        } elseif (Str::startsWith($phone, '+254')) {
            $phone = ltrim($phone, '+');
        }

        if (!Str::startsWith($phone, '2547') || strlen($phone) !== 12) {
            return back()->withErrors(['phone' => 'Invalid phone number format. Use format 2547XXXXXXXX'])->withInput();
        }

       $mpesaResponse = app(MpesaGateway::class)->initiate([
            'phone'             => $request->phone,
            'amount'            => $data['amount'],
            'shareholder_id'    => $data['shareholder_id'],
            'payment_reference' => $reference,
        ]);

        // If response is a JSON string, decode it
        if (is_string($mpesaResponse)) {
            $decoded = json_decode($mpesaResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['phone' => 'Invalid M-Pesa response received.'])->withInput();
            }

            $mpesaResponse = $decoded;
        }

        \Log::info('M-Pesa STK Response Parsed:', ['response' => $mpesaResponse]);

        $checkoutRef = $mpesaResponse['checkoutRequestID'] ?? $mpesaResponse['reference'] ?? null;
        if (!$checkoutRef) {
            return back()->withErrors(['phone' => 'M-Pesa STK Push failed to initiate.'])->withInput();
        }

        $data['payment_reference'] = $checkoutRef;
        $data['phone'] = $request->phone;

    }

    if ($data['payment_method'] === 'bank_transfer') {
        $data['payment_reference'] = $request->bank_payment_reference;
    } elseif ($data['payment_method'] === 'paypal') {
        $data['payment_reference'] = $request->paypal_payment_reference;
    }

    // Receipt upload
    if ($request->hasFile('payment_receipt')) {
        $data['payment_receipt'] = $request->file('payment_receipt')->store('contributions/receipts', 'public');
    }

    $data['recorded_by'] = Auth::guard('admin')->id();
    $data['payment_status'] = 'pending';
    $data['status'] = 'pending';

    $contribution = Contribution::create($data);

    $pdf = Pdf::loadView('mumbos::admin.contributions.receipt', [
        'contribution' => $contribution
    ]);

    $fileName = 'receipts/contribution_' . $contribution->id . '.pdf';
    Storage::disk('public')->put($fileName, $pdf->output());

    $contribution->update(['receipt_url' => Storage::url($fileName)]);
    \Mail::to($shareholder->customer->email)
            ->queue(new \App\Mail\ContributionSubmittedMail($contribution));

    return redirect()->route('admin.contributions.index')->with('message', 'Contribution recorded Succesfully.');
}

public function generateTransactionRef(string $shareholderNumber, string $source = 'WEB'): string
{
   
    if (auth('admin')->check()) {
        $source = 'ADM';
    } elseif (auth('customer')->check()) {
        $source = 'WEB';
    } elseif (request()->is('admin/*')) {
        $source = 'ADM';
    } else {
        $source = 'SYS'; // fallback for unknowns (API, system jobs)
    }

    do {
        $datePart   = now()->format('dmy');                // e.g. 260625
        $randomPart = strtoupper(Str::random(5));          // e.g. 9D7AF

        $reference = "TXN-{$source}-{$shareholderNumber}-{$datePart}-{$randomPart}";
        
    } while (Contribution::where('payment_reference', $reference)->exists()); // ensure it's unique

    return $reference;
}
   
//     public function show(Contribution $contribution)
//     {
//         $contribution->load('shareholder.customer');
//           $contribution->refresh(); 
// // dd($contribution->toArray());
//         return view('mumbos::admin.contributions.show', compact('contribution'));
//     }

public function show(Contribution $contribution)
{
    $contribution->load('shareholder.customer');
    $contribution->refresh(); 

    $signedUrl = URL::temporarySignedRoute(
        'admin.contributions.receipt-preview',
        now()->addMinutes(60),
        ['contribution' => $contribution->id]
    );

    return view('mumbos::admin.contributions.show', compact('contribution', 'signedUrl'));
}

public function edit(Contribution $contribution)
    {
    $contribution->load(['shareholder.customer', 'phase', 'recordedBy']);
    $shareholders = Shareholder::with('customer')->where('is_active', true)->get();
     $phases = Phase::orderBy('created_at', 'desc')->get();
    return view('mumbos::admin.contributions.edit', compact('contribution', 'shareholders','phases'));
    }

  
public function update(Request $request, Contribution $contribution)
    {

        //  dd('update method hit');
        $data = $request->validate([
            'shareholder_id'     => 'required|exists:shareholders,id',
            'amount'             => 'required|numeric|min:0.01',
            'currency'           => 'required|string|size:3',
            'payment_method'     => 'required|in:cash,bank_transfer,mpesa,paypal',
            'payment_channel'    => 'nullable|string',
            'payment_reference'  => 'nullable|string',
            'payment_receipt'    => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'payment_status'     => 'required|in:pending,completed,failed',
            'contributed_at'     => 'required|date',
            'status'             => 'required|in:pending,approved,rejected',
            'note'               => 'nullable|string',
        ]);

        // handle new receipt upload
        if ($request->hasFile('payment_receipt')) {
            // delete old
            if ($contribution->payment_receipt && Storage::disk('public')->exists($contribution->payment_receipt)) {
                Storage::disk('public')->delete($contribution->payment_receipt);
            }

            $data['payment_receipt'] = $request
                ->file('payment_receipt')
                ->store('contributions/receipts', 'public');
        }

        // if status changed to approved, set approver
        if ($data['status'] === 'approved' && $contribution->status !== 'approved') {
            $data['approved_by']  = Auth::guard('admin')->id();
            $data['approved_at']  = now();
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $contribution->update($data);

        return redirect()
            ->route('admin.contributions.index')
            ->with('success', 'Contribution updated successfully.');
    }

   
    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

        return redirect()
            ->route('admin.contributions.index')
            ->with('success', 'Contribution deleted.');
    }



public function previewReceipt(Contribution $contribution): StreamedResponse
{
    $relativePath = ltrim(str_replace('/storage/', '', $contribution->receipt_url), '/');
    $fullPath = Storage::disk('public')->path($relativePath);

    if (! Storage::disk('public')->exists($relativePath)) {
        abort(404, "Receipt not found at: $fullPath");
    }

    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="receipt_' . $contribution->id . '.pdf"',
    ]);
}


/**
 * Approve a pending contribution.
 */
public function approve(Contribution $contribution)
{
    if ($contribution->status !== 'pending') {
        return back()->with('error', 'Only pending contributions can be approved.');
    }

    // Update contribution status
    $contribution->update([
        'status'         => 'approved',
        'payment_status' => 'completed',
        'approved_by'    => Auth::guard('admin')->id(),
        'approved_at'    => now(),
    ]);

    // Update shareholder record
    $shareholder = $contribution->shareholder;

    if ($shareholder) {
        $updated = false;

        // If contribution is capital, update capital_paid
        if ($contribution->type === 'capital') {
            $shareholder->capital_paid = $shareholder->capital_paid + $contribution->amount;
            $updated = true;
        }

        // Update share_units if phase is valid
        if ($contribution->phase && $contribution->phase->share_value > 0) {
            $units = round($contribution->amount / $contribution->phase->share_value);

            // Avoid negative units or phase with share_value = 0
            if ($units > 0) {
                $shareholder->share_units += $units;
                $updated = true;
            }
        }

        if ($updated) {
            $shareholder->save();
            \Mail::to($shareholder->customer->email)
        ->queue(new \App\Mail\ContributionApprovedMail($contribution));

        }
    }

    return back()->with('success', "Contribution #{$contribution->id} approved and shareholder updated.");
}


/**
 * Reject a pending contribution.
 */
public function reject(Contribution $contribution)
{
    if ($contribution->status !== 'pending') {
        return back()->with('error', 'Only pending contributions can be rejected.');
    }

   $contribution->update([
        'status'         => 'rejected',
        'payment_status' => 'failed',
        'approved_by'    => Auth::guard('admin')->id(),
        'approved_at'    => now(),
    ]);


    $email = $contribution->shareholder->customer->email ?? null;

    if ($email) {
        \Mail::to($email)->queue(new ContributionRejectedMail($contribution));
    }

    return back()->with('success', "Contribution #{$contribution->id} rejected.");
}


public function bulkAction(Request $request)
{
    $request->validate([
        'selected_contributions' => 'required|array',
        'action' => 'required|string|in:approve,reject,delete',
    ]);

    $ids = $request->input('selected_contributions');
    $action = $request->input('action');

    $contributions = Contribution::whereIn('id', $ids)->get();
    $processedCount = 0;

    foreach ($contributions as $contribution) {
        switch ($action) {
            case 'approve':
                if ($contribution->status === 'pending') {
                    $this->approve($contribution);
                    $processedCount++;
                }
                break;

            case 'reject':
                if ($contribution->status === 'pending') {
                    $this->reject($contribution);
                    $processedCount++;
                }
                break;

            case 'delete':
                $contribution->delete();
                $processedCount++;
                break;
        }
    }

    return redirect()->back()->with('message', "Bulk action '$action' applied to $processedCount contribution(s).");
}


}
