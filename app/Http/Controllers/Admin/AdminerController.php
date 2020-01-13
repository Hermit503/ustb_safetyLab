<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminerController extends Controller
{
    /**
     * 获取管理员列表
     */
    public function getAdmin(){
        $temp_admins = User::with('unit')->with('roles')->get();
        $admins = [];
        foreach($temp_admins as $temp_admin){
            for($i = 0;$i<sizeof($temp_admin['roles']);$i++){
                if($temp_admin['roles'][$i]['role'] === '超级管理员'){
                    array_push($admins,$temp_admin);
                }
            }
        }
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.adminList')
                ->with('admins',$admins);
        }

        abort(404);
    }
}
