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
use Webkul\MUMBOS\Http\Controllers\Admin\LeadershipController;
use Webkul\MUMBOS\Http\Controllers\Admin\TeamController;
use Webkul\MUMBOS\Http\Controllers\Admin\MpesaCallbackController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contributions'], function () {


    
    Route::resource('membership-types', MembershipTypeController::class, ['as' => 'admin']);
    Route::resource('phases', PhaseController::class, ['as' => 'admin']);
    Route::resource('incentives', IncentiveController::class, ['as' => 'admin']);

});

Route::post('admin/contributions/phases/{id}/restore', [PhaseController::class, 'restore'])
    ->name('admin.contributions.phases.restore.custom');

Route::delete('admin/contributions/phases/{id}/force-delete', [PhaseController::class, 'forceDelete'])
    ->name('admin.contributions.phases.force-delete.custom');

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

        Route::get('/{shareholder_number}/statement', 'generateStatement')->name('statement');

        Route::post('/send-email','sendEmail')->name('send-email');

     Route::post('/{shareholder}/allocate-shares', 'allocateShares')->name('allocate-shares');
     Route::put('/{shareholderId}/update-units/{shareId}',  'updateShareUnits')->name('update-units');

   

    });
});


Route::prefix('admin/shares')
     ->middleware(['web','admin'])
     ->controller(ShareController::class)
     ->group(function () {

          
    // INDEX / CREATE / STORE
    Route::get('/',       'index')->name('admin.shares.index');
    Route::get('create',  'create')->name('admin.shares.create');
    Route::post('/',      'store')->name('admin.shares.store');

    // ALLOCATE FORM & ACTION
    Route::get('{share}/allocate',               'allocateForm')->name('admin.shares.allocate-form');
    Route::post('{share}/allocate',              'allocate')->name('admin.shares.allocate');
 

    // EDIT / UPDATE / DELETE
    Route::get('{share}/edit',   'edit')->name('admin.shares.edit');
    Route::put('{share}',         'update')->name('admin.shares.update');
    Route::delete('{share}',      'destroy')->name('admin.shares.destroy');
Route::put('{share}/allocate/{shareholder}', 'updateAllocation') ->name('admin.shares.update-allocation');

    // SHOW (always last, so it doesn’t “catch” other URLs)
    Route::get('{share}',         'show')->name('admin.shares.show');
});;



Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/contributions'], function () {
    Route::controller(ContributionController::class)->group(function () {
        Route::get('', 'index')->name('admin.contributions.index');
        Route::get('create', 'create')->name('admin.contributions.create');
        Route::post('', 'store')->name('admin.contributions.store');

        Route::get('{contribution}/edit', 'edit')->name('admin.contributions.edit');
        Route::put('{contribution}', 'update')->name('admin.contributions.update');
        Route::delete('{contribution}', 'destroy')->name('admin.contributions.destroy');
        Route::get('{contribution}', 'show')->name('admin.contributions.show');

        Route::post('{contribution}/approve', 'approve')->name('admin.contributions.approve');
        Route::post('{contribution}/reject', 'reject')->name('admin.contributions.reject');

        Route::post('{contribution}/recheck', 'recheckStatus')->name('admin.contributions.recheck');

        Route::post('pay', 'initiate')->name('admin.contributions.pay');

    
    });
});

Route::post('/admin/contributions/bulk-action', [ContributionController::class, 'bulkAction'])
    ->middleware(['web', 'admin'])
    ->name('admin.contributions.bulk-action');

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



Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => 'admin/leaders',
    'as' => 'admin.leaders.',
], function () {
    Route::controller(LeadershipController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{leader}', 'show')->name('show');
        Route::get('/{leader}/edit', 'edit')->name('edit');
        Route::put('/{leader}', 'update')->name('update');
        Route::delete('/{leader}', 'destroy')->name('destroy');
    });
});

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => 'admin/teams',
    'as' => 'admin.teams.',
], function () {
    Route::controller(TeamController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{team}', 'show')->name('show');
        Route::get('/{team}/edit', 'edit')->name('edit');
        Route::put('/{team}', 'update')->name('update');
        Route::delete('/{team}', 'destroy')->name('destroy');
    });
});