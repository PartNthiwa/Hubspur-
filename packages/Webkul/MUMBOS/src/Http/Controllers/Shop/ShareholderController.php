<?php
namespace Webkul\MUMBOS\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Customer\Models\Customer;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Share;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\DB;
use Webkul\Shop\Http\Controllers\Controller;

class ShareholderController extends Controller
{
    public function create()
    {
        return view('mumbos::shop.shareholders.create');
    }

    public function store(Request $request)
    {
  \Log::info('incoming', $request->all());
        // Validate the request data
        $data = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'nullable|email|unique:customers,email',
            'phone'          => 'required|string|max:20',
            'password'        => 'required|string|min:6|confirmed',

        ]);
        \Log::info('Validated data', $data);
        DB::beginTransaction();

        try {
            // Create minimal customer
            $customer = Customer::create([
                'first_name'  => $data['first_name'],
                 'last_name'  => $data['last_name'],
                'email'       => $data['email'] ?? null,
                'is_verified' => 1,
                'password'    => bcrypt($data['password']),
            ]);

            // Create shareholder
            Shareholder::create([
                'customer_id'    => $customer->id,
                'full_name'      => $data['first_name'],
                'email'          => $data['email'] ?? null,
                'phone'          => $data['phone'],
                'shareholder_number' => 'SH' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'is_active'      => false,
            ]);

            DB::commit();
           
            return redirect()->route('shop.shareholders.login')->with('success', 'Registration successful!');

        }catch (\Exception $e) {
            DB::rollback();
            \Log::error('Shareholder registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }

    }


    public function info()
    {
        $shareTypes = Share::where('is_active', true)
            ->where('visibility', true)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('mumbos::shop.shareholders.info', compact('shareTypes'));
    }


    public function showLoginForm()
{
    return view('mumbos::shop.shareholders.login');
}

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $customer = Customer::where('email', $request->email)->first();

    if (! $customer || ! Hash::check($request->password, $customer->password)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    // Optionally check if this customer is also a shareholder
    $isShareholder = Shareholder::where('customer_id', $customer->id)->exists();

    if (! $isShareholder) {
        return back()->withErrors([
            'email' => 'This account is not registered as a shareholder.',
        ]);
    }

    Auth::guard('customer')->login($customer);

    return redirect()->route('shop.shareholders.dashboard')->with('success', 'Login successful!');
}

public function logout(Request $request)
{
    Auth::guard('customer')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('shop.shareholders.login.form');
}
public function showForgotPasswordForm()
{
    return view('mumbos::shop.shareholders.forgot-password');
}

public function sendPasswordResetNotification($token)
{
    $this->notify(new \App\Notifications\ShareholderResetPasswordNotification($token));
}


public function showResetForm(Request $request, $token)
{
    return view('mumbos::shop.shareholders.reset-password', [
        'token' => $token,
        'email' => $request->email
    ]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $status = Password::broker('customers')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($customer, $password) {
            $customer->password = Hash::make($password);
            $customer->setRememberToken(Str::random(60));
            $customer->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('shop.shareholders.login')->with('success', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}



    public function thankYou()
    {
        return view('mumbos::shop.shareholders.thank-you');
    }

    public function terms()
    {
        return view('mumbos::shop.shareholders.terms');
    }

    public function confirm()
    {
        return view('mumbos::shop.shareholders.confirm');
    }
    public function allocate(Request $request)
    {
        $request->validate([
            'shareholder_id' => 'required|exists:shareholders,id',
            'share_id' => 'required|exists:shares,id',
            'units' => 'required|integer|min:1',
        ]);

        $shareholder = Shareholder::findOrFail($request->shareholder_id);
        $share = Share::findOrFail($request->share_id);

        // Allocate shares to the shareholder
        $shareholder->shares()->attach($share->id, [
            'units' => $request->units,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Shares allocated successfully.');
    }
  
    public function showShareholderProfile()
    {
        $shareholder = Auth::guard('customer')->user()->shareholder;

        if (!$shareholder) {
            return redirect()->route('shop.shareholders.login.form')->withErrors(['error' => 'You must be a registered shareholder to access this page.']);
        }

        return view('mumbos::shop.shareholders.profile', compact('shareholder'));
    }
    public function updateShareholderProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . Auth::guard('customer')->id(),
            'phone' => 'required|string|max:20',
        ]);

        $shareholder = Auth::guard('customer')->user()->shareholder;

        if (!$shareholder) {
            return redirect()->route('shop.shareholders.login.form')->withErrors(['error' => 'You must be a registered shareholder to access this page.']);
        }

        $shareholder->update($request->only('first_name', 'last_name', 'email', 'phone'));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    public function showShareholderTerms()
    {
        return view('mumbos::shop.shareholders.terms');
    }
    public function showShareholderPrivacyPolicy()
    {
        return view('mumbos::shop.shareholders.privacy-policy');
    }
    public function showShareholderFAQ()
    {
        return view('mumbos::shop.shareholders.faq');
    }
    public function showShareholderContact()
    {
        return view('mumbos::shop.shareholders.contact');
    }
    public function showShareholderHelp()
    {
        return view('mumbos::shop.shareholders.help');
    }


public function dashboard()
{
    $shareholder = auth()->user()->shareholder;

    if (!$shareholder) {
        return redirect()->route('shop.shareholders.register')->with('error', 'Please register as a shareholder first.');
    }

    // $shares = $shareholder->shares()->with('shareClass')->get();
    $shares = $shareholder->shares()->get();
    if ($shares->isEmpty()) {
        return redirect()->route('shop.shareholders.register')->with('info', 'You have no shares registered. Please register for shares.');
    }
    $totalUnits = $shares->sum('pivot.units');
    $totalValue = $shares->sum(function ($share) {
        return $share->pivot->units * $share->price_per_unit;
    });
    if ($totalUnits < 1) {
        return redirect()->route('shop.shareholders.register')->with('info', 'You have no shares registered. Please register for shares.');
    }
    if ($totalValue < 1) {
        return redirect()->route('shop.shareholders.register')->with('info', 'You have no shares registered. Please register for shares.');
    }

    return view('mumbos::shop.shareholders.dashboard', compact('shareholder', 'shares', 'totalUnits', 'totalValue'));
}


public function register(Request $request)
{
    if (! Auth::check()) {
        return redirect()
            ->route('shop.shareholders.login')
            ->with('error', 'Please log in to register for membership.');
    }

    $data = $request->validate([
        'share_id'    => 'required|exists:shares,id',
        'total_value' => 'required|numeric|min:1',
    ]);

    $shareholder = Auth::user()->shareholder;
    if (! $shareholder) {
        return back()->with('error', 'You must be registered as a shareholder.');
    }

    $share = Share::findOrFail($data['share_id']);
  
    if (! $shareholder) {
        return redirect()
            ->route('shop.shareholders.register.create')
            ->with('error', 'You must register as a shareholder before purchasing shares.');
    }

    // calculate units based on price_per_unit
    $units = floor($data['total_value'] / $share->price_per_unit);
    if ($units < 1) {
        return back()->with('error', 'The amount is too low to purchase any units.');
    }

    // attach (or syncWithoutDetaching) with pivot data
    $shareholder->shares()->attach($share->id, [
        'units'       => $units,
        // 'total_value' => $data['total_value'],
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    return back()->with('success',
        "You've successfully registered for {$units} units of {$share->class}."
    );
}

}