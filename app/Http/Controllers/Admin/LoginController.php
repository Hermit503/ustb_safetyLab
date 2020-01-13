<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * 自定义登录
     */

    public function login(Request $request){
        //获取表单输入
        $user_id = $request->input('user_id');
        $password = $request->input('password');
        //获取用户信息
        $user = User::where('user_id',$user_id)->first();
        //数据库中取的密码
        $pass = $user->password;
        $str = Hash::make($pass);
        if (Hash::check($password, $pass)) {
            Auth::login($user);
            return redirect('/admin/index');
        }else{
            return redirect('/admin');
        }
    }
}
