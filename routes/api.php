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
    Route::any('/user/login', 'UserController@wxLogin');
    Route::any('/user/logout', 'UserController@wxLogout');
    Route::get('/users',function (Request $request){
        if($request->unit_id=='1'){
        return new UserCollection(User::all());
        }
    });
});