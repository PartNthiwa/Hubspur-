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
use Webkul\MUMBOS\Models\ContactUs;
use Webkul\MUMBOS\Models\MembershipType;
use Illuminate\Support\Facades\DB;
use Webkul\Shop\Http\Controllers\Controller;
use App\Charts\CapitalContributionChart;
use Webkul\MUMBOS\Models\Team;

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
          $membershipTypes = MembershipType::where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();

 
    $teams = Team::with(['leaders' => function ($query) {
        $query->where('status', 'active')->orderBy('priority');
    }])->get();


    return view('mumbos::shop.shareholders.info', compact('membershipTypes','teams'));

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



// public function register(Request $request)
// {
//     // 1. Ensure user is logged in
//     if (! Auth::check()) {
//         return redirect()
//             ->route('shop.shareholders.login')
//             ->with('error', 'Please log in to register for membership.');
//     }

//     // 2. Validate input
//     $data = $request->validate([
//         'share_id'    => 'required|exists:shares,id',
//         'total_value' => 'required|numeric|min:1',
//     ]);

//     $shareholder = Auth::user()->shareholder;
//     if (! $shareholder) {
//         return redirect()
//             ->route('shop.shareholders.login')
//             ->with('error', 'You must register as a shareholder before purchasing shares.');
//     }

//     $share = Share::findOrFail($data['share_id']);

//     // 3. Compute units
//     $units = floor($data['total_value'] / $share->price_per_unit);
//     if ($units < 1) {
//         return back()->with('error', 'The amount is too low to purchase any units.');
//     }

//     // 4. Check if shareholder already has this share
//     DB::transaction(function () use ($shareholder, $share, $units) {
//         if ($shareholder->shares()->where('share_id', $share->id)->exists()) {
//             // already has some units → increment
//             $shareholder->shares()->updateExistingPivot(
//                 $share->id,
//                 [
//                     'units'      => DB::raw("units + {$units}"),
//                     'updated_at' => now(),
//                 ]
//             );
//         } else {
//             // first purchase of this share class
//             $shareholder->shares()->attach($share->id, [
//                 'units'      => $units,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }
//     });


//     return redirect()
//         ->route('shop.shareholders.dashboard')         
//         ->with('success', "You now own {$units} more units of “{$share->class}”.");
// }

public function editProfile()
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('shop.shareholders.login')->with('error', 'You must be logged in to access the dashboard.');
    }

    if (!$user->shareholder) {
        return redirect()->route('shop.shareholders.login')
                         ->with('error', 'Please register as a shareholder first.');
    }

    $shareholder = $user->shareholder;


    if (! $shareholder) {
        return redirect()->route('shop.shareholders.login')->with('error', 'Please register as a shareholder.');
    }

    return view('mumbos::shop.shareholders.profile.edit', compact('shareholder'));
}
public function viewProfile()
{
     $user = auth()->user();

    if (!$user) {
        return redirect()->route('shop.shareholders.login')->with('error', 'You must be logged in to access the dashboard.');
    }

    if (!$user->shareholder) {
        return redirect()->route('shop.shareholders.login')
                         ->with('error', 'Please register as a shareholder first.');
    }

    $shareholder = $user->shareholder;


    return view('mumbos::shop.shareholders.profile.show', compact('shareholder'));
}

public function updateProfile(Request $request)
{
    $shareholder = Auth::user()->shareholder;

    $data = $request->validate([
        'full_name'         => 'required|string|max:255',
        'phone'             => 'nullable|string|max:20',
        'email'             => 'nullable|email|max:255',
        'id_number'         => 'nullable|string|max:255',
        'kra_pin'           => 'nullable|string|max:255',
        'postal_address'    => 'nullable|string|max:255',
        'physical_address'  => 'nullable|string|max:255',
        'city'              => 'nullable|string|max:255',
        'country'           => 'nullable|string|max:255',
    ]);

    $shareholder->update($data);

    return redirect()->route('shop.shareholders.profile')->with('success', 'Profile updated successfully.');
}
 
    public function profile()
    {
        $shareholder = Auth::user()->shareholder;
        if (!$shareholder) {
            return redirect()->route('shop.shareholders.login')->with('error', 'Please register as a shareholder.');
        }
        return view('mumbos::shop.shareholders.profile', compact('shareholder'));   
    }

    public function showChangePasswordForm()
{
    $shareholder = Auth::user()->shareholder;

    return view('mumbos::shop.shareholders.profile.change-password', compact('shareholder'));
}

       public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $shareholder = Auth::user()->shareholder;

        if (! Hash::check($request->current_password, $shareholder->customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $shareholder->customer->password = Hash::make($request->new_password);
        $shareholder->customer->save();

        return redirect()->route('shop.shareholders.profile')->with('success', 'Password changed successfully.');
    }


public function send(Request $request)
{
    $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'message' => 'required|string|max:2000',
    ]);


    ContactUs::create($validated);

    Mail::raw($validated['message'], function ($mail) use ($validated) {
        $mail->to('support@mumbodiaspora.org')
             ->subject("New Message from {$validated['name']}")
             ->replyTo($validated['email']);
    });

    return back()->with('success', 'Your message has been sent successfully!');
}


public function support(Request $request)
{
    if (!Auth::check()) {
        abort(403, 'Unauthorized. Please log in to submit this form.');
    }


    $shareholder = Auth::user()->shareholder;
    // Check if the user is a shareholder
    if (!$shareholder) {
        abort(403, 'You must be a shareholder to submit this form.');
    }

    // Optional: Add additional status checks
   if (!$shareholder->is_active) {
    abort(403, 'Your shareholder account is not active.');
}

    // Validate form input
    $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email',
        'message' => 'required|string|max:2000',
    ]);

    $fullMessage = "Shareholder #: {$shareholder->shareholder_number}\n\n";
    $fullMessage .= $validated['message'];

  
    ContactUs::create([
        'name'    => $validated['name'],
        'email'   => $validated['email'],
        'message' => $fullMessage,
    ]);

    // Send email
    Mail::raw($fullMessage, function ($mail) use ($validated) {
        $mail->to('support@mumbodiaspora.org')
             ->subject("New Message from {$validated['name']}")
             ->replyTo($validated['email']);
    });

    return back()->with('info', 'You will contacted before end of day Today!');
}


public function dashboard()
{
    $user = auth('customer')->user();

    if (! $user || ! $user->shareholder) {
        return redirect()->route('shop.shareholders.login')
            ->with('error', 'Please login and register as shareholder.');
    }

    $shareholder = $user->shareholder()->with([
        'customer',
        'shares',
        'incentives',
        'contributions.phase',
        'phase',
    ])->first();

    if ($shareholder->is_active != 1) {
        abort(403, 'Your shareholder account is not active.');
    }

    // Capital Contributions by Phase
    $capitalGroups = $shareholder->contributions
        ->where('type', 'capital')->where('status', 'approved')
        ->groupBy(fn($c) => $c->phase->name ?? 'Unknown');

    $capitalLabels = $capitalGroups->keys();
    $capitalData = $capitalGroups->map(fn($g) => $g->sum('amount'))->values();

    // Contribution Timeline (monthly)
    $timelineGroups = $shareholder->contributions
        ->where('status', 'approved')->sortBy('created_at')
        ->groupBy(fn($c) => $c->created_at->format('Y-m'));

    $timelineLabels = $timelineGroups->keys();
    $timelineData = $timelineGroups->map(fn($g) => $g->sum('amount'))->values();

    // Share Distribution (pie)
    $shareLabels = $shareholder->shares->pluck('class');
    $shareData = $shareholder->shares->map(fn($s) => $s->pivot->units);

    // Incentives Over Time
    $incentiveGroups = $shareholder->incentives->sortBy('created_at')
        ->groupBy(fn($i) => $i->created_at->format('Y-m'));

    $incentiveLabels = $incentiveGroups->keys();
    $incentiveData = $incentiveGroups->map(fn($g) => $g->sum('amount'))->values();

    // Stacked Bar: Contributions by Type and Phase
    $stackedGroups = $shareholder->contributions
        ->where('status', 'approved')
        ->groupBy(fn($c) => $c->type)
        ->map(fn($items) => $items->groupBy(fn($c) => $c->phase->name ?? 'Unknown'));

    $phases = $shareholder->contributions->pluck('phase.name')->unique()->filter()->values();
    $types = $stackedGroups->keys();

  $stackedData = [];
    foreach ($types as $type) {
        $data = $phases->map(fn($phase) =>
            isset($stackedGroups[$type][$phase])
                ? $stackedGroups[$type][$phase]->sum('amount')
                : 0
        );

        $stackedData[] = [
            'label' => ucfirst($type),
            'data' => $data->toArray(), // Ensure array for JS
        ];
    }
return view('mumbos::shop.shareholders.dashboard', [
    'shareholder' => $shareholder,
    'capitalLabels' => $capitalLabels->toArray(),
    'capitalData' => $capitalData->toArray(),
    'timelineLabels' => $timelineLabels->toArray(),
    'timelineData' => $timelineData->toArray(),
    'shareLabels' => $shareLabels->toArray(),
    'shareData' => $shareData->toArray(),
    'incentiveLabels' => $incentiveLabels->toArray(),
    'incentiveData' => $incentiveData->toArray(),
    'phases' => $phases->toArray(),
    'stackedData' => $stackedData
]);


}


public function view()
{
    $user = auth('customer')->user();
    $shareholder = optional($user)->shareholder;

    return view('mumbos::shop.shareholders.card', compact('shareholder'));
}

public function cardDownload()
{
    $user = auth('customer')->user();
    $shareholder = optional($user)->shareholder;

    $pdf = \PDF::loadView('mumbos::shop.shareholders.card-pdf', compact('shareholder'));

    return $pdf->download('shareholder_card.pdf');
}


}