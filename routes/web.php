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
    Route::group(['prefix' => 'employee', 'middleware' => 'auth'], function () {
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
        // Stripe Payment
        Route::get('/stripe','Payment\StripeController@stripe')->name('membership.stripe');
        Route::post('/stripe','Payment\StripeController@stripePost')->name('membership.stripePost');
    });
});

// Document
Route::group(['prefix' => 'document', 'middleware' => 'auth'], function () {
    Route::get('/', function() {
        return redirect()->route('document',[1]);
    });
    Route::get('/list/{type}', 'DocumentController@index')->name('document');
    Route::get('/edit/{type}', 'DocumentController@edit')->name('document.edit');
    Route::post('/upload', 'DocumentController@upload')->name('document.upload');
    Route::post('/delete', 'DocumentController@delete')->name('document.delete');
    Route::get('/sendEmail', 'DocumentController@sendEmail')->name('document.sendEmail');
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