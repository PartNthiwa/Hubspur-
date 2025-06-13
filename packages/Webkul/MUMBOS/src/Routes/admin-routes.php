<?php

use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Http\Controllers\Admin\MUMBOSController;
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

//         // Level-3 submenus under Shareholders
//         Route::get('contact', 'contactInfo')->name('admin.shareholders.contact');
//         Route::get('investment-history', 'investmentHistory') ->name('admin.shareholders.history');
//     
// });
Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => 'admin/shareholders',
    'as' => 'admin.shareholders.', // ✅ Add this line
], function () {
    Route::controller(ShareholderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{shareholder}', 'show')->name('show');
        Route::get('/{shareholder}/edit', 'edit')->name('edit');
        Route::put('/{shareholder}', 'update')->name('update');
        Route::delete('/{shareholder}', 'destroy')->name('destroy');
    });
});




Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contributions'], function () {
    Route::controller(ContributionController::class)->group(function () {
        Route::get('', 'index')->name('admin.contributions.index');
        Route::get('create', 'create')->name('admin.contributions.create');
        Route::post('', 'store')->name('admin.contributions.store');
        Route::get('{id}/edit', 'edit')->name('admin.contributions.edit');
        Route::put('{id}', 'update')->name('admin.contributions.update');
        Route::delete('{id}', 'destroy')->name('admin.contributions.destroy');
    });
});

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/mumbos'], function () {
    Route::controller(MUMBOSController::class)->group(function () {
        Route::get('', 'index')->name('admin.mumbos.index');
    });
});
