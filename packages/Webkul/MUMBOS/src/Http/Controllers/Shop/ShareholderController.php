<?php
namespace Webkul\MUMBOS\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Customer\Models\Customer;
use Webkul\MUMBOS\Models\Shareholder;
use Illuminate\Support\Facades\Auth;    
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
                'is_active'      => true,
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
        return view('mumbos::shop.shareholders.info');
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

}