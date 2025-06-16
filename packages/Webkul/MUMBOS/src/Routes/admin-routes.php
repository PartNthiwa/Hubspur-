<?php

use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Http\Controllers\Admin\MUMBOSController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareholderController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareholderGroupController;
use Webkul\MUMBOS\Http\Controllers\Admin\ContributionController;

// Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/shareholders'], function () {
//     
//         Route::get('', 'index')->name('admin.shareholders.index');
//         Route::get('create', 'create')->name('admin.shareholders.create');
//         Route::get('/{shareholder}', [ShareholderController::class, 'show'])->name('admin.shareholders.show');

//         Route::post('', 'store')->name('admin.shareholders.store');
//         Route::get('{id}/edit', 'edit')->name('admin.shareholders.edit');
//         Route::put('{id}', 'update')->name('admin.shareholders.update');
//         Route::delete('{id}', 'destroy')->name('admin.shareholders.destroy');


//     
// });
Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => 'admin/shareholders',
    'as' => 'admin.shareholders.', 
], function () {
    Route::controller(ShareholderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{shareholder}', 'show')->name('show');
        Route::get('/{shareholder}/edit', 'edit')->name('edit');
        Route::put('/{shareholder}', 'update')->name('update');
        Route::delete('/{shareholder}', 'destroy')->name('destroy');


     Route::post('/{shareholder}/allocate-shares', 'allocateShares')->name('allocate-shares');
     Route::put('/{shareholderId}/update-units/{shareId}', [ShareholderController::class, 'updateShareUnits'])->name('update-units');

    });
});


Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/shares'], function () {
    Route::controller(ShareController::class)->group(function () {
        Route::get('/', 'index')->name('admin.shares.index');
        Route::get('/create', 'create')->name('admin.shares.create');
        Route::post('/', 'store')->name('admin.shares.store');
        Route::get('/{share}/edit', 'edit')->name('admin.shares.edit');
           Route::get('/{share}', 'show')->name('admin.shares.show');
        Route::put('/{share}', 'update')->name('admin.shares.update');
        Route::delete('/{share}', 'destroy')->name('admin.shares.destroy');
    });
});



Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contributions'], function () {
    Route::controller(ContributionController::class)->group(function () {
        Route::get('', 'index')->name('admin.contributions.index');
        Route::get('create', 'create')->name('admin.contributions.create');
        Route::post('', 'store')->name('admin.contributions.store');

        Route::get('{contribution}/edit', 'edit')->name('admin.contributions.edit');
        Route::put('{contribution}', 'update')->name('admin.contributions.update');
        Route::delete('{contribution}', 'destroy')->name('admin.contributions.destroy');
        Route::get('{contribution}', 'show')->name('admin.contributions.show');
    });
});

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/mumbos'], function () {
    Route::controller(MUMBOSController::class)->group(function () {
        Route::get('', 'index')->name('admin.mumbos.index');
    });
});

