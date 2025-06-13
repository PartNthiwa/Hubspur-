<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Webkul\MUMBOS\Http\Requests\ShareholderRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShareholderController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $shareholders = Shareholder::with('customer')->paginate(20);
        return view('mumbos::admin.shareholders.index', compact('shareholders'));
    }

    public function create()
    {
        $customers = Customer::doesntHave('shareholder')->get();
        return view('mumbos::admin.shareholders.create', compact('customers'));
    }

   public function store(Request $request)
{
    $messages = [
        'phone.unique' => 'The mobile number has already been taken. Each shareholder must have a unique phone number.',
        'id_number.unique' => 'The ID number has already been registered. Please verify it.',
        'joined_at.before_or_equal' => 'The joining date cannot be in the future. Please select today or an earlier date.',
    ];

    $data = $request->validate([
        'customer_id'        => 'required|exists:customers,id',
        'full_name'          => 'nullable|string',
        'id_number'          => 'nullable|string|unique:shareholders,id_number',
        'kra_pin'            => 'nullable|string',
        'email'              => 'nullable|email',
        'phone'              => 'nullable|string|unique:shareholders,phone',
        'postal_address'     => 'nullable|string',
        'physical_address'   => 'nullable|string',
        'city'               => 'nullable|string',
        'country'            => 'nullable|string',
        'share_class'        => 'nullable|string',
        'share_units'        => 'nullable|integer',
        'capital_paid'       => 'nullable|numeric',
        'joined_at'          => 'required|date|before_or_equal:today',
        'is_active'          => 'boolean',
        'is_board_member'    => 'nullable|boolean',
        'position'           => 'nullable|string',
    ], $messages);

    
    $data['shareholder_number'] = $this->generateUniqueShareholderNumber();
    $data['is_active'] = $request->has('is_active');
    $data['is_board_member'] = $request->has('is_board_member');

    Shareholder::create($data);

    return redirect()->route('admin.shareholders.index')->with('success', 'Shareholder created successfully.');
}


public function edit(Shareholder $shareholder)
{
    return view('mumbos::admin.shareholders.edit', compact('shareholder'));
}




public function show(Shareholder $shareholder)
{
    $shareholder->load('customer');

    return view('mumbos::admin.shareholders.show', compact('shareholder'));
}


public function update(Request $request, Shareholder $shareholder)
{
    $messages = [
        'phone.unique' => 'The mobile number has already been taken. Each shareholder must have a unique phone number.',
        'id_number.unique' => 'The ID number has already been registered. Please verify it.',
        'joined_at.before_or_equal' => 'The joining date cannot be in the future. Please select today or an earlier date.',
    ];

    $data = $request->validate([
        'shareholder_number' => 'required|unique:shareholders,shareholder_number,' . $shareholder->id,
        'full_name'          => 'nullable|string',
        'id_number'          => 'nullable|string|unique:shareholders,id_number,' . $shareholder->id,
        'kra_pin'            => 'nullable|string',
        'email'              => 'nullable|email',
        'phone'              => 'nullable|string|unique:shareholders,phone,' . $shareholder->id,
        'postal_address'     => 'nullable|string',
        'physical_address'   => 'nullable|string',
        'city'               => 'nullable|string',
        'country'            => 'nullable|string',
        'share_class'        => 'nullable|string',
        'share_units'        => 'nullable|integer',
        'capital_paid'       => 'nullable|numeric',
        'joined_at'          => 'required|date|before_or_equal:today',
        'is_active'          => 'boolean',
        'is_board_member'    => 'nullable|boolean',
        'position'           => 'nullable|string',
    ], $messages);

    $data['is_active'] = $request->has('is_active');
    $data['is_board_member'] = $request->has('is_board_member');

    $shareholder->update($data);

    return redirect()->route('admin.shareholders.index')->with('success', 'Shareholder updated successfully.');
}


private function generateUniqueShareholderNumber()
{
    do {
        $number = 'M-SH' . str_pad(mt_rand(1, 99999), 7, '0', STR_PAD_LEFT);
    } while (Shareholder::where('shareholder_number', $number)->exists());

    return $number;
}

public function generateShareholderNumber()
{
    $number = $this->generateUniqueShareholderNumber();
    return response()->json(['shareholder_number' => $number]);
}

public function destroy(Shareholder $shareholder)
{
    $shareholder->delete();

    return redirect()->route('admin.shareholders.index')->with('success', 'Shareholder deleted successfully.');
}



public function downloadShareholderDocument($id, $documentType)
{
    $shareholder = Shareholder::findOrFail($id);
    $documentPath = $shareholder->getDocumentPath($documentType);

    if (Storage::exists($documentPath)) {
        return Storage::download($documentPath);
    }

    return redirect()->back()->with('error', 'Document not found.');
}

}
