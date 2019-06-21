<?php

namespace App\Http\Controllers;

use App\Role;
use App\Unit;
use App\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /*
     * 注册时判断用户角色
     * @author lj
     * @time 2019-06-19
     * $user_id 用户工号
     *
     * */
    public function getRole($user_id){
        $result = Role::where('user_id',$user_id)->get(['role']);
        foreach ($result as $value){
            if($value->role != "教师"){
                return true;
            }
        }
        return false;
    }

    /**
     * 获取单位列表
     * @author lj
     * @time 2019-06-21
     * @param null
     * @return json单位列表
     *
     */
    public function getUnitList(){
        $units = Unit::all();
        $allUnit = [];
        foreach ($units as $item) {
            $allUnit[$item->id] = $item->unit_name;
        }
        return response()->json($allUnit);
    }

    /*
     * 注册选择单位 ajax请求获取该单位下管理员
     * @author hzj
     * @time 2019-06-17
     * 修复bug
     * @author lj
     *@time 2019-06-19
     * */
    public function getUserRole(Request $request)
    {
        $unit_id = $request->unit_id;
        $allUser = [];
        $user = User::where('unit_id', '=', $unit_id)->get();
        foreach ($user as $item) {
            if($this->getRole($item->user_id)){
                $allUser[$item->id] = $item->name;
            }
        }
        return response()->json($allUser);
    }

}
