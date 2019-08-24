<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//微信公众号 注册路由
Route::get('/register','RegisterController@getRegister');
Route::post('/register','RegisterController@register')->name('register');
Route::get('/sucess',function(){
    return view('sucess');
});
Route::get('/tool','ToolController@getUserRole');
Route::get('/units','ToolController@getUnitList');
//小程序路由均在api.php
