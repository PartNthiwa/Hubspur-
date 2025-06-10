<?php

use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Http\Controllers\Admin\MUMBOSController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/mumbos'], function () {
    Route::controller(MUMBOSController::class)->group(function () {
        Route::get('', 'index')->name('admin.mumbos.index');
    });
});