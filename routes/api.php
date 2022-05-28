<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::get('/', 'ApiController@index');


    Route::post('/login', 'ApiController@login');
    Route::post('/register', 'ApiController@register');
    Route::post('/registerUser', 'ApiController@registerUser');
    Route::post('/updateUser', 'ApiController@updateUser');
    Route::post('/changePassword', 'ApiController@changePassword');
    Route::post('/uploadAvatar', 'ApiController@uploadAvatar');
    Route::post('/sendCode', 'ApiController@sendCode');
    Route::post('/verifyCode', 'ApiController@verifyCode');
    Route::post('/resetPassword', 'ApiController@resetPassword');
    
    
    

    Route::get('/logout', 'ApiController@logout')->middleware('auth:api');

    //Route::group(['prefix' => 'trackOption','middleware' => ['auth:api']], function () {
	Route::group(['prefix' => 'trackOption'], function () {
	    Route::post('/getTrackOption', 'ApiController@getTrackOption');
	    Route::post('/saveTrackOption', 'ApiController@saveTrackOption');
	});

    Route::group(['prefix' => 'reminder'], function () {
	    Route::post('/getTime', 'ApiController@getTime');
	    Route::post('/saveTime', 'ApiController@saveTime');
	});


    Route::group(['prefix' => 'setTrack'], function () {
 	Route::group(['prefix' => 'nutrition'], function () {
		    Route::post('/get', 'ApiController@getNutrition');
		    Route::post('/save', 'ApiController@saveNutrition');
		});

	 	Route::group(['prefix' => 'sleep'], function () {
		    Route::post('/get', 'ApiController@getSleep');
		    Route::post('/save', 'ApiController@saveSleep');
		});

		Route::group(['prefix' => 'physical'], function () {
		    Route::post('/get', 'ApiController@getPhysical');
		    Route::post('/uploadTongue', 'ApiController@uploadTongue');
		    Route::post('/save', 'ApiController@savePhysical');
		    Route::post('/symptomsList', 'ApiController@getSymptomsList');
		    Route::post('/dreamSectionsList', 'ApiController@getdreamSectionsList');
		});

		Route::group(['prefix' => 'spiritual'], function () {
		    Route::post('/get', 'ApiController@getSpiritual');
		    Route::post('/uploadRecord', 'ApiController@uploadRecord');
		    Route::post('/save', 'ApiController@saveSpiritual');
		});


	});
    Route::group(['prefix' => 'Weather'], function () {
	    Route::post('/today_get', 'ApiController@today_get');
	});
	Route::group(['prefix' => 'ConnectDot'], function(){
		Route::post('/result_get', 'ApiController@result_get');
	});
	Route:: group(['prefix' => 'PdfData'], function(){
		Route::post('/pdfdata_get', 'ApiController@pdfdata_get');
	});
    // Route::post('/uploadTongue', 'UploadController@uploadTongue');
	
});
