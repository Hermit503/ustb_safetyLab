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
    //用户微信登录
    Route::any('/user/login', 'UserController@wxLogin');
    Route::post('/users', 'UserController@getUser');
    //用户注销
    Route::post('/user/logout', 'UserController@wxLogout');
    //获取单个用户
    Route::get('/user/{id}','UserController@getOneUser');
    //修改用户信息
    Route::put('/user/{id}','UserController@updateUser');

    Route::get('/equipments', 'EquipmentController@getEquipment');
    Route::post('/equipment/add', 'EquipmentController@addEquipment');
    Route::get('/equipment/getold', 'EquipmentController@oldEquipment');
    Route::get('/equipment/getlaboratory', 'EquipmentController@getLaboratory');
    Route::put('/equipment/update', 'EquipmentController@updateEquipment');
    Route::delete('/equipment/delete', 'EquipmentController@deleteEquipment');
});