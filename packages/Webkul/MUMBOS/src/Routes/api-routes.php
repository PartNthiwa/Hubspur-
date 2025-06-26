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
use Webkul\Customer\Models\Customer;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Share;
use Webkul\MUMBOS\Models\ShareholderGroup;
use Webkul\MUMBOS\Models\ShareholderContribution;
use Webkul\MUMBOS\Models\ShareholderAuth;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Http\Controllers\Shop\ShareholderGroupController;
use Webkul\MUMBOS\Http\Controllers\Shop\ShareController;
use Webkul\MUMBOS\Http\Controllers\Shop\ShareholderContributionController;

use Webkul\MUMBOS\Http\Controllers\Shop\ShareholderController;

use Webkul\MUMBOS\Http\Controllers\Shop\ContributionController;
use Webkul\MUMBOS\Http\Controllers\Admin\MpesaCallbackController;

Route::any('/clk', [MpesaCallbackController::class, 'handleCallback'])->name('clk');

Route::get('/hello', [MpesaCallbackController::class, 'hello']);
