<?php

namespace App\Http\Controllers\Admin;

use App\Role;
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

    /**
     * 新增管理员
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-20
     */
    public function newAdmin(Request $request){
        $user_id = $request->new_admin_id;
        $role = new Role;
        $role->user_id = $user_id;
        $role->role = '超级管理员';
        $role->created_at = date("Y-m-d H:i:s");
        $role->updated_at = date("Y-m-d H:i:s");
        if(Gate::allows('access-admin',Auth::user())){
            $role->save();
            return "添加成功";
        }

        abort(404);
    }

    /**
     * 删除管理员
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-20
     */
    public function deleteAdmin(Request $request){
        $user_id = $request->user_id;
        if(Gate::allows('access-admin',Auth::user())){
            Role::where('user_id', $user_id)->where('role','超级管理员')->delete();
            return "删除成功";
        }
        abort(404);
    }
}
