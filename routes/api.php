<?php

use Illuminate\Http\Request;



Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details');
    Route::post('Device/Create', 'API\UserController@create_device');
    Route::patch('Device/Update/{device}', 'API\UserController@update_device');
    Route::delete('Device/Delete/{device}', 'API\UserController@delete_device');
    Route::get('GetVendor', 'API\UserController@getVendor');
    Route::get('GetPolicy', 'API\UserController@getPolicy');
    Route::get('GetDevice', 'API\UserController@getDevice');
    Route::get('getDevice/{id}', 'API\UserController@getDeviceById');
    Route::post('logout','API\UserController@logoutApi');
});
