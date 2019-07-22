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
    //获取用户信息
    Route::get('/users', 'UserController@getUser');
    //用户注销
    Route::post('/user/logout', 'UserController@wxLogout');
    //获取单个用户
    Route::get('/users/{id}','UserController@getOneUser');
    //修改用户信息
    Route::put('/users/{id}','UserController@updateUser');
    //删除用户
    Route::delete('/users/{id}','UserController@deleteUser');
    //判断用户权限
    Route::get('/users/permissions/{id}','UserController@hasPermission');
    //用户搜索
    Route::get('/searchUser','UserController@searchUser');

    //获取设备列表
    Route::get('/equipments', 'EquipmentController@getEquipment');
    //新增设备
    Route::post('/equipment/add', 'EquipmentController@addEquipment');
    //获取单个设备信息
    Route::get('/equipment/{id}', 'EquipmentController@getOneEquipment');
    //通过实验室id获取实验室名称TODO:可优化
    Route::get('/equipment/getlaboratory/{id}', 'EquipmentController@getLaboratory');
    //修改设备信息
    Route::put('/equipment/{id}', 'EquipmentController@updateEquipment');
    //删除设备信息
    Route::delete('/equipment/{id}', 'EquipmentController@deleteEquipment');
    //设备搜索
    Route::get('/searchEquipment','EquipmentController@searchEquipment');

    //获取实验室地址列表
    Route::get('/getlaboratory/List/{unit_id}', 'EquipmentController@getLaboratoryList');

    //隐患上传
    Route::post('/hiddens','HiddenController@addHidden');
    //获取隐患列表
    Route::get('/hiddens','HiddenController@getHidden');
    //保存隐患照片
    Route::post('/hiddens/upload','HiddenController@saveHiddenImage');

    //邮件发送
    Route::get('/mail','MailController@sendEmail');

    //短信发送
    //获取隐患详情
    Route::put('/hiddens/{id}','HiddenController@getHiddenDetail');
});