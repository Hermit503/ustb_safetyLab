<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /*
     * 注册选择单位 ajax请求获取该单位下管理员
     * @author hzj
     * @time 2019-06-17
     * @
     *
     * */
    public function getUserRole()
    {
        $allUser = array();
        $user = User::where('unit_id', '=', '1')->get();
        foreach ($user as $item) {
            foreach ($item->roles as $getUser) {
                if ($getUser->role == "校级管理员" || $getUser->role == "院级管理员") {
                    array_push($allUser,$item->name);
                }
            }
        }
        return response()->json(array_unique($allUser));
    }
}
