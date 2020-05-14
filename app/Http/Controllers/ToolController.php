<?php

namespace App\Http\Controllers;

use App\Exam;
use App\Imports\ExcelImport;
use App\Role;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Faker\Generator as Faker;

class ToolController extends Controller
{
    /*
     * 注册时判断用户角色
     * @author lj
     * @time 2019-06-19
     * $user_id 用户工号
     *
     * */
    public function getRole($user_id)
    {
        $result = Role::where('user_id', $user_id)->get(['role']);
        foreach ($result as $value) {
            if ($value->role != "教师") {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取单位列表
     * @param null
     * @return json单位列表
     *
     * @author lj
     * @time 2019-06-21
     */
    public function getUnitList()
    {
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
            if ($this->getRole($item->user_id)) {
                $allUser[$item->id] = $item->name;
            }
        }
        return response()->json($allUser);
    }

    public function getUserName($user_id)
    {
        $name = User::where('user_id', $user_id)->get('name');
        return $name[0]->name;
    }

    public function uploadExamQuestion(Request $request)
    {
        $file = $request->file('file');
        $arrays = Excel::toArray(new ExcelImport(), $file);
        for ($i = 0; $i < count($arrays[0]); $i++) {
            $exam = new Exam();
            $exam->type = $arrays[0][$i][0];
            $exam->question = $arrays[0][$i][1];
            $exam->option1 = $arrays[0][$i][2];
            $exam->option2 = $arrays[0][$i][3];
            $exam->option3 = $arrays[0][$i][4];
            $exam->option4 = $arrays[0][$i][5];
            $exam->answer = $arrays[0][$i][6];
            $exam->save();
        }
        return response()->json([
            "msg" => '上传成功'
        ], 200);
    }

    /**
     * 修改测试用户数据
     * @param Request $request
     * @param Faker $faker
     */
    public function fixName(Request $request, Faker $faker)
    {
        for ($i = 0; $i < 200; $i++) {
            $user = User::where('user_id', $i)->first();
            if ($user != null) {
                //$user->name=$faker->name;
                //$user->phone_number=$faker->phoneNumber;
                //$user->email=$faker->email;
                $user->exam_result = 85;
                $user->save();
            } else {
                continue;
            }
        }
    }

}
