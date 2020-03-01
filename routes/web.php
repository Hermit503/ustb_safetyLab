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
    Route::get('getLaboratory','Admin\EquipmentController@getLaboratoryList');
    Route::get('getOneEquipment','Admin\EquipmentController@getOneEquipment');
    Route::post('newEquipment','Admin\EquipmentController@addEquipment');
    Route::put('modifyEquipment','Admin\EquipmentController@updateEquipment');
    Route::delete('deleteEquipment','Admin\EquipmentController@deleteEquipment');
    Route::get('chemicalsList', 'Admin\ChemicalController@getChemicalsList');
    Route::post('newChemical','Admin\ChemicalController@newChemical');
    Route::put('modifyChemical','Admin\ChemicalController@modifyChemical');
    Route::delete('deleteChemical','Admin\ChemicalController@deleteChemical');
    Route::get('userList', 'Admin\UserController@getUserList');
    Route::get('adminList','Admin\AdminerController@getAdmin');
    Route::post('newAdminer','Admin\AdminerController@newAdmin');
    Route::delete('deleteAdminer','Admin\AdminerController@deleteAdmin');
    Route::post('newUser','Admin\UserController@newUser');
    Route::put('modifyUser','Admin\UserController@modifyUser');
    Route::delete('deleteUser','Admin\UserController@deleteUser');
    Route::get('hidden', 'Admin\HiddenController@getHiddensList');
    Route::get('message', 'Admin\MessageController@getMessagesList');
    Route::get('log', 'Admin\LogController@getLogList');
    Route::get('getLogs','Admin\LogController@getLogs');
    Route::get('paper', 'Admin\PaperController@getPaperList');
    Route::post('newPaper','Admin\PaperController@newPaper');
    Route::put('modifyPaper','Admin\PaperController@modifyPaper');
    Route::delete('deletePaper','Admin\PaperController@deletePaper');
    Route::get('score', 'Admin\ScoreController@getScoreList');
    Route::get('role', 'Admin\RoleController@getRolesList');
    Route::get('chooseRole','Admin\RoleController@chooseRole');
    Route::get('findRole','Admin\RoleController@findRole');
    Route::post('newRole','Admin\RoleController@newRole');
    Route::delete('deleteRole','Admin\RoleController@deleteRole');
    Route::get('permission', 'Admin\PermissionController@getPermission');
    Route::get('choosePermission','Admin\PermissionController@choosePermission');
    Route::post('newPermission','Admin\PermissionController@newPermission');
    Route::delete('deletePermission','Admin\PermissionController@deletePermission');
    //上传考试题
    Route::post('uploadExamQuestion','ToolController@uploadExamQuestion');





});


