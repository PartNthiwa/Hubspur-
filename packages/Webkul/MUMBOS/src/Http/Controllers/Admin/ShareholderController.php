<?php

namespace Webkul\MUMBOS\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\MembershipType;
use Webkul\MUMBOS\Models\Phase;
use Webkul\MUMBOS\Models\Incentive;
use Webkul\MUMBOS\Models\Share;
use Webkul\MUMBOS\Models\ContactUs;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Webkul\Customer\Models\Customer;
use Webkul\MUMBOS\Models\Contribution;
use Illuminate\Http\Request;

use App\Mail\ShareholderMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Webkul\MUMBOS\Http\Requests\ShareholderRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\ShareholderInvitationNotification;


class ShareholderController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {   
        $shares = Share::all(); 
      $shareholders = Shareholder::with(['customer', 'shares', 'incentives', 'contributions', 'membershipTypes'])->paginate(20);

        return view('mumbos::admin.shareholders.index', compact('shareholders', 'shares'));
    }

public function sendEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
        'attachments.*' => 'file|mimes:pdf,docx,zip,png,jpg|max:2048'
    ]);

    // Save uploaded files temporarily and collect paths
    $attachments = [];
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $attachments[] = $file->store('temp_emails');
        }
    }

    Mail::to($request->email)
        ->queue(new ShareholderMessage(
            $request->subject,
            $request->message,
            $attachments
        ));

    return back()->with('success', 'Email queued successfully.');
}

public function generateStatement($shareholderNumber)
{
    $shareholder = Shareholder::with([
    'customer',
    'contributions.phase', 
    'incentives'
])->where('shareholder_number', $shareholderNumber)
  ->firstOrFail();


    $pdf = Pdf::loadView('mumbos::admin.shareholders.statement-pdf', compact('shareholder'));

    if (request()->query('download') === 'true') {
        return $pdf->download("statement_{$shareholderNumber}.pdf");
    }

    return $pdf->stream("statement_{$shareholderNumber}.pdf"); // Open in browser
}
public function contactUs()
{
    $messages = ContactUs::latest()->paginate(20);
    return view('mumbos::admin.shareholders.contact-us', compact('messages'));
}
public function showContactMessage($id)
{
    $message = ContactUs::findOrFail($id);
    return view('mumbos::admin.shareholders.contact-us-show', compact('message'));
}

public function deleteContactMessage($id)
{
    $message = ContactUs::findOrFail($id);
    $message->delete();

    return redirect()->route('admin.shareholders.contact-us')->with('success', 'Message deleted successfully.');
}


  public function create()
{
    $shares = Share::all(); 
    $phases = Phase::all();
    $incentives = Incentive::all();
    $customers = Customer::doesntHave('shareholder')->get();
$membershipTypes = MembershipType::where('is_active', true)->get();
    return view('mumbos::admin.shareholders.create', compact('customers', 'shares', 'phases', 'incentives','membershipTypes'));
}

   public function store(Request $request)
{


    $messages = [
        'phone.unique' => 'The mobile number has already been taken. Each shareholder must have a unique phone number.',
        'id_number.unique' => 'The ID number has already been registered. Please verify it.',
        'joined_at.before_or_equal' => 'The joining date cannot be in the future. Please select today or an earlier date.',
    ];

    $data = $request->validate([

        'customer_id'        => 'nullable|exists:customers,id',
        'first_name'  => 'required|string|max:255',
        'last_name'   => 'required|string|max:255',
        'email'            => 'required|email|unique:customers,email',
        'phase_id'           => 'required|exists:phases,id',
        'memberships' => 'nullable|array',
        'memberships.*.amount_paid' => 'nullable|numeric|min:0',
       
        'incentives'         => 'nullable|array',
        'incentives.*'       => 'exists:incentives,id',
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

    $customerId = $validated['customer_id'] ?? null;

 if (!$customerId) {
    $customer = Customer::create([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'password'   => Hash::make(Str::random(12)), // temp password
        'is_verified' => true,
    ]);

    $token = Password::broker('customers')->createToken($customer);
    // Send invite to set password
    $customer->notify(new ShareholderInvitationNotification($token));
    
    $customerId = $customer->id;
}



    $data['customer_id'] = $customer->id;
    $data['shareholder_number'] = $this->generateUniqueShareholderNumber();
    $data['is_active'] = $request->has('is_active');
    $data['is_board_member'] = $request->has('is_board_member');

    $shareholder = Shareholder::create($data);

     // Save contribution
   if ($request->filled('memberships')) {
    foreach ($request->memberships as $membershipTypeId => $membershipData) {
        if (!empty($membershipData['amount_paid'])) {
            // Save to pivot table
            $shareholder->membershipTypes()->attach($membershipTypeId, [
                'amount_paid' => $membershipData['amount_paid'],
                //  'phase_id'    => $request->input('phase_id'),
            ]);

            // Also log the contribution
            \Webkul\MUMBOS\Models\Contribution::create([
                'shareholder_id' => $shareholder->id,
                'amount'         => $membershipData['amount_paid'],
                'type'           => 'membership',
                'phase_id'       => $request->input('phase_id'),
                'payment_method' => 'manual',
                'payment_status' => 'completed',
                'currency'       => 'KES',
                'contributed_at' => now(),
                'status'         => 'pending',
                'recorded_by'    => auth('admin')->id(),
                'notes'          => 'Membership Type ID: ' . $membershipTypeId,
            ]);
        }
    }
}

    // Attach incentives
  if ($request->filled('incentives')) {
    $shareholder->incentives()->sync($request->incentives); 
}
   
    return redirect()->route('admin.shareholders.index')->with('success', 'Shareholder created successfully.');
}

public function edit(Shareholder $shareholder)
{
    $shareholder->load([
        'customer',
        'shares',
        'incentives',
        'membershipTypes',
        'contributions'
    ]);

    $shares = Share::all();
    $customers = Customer::all();
    $phases = Phase::all();
    $membershipTypes = MembershipType::all();
    $incentives = Incentive::all();

    return view('mumbos::admin.shareholders.edit', compact(
        'shareholder',
        'shares',
        'customers',
        'phases',
        'membershipTypes',
        'incentives'
    ));
}




public function show(Shareholder $shareholder)
{
   
    $shareholder->load('shares');

    $shareholder->load('customer');

    return view('mumbos::admin.shareholders.show', compact('shareholder'));
}


public function update(Request $request, Shareholder $shareholder)
{
    Log::info('Update called for shareholder:', ['shareholder' => $shareholder->id]);

    $messages = [
        'phone.unique' => 'The mobile number has already been taken.',
        'id_number.unique' => 'The ID number has already been registered.',
        'joined_at.before_or_equal' => 'The joining date cannot be in the future.',
    ];

    Log::info('Running validation...');

    $data = $request->validate([
        // 'shareholder_number' => 'required|unique:shareholders,shareholder_number,' . $shareholder->id . ',id',
        'full_name'          => 'nullable|string',
        'id_number'          => 'nullable|string|unique:shareholders,id_number,' . $shareholder->id . ',id',
        'kra_pin'            => 'nullable|string',
        'email'              => 'nullable|email',
        'phone'              => 'nullable|string|unique:shareholders,phone,' . $shareholder->id . ',id',
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
        'incentives'         => 'nullable|array',
        'incentives.*'       => 'exists:incentives,id',
        'membership_types'   => 'nullable|array',
        'membership_types.*' => 'exists:membership_types,id',
    ], $messages);

    Log::info('Validation passed.', $data);

    $data['is_active'] = $request->has('is_active');
    $data['is_board_member'] = $request->has('is_board_member');

    Log::info('Updating shareholder...', ['data' => $data]);

    $shareholder->update($data);

    Log::info('Shareholder updated.');

    $shareholder->incentives()->sync($request->input('incentives', []));

    if ($request->filled('memberships')) {
        Log::info('Handling memberships...');
        foreach ($request->input('memberships') as $membershipId => $details) {
            if (isset($details['selected']) && $details['selected']) {
                $amount = isset($details['amount_paid']) ? floatval($details['amount_paid']) : 0;

                Log::info('Syncing membership type', ['membership_id' => $membershipId, 'amount' => $amount]);

                $shareholder->membershipTypes()->syncWithoutDetaching([
                    $membershipId => [
                        'amount_paid' => $amount,
                        // 'phase_id'    => $request->input('phase_id'),
                    ],
                ]);

                Contribution::create([
                    'shareholder_id' => $shareholder->id,
                    'amount'         => $amount,
                    'type'           => 'membership',
                    'phase_id'       => $request->input('phase_id'),
                    'payment_method' => 'manual',
                    'payment_status' => 'completed',
                    'currency'       => 'KES',
                    'contributed_at' => now(),
                    'status'         => 'pending',
                    'recorded_by'    => auth('admin')->id(),
                ]);
            }
        }
    }

    Log::info('Update completed. Redirecting...');

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
    try {
        // Optional: Detach related shares first (to clean up pivot data)
        $shareholder->shares()->detach();

        // Optional: You can also delete any related documents/files if applicable here

        // Then delete the shareholder
        $shareholder->delete();

        return redirect()
            ->route('admin.shareholders.index')
            ->with('success', 'Shareholder deleted successfully.');
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->with('error', 'An error occurred while deleting the shareholder: ' . $e->getMessage());
    }
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




public function allocateShares(Request $request, Shareholder $shareholder)
{
    $request->validate([
        'share_id' => 'required|exists:shares,id',
        'units' => 'required|integer|min:1',
    ]);

    $shareId = $request->input('share_id');
    $unitsToAdd = $request->input('units');

    // Check if the shareholder already owns this share class
    $existing = $shareholder->shares()->where('share_id', $shareId)->first();

    if ($existing) {
        // Update existing units
        $currentUnits = $existing->pivot->units;
        $shareholder->shares()->updateExistingPivot($shareId, [
            'units' => $currentUnits + $unitsToAdd,
        ]);
    } else {
        // Create new allocation
        $shareholder->shares()->attach($shareId, [
            'units' => $unitsToAdd,
        ]);
    }

    return redirect()->back()->with('success', 'Shares allocated successfully.');
}



public function updateShareUnits(Request $request, $shareholderId, $shareId)
{
    $shareholder = Shareholder::findOrFail($shareholderId);
    $share = Share::findOrFail($shareId);

    $request->validate([
        'units' => 'required|integer|min:0',
    ]);

    if ($request->units == 0) {
        $shareholder->shares()->detach($share->id);
    } else {
        $shareholder->shares()->updateExistingPivot($share->id, [
            'units' => $request->units,
        ]);
    }

    return back()->with('success', 'Share units updated.');
}
public function sendResetLink($shareholderNumber)
{
    $shareholder = Shareholder::where('shareholder_number', $shareholderNumber)->firstOrFail();

    if (empty($shareholder->email)) {
        return back()->withErrors(['error' => 'This shareholder does not have an email address.']);
    }

    $customer = Customer::where('email', $shareholder->email)->first();

    if (!$customer) {
        return back()->withErrors(['error' => 'Linked customer not found.']);
    }

    // Send password reset link via the customer password broker
    Password::broker('shareholders')->sendResetLink(['email' => $customer->email]);

    // Log invitation and enable access
    $shareholder->update([
        'password_invitation_sent_at' => now(),
        'can_reset_password' => true,
    ]);

    return back()->with('success', 'Password reset link sent to shareholder.');
}


}