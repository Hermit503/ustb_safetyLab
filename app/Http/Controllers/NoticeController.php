<?php

namespace App\Http\Controllers;

use App\Role;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function getList(Request $request){
        $unit_id = $request->unit_id;
        $id = $request->user_id;
        $result = [];
        if (strpos($request->roles, '校级管理员') !== false) {
            //校级管理员向所有unit发通知
            $units = Unit::all('unit_name');
            for($j = 0;$j<count($units);$j++){
                $result[$j] = $units[$j]['unit_name'];
            }
            return $result;
        }elseif (strpos($request->roles, '院级管理员') !== false) {
            //院级管理员向实验室管理员发通知
            //所属单位一致，身份为实验室管理员

            //同一单位
            $laborary_users = User::where('unit_id',$unit_id)->get();

            $i = 0;
            for($j = 0 ; $j < count($laborary_users) ; $j++){
                if(Role::where('user_id',$laborary_users[$j]->user_id)->where('role','实验室管理员')->get('user_id')->isNotEmpty()){
                    $result[$i] = $laborary_users[$j]->name;
                    $i++;
                }
            }

            return $result;
        }elseif (strpos($request->roles, '实验室管理员') !== false) {
            //实验室管理员向教师发通知
            //所属单位一致，roles为教师，parent_id为自己的id
            $teachers = User::where('unit_id',$unit_id)->where('parent_id',$id)->get();
            $i = 0;
            for ($j = 0 ; $j < count($teachers) ; $j++){
                if(Role::where('user_id',$teachers[$j]->user_id)->where('role','教师')->get('user_id')->isNotEmpty()){
                    $result[$i] = $teachers[$j]->name;
                    $i++;
                }
            }
            return $result;
        }
    }

    /**
     * 获取序号
     */
    public function getIndex(Request $request){
        $unit_id = $request->unit_id;
        $id = $request->user_id;
        $result = [];
        if (strpos($request->roles, '校级管理员') !== false) {
            //校级管理员向所有unit发通知
            $units = Unit::all();
            for($j = 0;$j<count($units);$j++){
                $result[$j] = $units[$j]['id'];
            }
            return $result;
        }elseif (strpos($request->roles, '院级管理员') !== false) {
            //院级管理员向实验室管理员发通知
            //所属单位一致，身份为实验室管理员

            //同一单位
            $laborary_users = User::where('unit_id',$unit_id)->get();

            $i = 0;
            for($j = 0 ; $j < count($laborary_users) ; $j++){
                if(Role::where('user_id',$laborary_users[$j]->user_id)->where('role','实验室管理员')->get('user_id')->isNotEmpty()){
                    $result[$i] = $laborary_users[$j]->user_id;
                    $i++;
                }
            }

            return $result;
        }elseif (strpos($request->roles, '实验室管理员') !== false) {
            //实验室管理员向教师发通知
            //所属单位一致，roles为教师，parent_id为自己的id
            $teachers = User::where('unit_id',$unit_id)->where('parent_id',$id)->get();
            $i = 0;
            for ($j = 0 ; $j < count($teachers) ; $j++){
                if(Role::where('user_id',$teachers[$j]->user_id)->where('role','教师')->get('user_id')->isNotEmpty()){
                    $result[$i] = $teachers[$j]->user_id;
                    $i++;
                }
            }
            return $result;
        }
    }

    public function saveImage(Request $request)
    {
        Log::info($request);
        $path = $request->file('file')->store('public/noticeImage');
        return Storage::url($path);
    }

    public function saveFile(Request $request)
    {
//        Log::info($request);
        $path = $request->file('file')->store('public/noticeFile');
        return Storage::url($path);
//        return $request->file;
    }
}
