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
    public function getUserRole(Request $request)
    {
        $unit_id = $request->unit_id;
        $allUser = [];
        $user = User::where('unit_id', '=', $unit_id)->get(['id','name']);
        foreach ($user as $item) {
            foreach ($item->roles as $getUser) {
                if ($getUser->role == "校级管理员" || $getUser->role == "院级管理员") {
                    $allUser[$item->id] = $item->name;
                }
            }
        }
        return response()->json($allUser);
    }
}
