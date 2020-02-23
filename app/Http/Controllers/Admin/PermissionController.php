<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * 权限管理
     */
    public function getPermission(){
        $permissions = Permission::with('user')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.permissionList')
                ->with('permissions',$permissions);

        }

        abort(404);
    }

    /**
     * 判断还能添加什么权限
     * @param Request $request
     * @return array
     * @author lj
     * @time 2020-02-06
     */
    public function choosePermission(Request $request){
        $user_id = $request->user_id;
        $permission_a = array('createUser','updateUser','deleteUser','createEquipment','updateEquipment','deleteEquipment');
        $permission_b = [];
        $permissions = Permission::where('user_id',$user_id)->get('permission');

        if(count($permissions) == count($permission_a)){
            return $permission_b;
        }else{
            foreach($permissions as $permission){
                array_push($permission_b,$permission['permission']);
            }
            $result = array_diff($permission_a,$permission_b);
            return $result;
        }
    }

    /**
     * 添加新的权限
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-06
     */
    public function newPermission(Request $request){
        $user_id = $request->user_id;
        $permission = $request->permission;

        //先判断这个人有没有这个权限
        $permissions = Permission::where('user_id',$user_id)
                        ->where('permission',$permission)
                        ->get();
        if(count($permissions) == 0){
            $newPermission = new Permission;
            $newPermission->user_id = $user_id;
            $newPermission->permission = $permission;
            $newPermission->created_at = date("Y-m-d H:i:s");
            $newPermission->updated_at = date("Y-m-d H:i:s");
            if(Gate::allows('access-admin',Auth::user())){
                $newPermission->save();
                Log::info("管理员".Auth::user()['user_id']."为".$user_id."添加了".$permission."权限");
                return "添加成功";
            }
            abort(404);
        }else{
            return "这个用户已经有这个权限了";
        }
    }

    /**
     * 删除权限
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-06
     */
    public function deletePermission(Request $request){
        $id = $request->id;
        $permission_detail = Permission::where('id',$id)->get();
        $permission = Permission::find($id);
        if(Gate::allows('access-admin',Auth::user())){
            $permission->delete();
            Log::alert("管理员".Auth::user()['user_id']."删除了".$permission_detail[0]['user_id']."的".$permission_detail[0]['permission']."权限");
            return "删除成功";
        }
        abort(404);
    }
}
