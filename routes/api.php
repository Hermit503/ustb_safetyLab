<?php

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserCollection;
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

Route::group(['prefix' => '/v1'], function () {
    Route::post('/user/login', 'UserController@wxLogin');
    Route::post('/users', 'UserController@getUser');
    Route::post('/user/logout', 'UserController@wxLogout');
    Route::get('/equipments', 'EquipmentController@getEquipment');
    Route::post('/equipment/add', 'EquipmentController@addEquipment');
    Route::get('/equipment/getold', 'EquipmentController@oldEquipment');
    Route::get('/equipment/getlaboratory', 'EquipmentController@getLaboratory');
    Route::put('/equipment/update', 'EquipmentController@updateEquipment');
    Route::delete('/equipment/delete', 'EquipmentController@deleteEquipment');
});