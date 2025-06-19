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
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

public function store(Request $request)
{
    $shareholder = Auth::user()->shareholder;

    $data = $request->validate([
        'amount'            => 'required|numeric|min:1',
        'payment_method'    => 'required|in:cash,bank_transfer,mpesa',
        'payment_reference' => 'required|string|max:255',
        'payment_receipt'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'contributed_at'    => 'required|date',
        'note'              => 'nullable|string',
    ]);

    $data['currency']        = 'KES';
    $data['payment_status']  = 'pending';
    $data['status']          = 'pending';
    $data['paid_at']         = now();

    // Handle receipt upload
    if ($request->hasFile('payment_receipt')) {
        $data['payment_receipt'] = $request->file('payment_receipt')->store('receipts', 'public');
    }

    $shareholder->contributions()->create($data);

    return redirect()
        ->route('shop.shareholders.contributions.index')
        ->with('success', 'Your contribution has been recorded and is pending review.');
}


}
