<?php

use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Http\Controllers\Shop\MUMBOSController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'mumbos'], function () {
    Route::get('', [MUMBOSController::class, 'index'])->name('shop.mumbos.index');
});