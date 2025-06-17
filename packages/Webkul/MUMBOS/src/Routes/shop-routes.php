<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;  
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;
use Webkul\MUMBOS\Http\Controllers\Shop\ShareholderController;
use Webkul\MUMBOS\Http\Controllers\Shop\MUMBOSController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareController;
use Webkul\MUMBOS\Http\Controllers\Shop\ShareholderAuthController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareholderGroupController;
use Webkul\MUMBOS\Http\Controllers\Admin\ContributionController;
Route::group([
    'middleware' => ['web', 'shop'],
], function () {
    // Shareholder Registration
    Route::get('/register/shareholder', [ShareholderController::class, 'create'])->name('shop.shareholders.register.create');
    Route::post('/register/shareholder', [ShareholderController::class, 'store'])->name('shop.shareholders.register.store');
    Route::get('/register/shareholder/info', [ShareholderController::class, 'info'])->name('shop.shareholders.register.info');
    Route::get('/register/shareholder/terms', [ShareholderController::class, 'terms'])->name('shop.shareholders.register.terms');
    Route::get('/register/shareholder/confirm', [ShareholderController::class, 'confirm'])->name('shop.shareholders.register.confirm');
    Route::get('/register/shareholder/thank-you', [ShareholderController::class, 'thankYou'])->name('shop.shareholders.register.thank-you');

    // Shareholder Login
    Route::get('/shareholder/login', [ShareholderController::class, 'showLoginForm'])->name('shop.shareholders.login.form');
    Route::post('/shareholder/login', [ShareholderController::class, 'login'])->name('shop.shareholders.login');
    Route::post('/shareholder/logout', [ShareholderController::class, 'logout'])->name('shop.shareholders.logout');

    // Shareholder Forgot Password (optional)
    Route::get('/shareholder/forgot-password', [ShareholderController::class, 'showForgotPasswordForm'])->name('shop.shareholders.forgot-password');
    // Route::post('/shareholder/forgot-password', [ShareholderController::class, 'sendResetLink'])->name('shop.shareholders.forgot-password.send');
    Route::get('/shareholder/reset-password/{token}', [ShareholderController::class, 'showResetForm'])->name('shop.shareholders.password.reset.form');

    Route::post('/shareholder/reset-password', [ShareholderController::class, 'resetPassword'])->name('shop.shareholders.password.reset');

    Route::post('/shareholder/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::broker('customers')->sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('shop.shareholders.forgot-password.send');

});