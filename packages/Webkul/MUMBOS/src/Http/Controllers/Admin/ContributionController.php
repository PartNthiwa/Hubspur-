<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Models\Shareholder;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\StreamedResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContributionController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

public function index()
{
    $contributions = Contribution::with('shareholder.customer')
        ->whereHas('shareholder.customer') // only those that have a customer
        ->paginate(20);

    return view('mumbos::admin.contributions.index', compact('contributions'));
}



    public function create()
    {
        $shareholders = Shareholder::where('is_active', true)->get();

        return view('mumbos::admin.contributions.create', compact('shareholders'));
    }

 public function store(Request $request)
{
    // 1. Validate incoming data
    $data = $request->validate([
        'shareholder_id'     => 'required|exists:shareholders,id',
        'amount'             => 'required|numeric|min:0.01',
        'currency'           => 'required|string|size:3',
        'payment_method'     => 'required|in:cash,bank_transfer,mpesa,paypal',
        'payment_channel'    => 'nullable|string',
        'payment_reference'  => 'nullable|string',
        'contributed_at'     => 'required|date',
        'note'               => 'nullable|string',
    ]);

    // 2. Handle optional original receipt upload (if any)
    if ($request->hasFile('payment_receipt')) {
        $data['payment_receipt'] = $request
            ->file('payment_receipt')
            ->store('contributions/receipts', 'public');
    } else {
        $data['payment_receipt'] = null;
    }

    // 3. Set audit & status fields
    $data['recorded_by']    = Auth::guard('admin')->id();
    $data['payment_status'] = 'pending';
    $data['status']         = 'pending';

    // 4. Create the contribution
    $contribution = Contribution::create($data);

    // 5. Generate PDF Receipt
    $pdf = Pdf::loadView('mumbos::admin.contributions.receipt', [
        'contribution' => $contribution
    ]);

    // 6. Store the PDF into storage/app/public/receipts/
    $fileName = 'receipts/contribution_' . $contribution->id . '.pdf';
    Storage::disk('public')->put($fileName, $pdf->output());

    // 7. Now set the public URL and save the model again
    $contribution->receipt_url = Storage::url($fileName); // e.g. "/storage/receipts/contribution_1.pdf"
    $contribution->save();


    // 8. Redirect back with success
    return redirect()
        ->route('admin.contributions.index')
        ->with('success', 'Contribution recorded and receipt generated.');
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
        $shareholders = Shareholder::where('is_active', true)->get();

        return view('mumbos::admin.contributions.edit', compact('contribution', 'shareholders'));
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
    // Only allow approving pending items
    if ($contribution->status !== 'pending') {
        return back()->with('error','Only pending contributions can be approved.');
    }

    $contribution->update([
        'status'         => 'approved',
        'payment_status' => 'completed',
        'approved_by'    => Auth::guard('admin')->id(),
        'approved_at'    => now(),
    ]);

    return back()->with('success',"Contribution #{$contribution->id} approved.");
}

/**
 * Reject a pending contribution.
 */
public function reject(Contribution $contribution)
{
    if ($contribution->status !== 'pending') {
        return back()->with('error','Only pending contributions can be rejected.');
    }

    $contribution->update([
        'status'      => 'Failed',
        'approved_by' => Auth::guard('admin')->id(),
        'approved_at' => now(),
    ]);

    return back()->with('success',"Contribution #{$contribution->id} rejected.");
}

}
