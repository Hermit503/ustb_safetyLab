<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * 角色管理
     */
    public function getRolesList(){
        $roles = Role::with('user')->orderBy('user_id', 'asc')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.roleList')
                ->with('roles',$roles);
        }

        abort(404);
    }

    public function findRole(Request $request){
        $user_id = $request->user_id;
        $roles = Role::where('user_id',$user_id)->get();
        if(Gate::allows('access-admin',Auth::user())){
            return $roles;
        }
        abort(404);
    }

    /**
     * 获取用户可以再添加的角色
     * @param Request $request
     * @return array
     * @author lj
     * @time 2020-02-03
     */
    public function chooseRole(Request $request){
        $user_id = $request->user_id;
        $role_a = array('校级管理员','院级管理员','实验室管理员','教师');
        $role_b = [];
        $roles = Role::where('user_id',$user_id)->get('role');
        foreach($roles as $role){
            array_push($role_b,$role['role']);
        }
        $result = array_diff($role_a,$role_b);
        return $result;
    }
    /**
     * 添加角色
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-03
     */
    public function newRole(Request $request){
        $user_id = $request->user_id;
        $role = $request->role;

        //先判断这个人有没有这个角色
        $roles = Role::where('user_id',$user_id)
                ->where('role',$role)
                ->get();
        if(count($roles) == 0){
//            echo "没有这个角色";
            $newRole = new Role;
            $newRole->user_id = $user_id;
            $newRole->role = $role;
            $newRole->created_at = date("Y-m-d H:i:s");
            $newRole->updated_at = date("Y-m-d H:i:s");
            if(Gate::allows('access-admin',Auth::user())){
                $newRole->save();
                return "添加成功";
            }
            abort(404);
        }else{
            return "该用户已经有这个角色了";
        }

    }

    /**
     * 删除角色
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-03
     */
    public function deleteRole(Request $request){
        $id = $request->id;
        $role = Role::find($id);
        if(Gate::allows('access-admin',Auth::user())){
            $role->delete();
            return "删除成功";
        }
        abort(404);
    }
}
