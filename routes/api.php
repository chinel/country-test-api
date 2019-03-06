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

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::get('activities', 'ActivityController@index');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', 'ApiController@logout');

    Route::get('user', 'ApiController@getAuthUser');

    Route::get('countries', 'CountryController@index');
    Route::get('country/{id}', 'CountryController@show');
    Route::post('countries', 'CountryController@store');
    Route::put('country/{id}', 'CountryController@update');
    Route::delete('country/{id}', 'CountryController@destroy');
});
