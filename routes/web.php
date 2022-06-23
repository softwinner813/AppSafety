<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Quick search dummy route to display html elements in search dropdown (header search)
Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');



Auth::routes();

/*
|--------------------------------------------------------------------------
| Forget Password Routes
|--------------------------------------------------------------------------
*/
Route::get('forget-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forget.password.get');
// Route::post('forget-password', 'Auth\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post'); 
// Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
// Route::post('reset-password', 'Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/policy', [App\Http\Controllers\HomeController::class, 'policy'])->name('policy');
// Route::get('/permit', [App\Http\Controllers\PermitController::class, 'index'])->name('permit');

// Profile
Route::group(['prefix' => 'setting', 'middleware' => 'auth'], function () {
    Route::get('/', function() {
        return redirect()->route('profile');
    });

    Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function () {
        Route::get('/', 'SettingController@index')->name('profile');
        Route::post('/save', 'SettingController@profileSave')->name('profile-save');
    });

    // Employee Email List
    Route::group(['prefix' => 'employee', 'middleware' => 'paid'], function () {
        Route::get('/', 'SettingController@employee')->name('employee');
        Route::post('/save', 'SettingController@employeeSave')->name('employee-save');
        Route::post('/delete', 'SettingController@employeeDelete')->name('employee-delete');
    });

    // Change Password
    Route::group(['prefix' => 'changepass', 'middleware' => 'auth'], function () {
        Route::get('/', 'SettingController@changePassword')->name('change-password');
        Route::post('/save', 'SettingController@changePassSave')->name('changepass-save');
    });

    // Membership
    Route::group(['prefix' => 'membership', 'middleware' => 'auth'], function () {
        Route::get('/', 'SettingController@membership')->name('membership');
        Route::post('/getCheckout', 'SettingController@getCheckout')->name('membership-getCheckout');
        //Paypal Payment
        Route::get('/paymentResult','Payment\PaypalController@paymentResult')->name('membership.paymentResult');
        Route::post('/paypal','Payment\PaypalController@postPaymentWithpaypal')->name('membership-postPaypal');
        Route::get('/paypal','Payment\PaypalController@getPaymentStatus')->name('membership-statusPaypal');
        Route::get('/paypal/cancel', 'Payment\PayPalController@cancel')->name('membership.paypal.cancel');
        Route::get('/paypal/success', 'Payment\PayPalController@success')->name('membership.paypal.success');
        // Stripe Payment
        Route::get('/stripe','Payment\StripeController@stripe')->name('membership.stripe');
        Route::post('/stripe','Payment\StripeController@stripePost')->name('membership.stripePost');
    });
});

// Document
Route::group(['prefix' => 'document'], function () {

    Route::group(['prefix' => 'audit'], function () {
        Route::get('/', 'Documents\Audit\AuditController@index')->name('document.audit')->middleware('auth');
    });


    Route::group(['prefix' => 'guidance'], function () {
        Route::get('/', 'Documents\Guidance\GuidanceController@index')->name('document.guidance')->middleware('auth');
        Route::post('/edit', 'Documents\Guidance\GuidanceController@edit')->name('document.guidance.edit')->middleware('auth');
        Route::post('/upload', 'Documents\Guidance\GuidanceController@upload')->name('document.guidance.upload');
        Route::post('/save', 'Documents\Guidance\GuidanceController@save')->name('document.guidance.save');
        Route::post('/resend', 'Documents\Guidance\GuidanceController@resendEmail')->name('document.guidance.resend');
        Route::get('/sign/{token}', 'Documents\Guidance\GuidanceController@sign')->name('document.guidance.sign');
    });


    Route::group(['prefix' => 'induction'], function () {
        Route::get('/', 'Documents\Induction\InductionController@index')->name('document.induction')->middleware('auth');
        Route::post('/edit', 'Documents\Induction\InductionController@edit')->name('document.induction.edit')->middleware('auth');
        Route::post('/upload', 'Documents\Induction\InductionController@upload')->name('document.induction.upload');
        Route::post('/save', 'Documents\Induction\InductionController@save')->name('document.induction.save');
        Route::post('/resend', 'Documents\Induction\InductionController@resendEmail')->name('document.induction.resend');
        Route::get('/sign/{token}', 'Documents\Induction\GuidanceController@sign')->name('document.induction.sign');
    });


    Route::group(['prefix' => 'incident'], function () {
        Route::get('/', 'Documents\Incident\IncidentController@index')->name('document.incident')->middleware('auth');
        Route::post('/edit', 'Documents\Incident\IncidentController@edit')->name('document.incident.edit')->middleware('auth');
        Route::post('/upload', 'Documents\Incident\IncidentController@upload')->name('document.incident.upload');
        Route::post('/save', 'Documents\Incident\IncidentController@save')->name('document.incident.save');
        Route::post('/resend', 'Documents\Incident\IncidentController@resendEmail')->name('document.incident.resend');
        Route::get('/sign/{token}', 'Documents\Incident\IncidentController@sign')->name('document.incident.sign');
    });

    Route::group(['prefix' => 'permit'], function () {
        Route::get('/', 'Documents\Permit\PermitController@index')->name('document.permit')->middleware('auth');
        Route::post('/edit', 'Documents\Permit\PermitController@edit')->name('document.permit.edit')->middleware('auth');
        Route::post('/upload', 'Documents\Permit\PermitController@upload')->name('document.permit.upload');
        Route::post('/save', 'Documents\Permit\PermitController@save')->name('document.permit.save');
        Route::post('/resend', 'Documents\Permit\PermitController@resendEmail')->name('document.permit.resend');
        Route::get('/sign/{token}', 'Documents\Permit\PermitController@sign')->name('document.permit.sign');
    });



    Route::group(['prefix' => 'box', 'middleware' => 'auth'], function () {
        // Inbox
        Route::get('/inbox/{type}', 'DocumentController@inbox')->name('document.box.inbox');
        Route::post('/inbox/search/{type}', 'DocumentController@inbox')->name('document.box.inbox.search');

        Route::get('/new/{type}', 'DocumentController@new')->name('document.box.new');
        Route::get('/sign/{id}', 'DocumentController@sign')->name('document.box.sign');
        Route::get('/moveDel/{id}', 'DocumentController@moveDel')->name('document.box.moveDel');
        Route::get('/preview/{id}', 'DocumentController@preview')->name('document.box.preview');
        Route::get('/download/{id}', 'DocumentController@download')->name('document.box.download');
        Route::get('/resend/{id}', 'DocumentController@resendEmail')->name('document.box.resend');
        Route::get('/restore/{id}', 'DocumentController@restore')->name('document.box.restore');
        Route::get('/delete/{id}', 'DocumentController@delete')->name('document.box.delete');

        // Detail Page
        Route::get('/detail/{id}', 'DocumentController@detail')->name('document.box.detail');

        // Sent Box
        Route::get('/sent/{type}', 'DocumentController@sent')->name('document.box.sent');
        Route::post('/sent/search/{type}', 'DocumentController@sent')->name('document.box.sent.search');

        // Deleted Box
        Route::get('/deleted/{type}', 'DocumentController@deleted')->name('document.box.deleted');
        Route::post('/deleted/search/{type}', 'DocumentController@deleted')->name('document.box.deleted.search');

    });


    Route::get('/', function() {
        return redirect()->route('document.audit');
    });

    // Route::get('/list/{type}', 'DocumentController@index')->name('document');
    // Route::get('/edit/{type}', 'DocumentController@edit')->name('document.edit');
    // Route::post('/save', 'DocumentController@upload')->name('document.upload');
    // Route::post('/delete', 'DocumentController@delete')->name('document.delete');
    // Route::post('/resend', 'DocumentController@resendEmail')->name('document.resend');

    // Route::post('/sendEmail', 'DocumentController@sendEmail')->name('document.sendEmail');
    // Route::post('/saveSign', 'DocumentController@saveSign')->name('document.saveSign');
    
    Route::get('/test', 'DocumentController@test')->name('document.test');
// 
    Route::get('/testEmail', 'DocumentController@testEmail')->name('document.testEmail');


});




// Users
Route::group(['prefix' => 'users', 'middleware' => 'admin'], function () {
    Route::get('/', 'Admin\UserAdminController@index');
    Route::post('/get', 'Admin\UserAdminController@get');
    Route::get('/detail/{id}', 'Admin\UserAdminController@detail');
    Route::post('/detail/datePicker', 'Admin\UserAdminController@datePicker');
    Route::get('/individual_detail/{id}', 'Admin\UserAdminController@individual_detail');
    Route::post('/individual_detail/datePicker', 'Admin\UserAdminController@individual_detail_datePicker');
    Route::get('/pdf_download', 'Admin\UserAdminController@pdf_download');
});


// Contact us
Route::group(['prefix' => 'contact'], function () {
    Route::get('/', 'ContactController@index')->name('contact');
    Route::post('/send', 'ContactController@send')->name('contact.send');
});