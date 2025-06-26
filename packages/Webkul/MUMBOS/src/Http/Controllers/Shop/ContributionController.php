<?php

namespace Webkul\MUMBOS\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Models\Shareholder;
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

class ContributionController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Show list of contributions
   public function index()
{

    // 1. Grab the logged‑in customer via the 'customer' guard
    $customer = Auth::guard('customer')->user();

    // 2. If there's no customer, send them to login
    if (! $customer) {
        return redirect()->route('shop.customer.session.index');
    }

    // 3. Grab their shareholder record
    $shareholder = $customer->shareholder;
    if (! $shareholder) {
        return redirect()->route('shop.shareholders.register.info')
                         ->with('error','You must register as a shareholder first.');
    }

    // 4. Fetch their contributions
    $contributions = $shareholder->contributions()
                                ->orderBy('contributed_at','desc')
                                ->paginate(10);

    return view('mumbos::shop.shareholders.contributions.index', compact('contributions'));
}

    // Show creation form
    public function create()
    {
        return view('mumbos::shop.shareholders.contributions.create');
    }


// public function store(Request $request)
// {
//     $shareholder = Auth::user()->shareholder;

//     $data = $request->validate([
//         'amount'                  => 'required|numeric|min:1',
//         'payment_method'          => 'required|in:cash,bank_transfer,mpesa',
//         'bank_payment_reference'  => 'nullable|string|max:255',
//         'cash_note'               => 'nullable|string|max:255',
//         'phone'                   => 'nullable|string|max:20',
//         'payment_receipt'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
//         'contributed_at'          => 'required|date',
//         'note'                    => 'nullable|string|max:1000',
//     ]);

//     $data['currency']        = 'KES';
//     $data['payment_status']  = 'pending';
//     $data['status']          = 'pending';
//     $data['paid_at']         = now();
//     $data['shareholder_id']  = $shareholder->id;

//     // Generate a reference for STK push (or fallback)
//     $reference = 'TXN-' . now()->format('YmdHis') . '-' . uniqid();

//     // Normalize and handle payment method logic
//     if ($data['payment_method'] === 'mpesa') {
//         if (empty($request->phone)) {
//             return back()->withErrors(['phone' => 'Phone number is required for M-Pesa payments.'])->withInput();
//         }

//         //  Normalize phone to 2547XXXXXXXX
//         $phone = preg_replace('/\D/', '', $request->phone);
//        if (Str::startsWith($phone, '2547') && strlen($phone) === 12) {
      
//         } elseif (Str::startsWith($phone, '07') && strlen($phone) === 10) {
//             $phone = '254' . substr($phone, 1);
//         } elseif (Str::startsWith($phone, '7') && strlen($phone) === 9) {
//             $phone = '254' . $phone;
//         } elseif (Str::startsWith($phone, '+2547') && strlen($phone) === 13) {
//             $phone = substr($phone, 1); 
//         }


//         if (!Str::startsWith($phone, '2547') || strlen($phone) !== 12) {
//             return back()->withErrors(['phone' => 'Invalid phone number format. Use a valid Safaricom number.'])->withInput();
//         }


//         \Log::info('Contribution attempt:', [
//             'shareholder_id' => $shareholder->id,
//             'method'         => $data['payment_method'],
//             'amount'         => $data['amount'],
//             'reference'      => $data['payment_reference'] ?? 'N/A',
//         ]);

//         // Initiate M-Pesa STK Push
//         $mpesaResponse = app(MpesaGateway::class)->initiate([
//             'phone'             => $phone,
//             'amount'            => $data['amount'],
//             'shareholder_id'    => $shareholder->id,
//             'payment_reference' => $reference,
//         ]);

//      dd('Decoded M-Pesa STK Response:', ['response' => $mpesaResponse]);

//        if (is_string($mpesaResponse)) {
//             $decoded = json_decode($mpesaResponse, true);

//             if (json_last_error() !== JSON_ERROR_NONE) {
//                 return back()->withErrors(['phone' => 'Invalid M-Pesa response received.'])->withInput();
//             }

//             $mpesaResponse = $decoded;
//         }

//         \Log::info('M-Pesa STK Response Parsed:', ['response' => $mpesaResponse]);

//         $checkoutRef = $mpesaResponse['checkoutRequestID'] ?? $mpesaResponse['reference'] ?? null;

//         if (!$checkoutRef) {
//             return back()->withErrors(['phone' => 'M-Pesa STK Push failed to initiate.'])->withInput();
//         }

//         $data['payment_reference'] = $checkoutRef;
//         $data['phone'] = $request->phone;

//     }

//     // Handle bank transfer
//     if ($data['payment_method'] === 'bank_transfer') {
//         if (empty($request->bank_payment_reference)) {
//             return back()->withErrors(['bank_payment_reference' => 'Bank reference is required.'])->withInput();
//         }
//         $data['payment_reference'] = $request->bank_payment_reference;
//     }

//     // Handle cash
//     if ($data['payment_method'] === 'cash') {
//         $data['payment_reference'] = 'CASH-' . now()->format('YmdHis');
//         $data['note'] = ($data['note'] ?? '') . ' | ' . $request->cash_note;
//     }

//     // Handle receipt upload
//     if ($request->hasFile('payment_receipt')) {
//         $data['payment_receipt'] = $request->file('payment_receipt')->store('contributions/receipts', 'public');
//     }

//     // Save contribution
//     $contribution = $shareholder->contributions()->create($data);

//     // Generate PDF receipt (optional)
//     $pdf = Pdf::loadView('mumbos::admin.contributions.receipt', [
//         'contribution' => $contribution
//     ]);

//     $fileName = 'receipts/contribution_' . $contribution->id . '.pdf';
//     Storage::disk('public')->put($fileName, $pdf->output());
//     $contribution->update(['receipt_url' => Storage::url($fileName)]);

//     return redirect()
//         ->route('shop.shareholders.contributions.index')
//         ->with('success', 'Your contribution has been recorded. You will be notified once it is approved.');
// }


}
