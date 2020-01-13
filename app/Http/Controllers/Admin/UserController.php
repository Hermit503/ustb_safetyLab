<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * 获取用户列表
     */
    public function getUserList(){
        // $users = User::paginate(10)->with('unit')->get();
        $users = User::with('unit')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.userList')
                ->with('users',$users);
        }

        abort(404);
    }
}
