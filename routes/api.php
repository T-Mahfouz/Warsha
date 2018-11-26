<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function(){

    ############ Common Auth Routes
    Route::post('me', 'Api\CommonAuthController@me');
    Route::post('refresh', 'Api\CommonAuthController@refresh');
    Route::post('editprofile', 'Api\CommonAuthController@editProfile');
    Route::get('logout','Api\CommonAuthController@logout');
    Route::get('orderinfo','Api\CommonAuthController@orderInfo');
    Route::get('offers','Api\CommonAuthController@offers');
    Route::get('userinfo','Api\CommonAuthController@userInfo');

    ############ User Routes
    Route::post('postrequest','Api\UserController@postRequest');
    Route::post('acceptoffer','Api\UserController@acceptOffer');
    Route::post('rateworkshop','Api\UserController@rateWorkshop');
    Route::get('usertimeline','Api\UserController@userTimeline');


    ############ Workshops Routes
    Route::post('postoffer','Api\WorkshopController@postOffer');
    Route::get('acceptedoffers','Api\WorkshopController@acceptedOffers');
    Route::get('alloffers','Api\WorkshopController@allOffers');
    Route::get('workshoptimeline','Api\WorkshopController@workshopTimeline');


});


Route::post('login','Api\PublicController@login')->name('login');
Route::post('signup','Api\PublicController@signup');
Route::get('aboutus','Api\PublicController@aboutus');
Route::get('services','Api\PublicController@services');
Route::post('sendcontactus','Api\PublicController@sendContactUs');
