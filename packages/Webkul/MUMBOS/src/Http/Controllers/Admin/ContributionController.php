<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Models\Shareholder;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContributionController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index()
    {
        $contributions = Contribution::with('shareholder.customer')
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
    // Handle optional payment receipt upload
    if ($request->hasFile('payment_receipt')) {
        $data['payment_receipt'] = $request
            ->file('payment_receipt')
            ->store('contributions/receipts', 'public');
    } else {
        $data['payment_receipt'] = null; // Ensure it's set even if not uploaded
    }
    // Set additional fields
    $data['recorded_by']    = Auth::guard('admin')->id();
    $data['payment_status'] = 'pending';
    $data['status']         = 'pending';

    // Save contribution first
    $contribution = Contribution::create($data);

    // Generate PDF Receipt
    $pdf = Pdf::loadView('mumbos::admin.contributions.receipt', [
        'contribution' => $contribution
    ]);

    $fileName = 'receipts/contribution_' . $contribution->id . '.pdf';
    Storage::disk('public')->put($fileName, $pdf->output());

    // Update contribution with receipt URL
    $contribution->update([
        'receipt_url' => Storage::url($fileName),
    ]);

    return redirect()
        ->route('admin.contributions.index')
        ->with('success', 'Contribution recorded, pending payment.');
}

   
    public function show(Contribution $contribution)
    {
        $contribution->load('shareholder.customer');
          $contribution->refresh(); 
// dd($contribution->toArray());
        return view('mumbos::admin.contributions.show', compact('contribution'));
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

    public function previewReceipt($id)
{
    $contribution = Contribution::findOrFail($id);

    if (!$contribution->receipt_url) {
        abort(404, 'Receipt not available.');
    }

    $relativePath = str_replace('/storage/', '', $contribution->receipt_url);
    $fullPath = storage_path('app/public/' . $relativePath);

    if (!file_exists($fullPath)) {
        abort(404, 'PDF file not found.');
    }

    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="receipt.pdf"',
    ]);
}

}
