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
    Route::get('welcome','Admin\WelcomeController@getWelcome');
    Route::post('login','Admin\LoginController@login');
    Route::get('logout',function(){
        Auth::logout();
        return redirect('/admin');
    });
    Route::get('unitList','Admin\UnitController@getUnitList');
    Route::post('newUnit','Admin\UnitController@newUnit');
    Route::put('modifyUnit','Admin\UnitController@modifyUnit');
    Route::get('larboriesList', 'Admin\LaboratoryController@getLaboratoriesList');
    Route::post('newLarborary','Admin\LaboratoryController@newLaboratory');
    Route::put('modifyLaborary','Admin\LaboratoryController@modifyLaboratory');
    Route::get('allUsers','UserController@getAllUsers');
    Route::get('equipmentsList', 'Admin\EquipmentController@getEquipmentsList');
    Route::get('chemicalsList', 'Admin\ChemicalController@getChemicalsList');
    Route::get('userList', 'Admin\UserController@getUserList');
    Route::get('adminList','Admin\AdminerController@getAdmin');
    Route::post('newAdminer','Admin\AdminerController@newAdmin');
    Route::delete('deleteAdminer','Admin\AdminerController@deleteAdmin');
    Route::get('hidden', 'Admin\HiddenController@getHiddensList');
    Route::get('message', 'Admin\MessageController@getMessagesList');
    Route::get('log', 'Admin\LogController@getLogList');
    Route::get('paper', 'Admin\PaperController@getPaperList');
    Route::get('score', 'Admin\ScoreController@getScoreList');
    Route::get('role', 'Admin\RoleController@getRolesList');
    Route::get('permission', 'Admin\PermissionController@getPermission');

});
