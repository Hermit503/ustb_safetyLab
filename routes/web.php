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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
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

//后台路由

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.login');
    });
    Route::get('index', function () {
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.index');
        }
        abort(404);
    });
    Route::get('welcome','AdminController@getWelcome');
    Route::post('login','AdminController@login');
    Route::get('logout',function(){
        Auth::logout();
        return redirect('/admin');
    });
    Route::get('unitList','AdminController@getUnitList');
    Route::get('larboriesList', 'AdminController@getLarboriesList');
    Route::get('equipmentsList', 'AdminController@getEquipmentsList');
    Route::get('chemicalsList', 'AdminController@getChemicalsList');
    Route::get('userList', 'AdminController@getUserList');
    Route::get('adminList','AdminController@getAdmin');
    Route::get('hidden', 'AdminController@getHiddensList');
    Route::get('message', 'AdminController@getMessagesList');
    Route::get('log', 'AdminController@getLogList');
    Route::get('paper', 'AdminController@getPaperList');
    Route::get('score', 'AdminController@getScoreList');
    Route::get('role', 'AdminController@getRolesList');
    Route::get('permission', 'AdminController@getPermission');
    
});
