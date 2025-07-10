<?php

use Illuminate\Support\Facades\Route;
use Webkul\MUMBOS\Http\Controllers\Admin\MUMBOSController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareholderController;
use Webkul\MUMBOS\Http\Controllers\Admin\ShareholderGroupController;
use Webkul\MUMBOS\Http\Controllers\Admin\MembershipTypeController;
use Webkul\MUMBOS\Http\Controllers\Admin\ContributionController;
use Webkul\MUMBOS\Http\Controllers\Admin\PhaseController;
use Webkul\MUMBOS\Http\Controllers\Admin\IncentiveController;

use Webkul\MUMBOS\Http\Controllers\Admin\MpesaCallbackController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contributions'], function () {

    Route::resource('membership-types', MembershipTypeController::class, ['as' => 'admin']);
    Route::resource('phases', PhaseController::class, ['as' => 'admin']);
    Route::resource('incentives', IncentiveController::class, ['as' => 'admin']);


});



Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => 'admin/shareholders',
    'as' => 'admin.shareholders.', 
], function () {
    Route::controller(ShareholderController::class)->group(function () {

         Route::get('/contact-us','contactUs')->name('contact-us');
        Route::get('/contact-us/{id}', 'showContactMessage')->name('contact-us.show');
        Route::delete('/contact-us/{id}', 'deleteContactMessage')->name('contact-us.destroy');

        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{shareholder}', 'show')->name('show');
        Route::get('/{shareholder}/edit', 'edit')->name('edit');
        Route::put('/{shareholder}', 'update')->name('update');
        Route::delete('/{shareholder}', 'destroy')->name('destroy');
        Route::post('/{shareholderNumber}/send-reset-link',  'sendResetLink')
            ->name('send-reset-link');


     Route::post('/{shareholder}/allocate-shares', 'allocateShares')->name('allocate-shares');
     Route::put('/{shareholderId}/update-units/{shareId}',  'updateShareUnits')->name('update-units');

   

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

 
          Route::post('{contribution}/approve', 'approve') ->name('admin.contributions.approve');
        Route::post('{contribution}/reject', 'reject') ->name('admin.contributions.reject');
        // Route::get('{contribution}/receipt-preview', 'previewReceipt')->name('admin.contributions.receipt-preview');
   
Route::post('{contribution}/recheck', [ContributionController::class, 'recheckStatus'])
    ->name('admin.contributions.recheck');


    Route::post('/admin/contributions/pay',  'initiate')->name('admin.contributions.pay');

    });


});


Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/mumbos'], function () {
    Route::controller(MUMBOSController::class)->group(function () {
        Route::get('', 'index')->name('admin.mumbos.index');
    });
});

// routes/web.php
Route::get('/admin/contributions/{contribution}/receipt-preview', [ContributionController::class, 'previewReceipt'])
    ->name('admin.contributions.receipt-preview')
    ->middleware('signed');

Route::get('admin/contributions/{contribution}/receipt', [ContributionController::class, 'downloadReceipt'])
     ->name('admin.contributions.receipt.download');

Route::put('/admin/incentives/{incentive_id}/shareholder/{shareholder_number}/update-units', [
    IncentiveController::class, 'updateUnits'
])->name('admin.incentives.update-units');
