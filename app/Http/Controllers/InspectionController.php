<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\Equipments;
use App\Hidden;
use App\HiddensLog;
use App\InspectionLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    /**
     * 获取单个设备/药品 判断是否存在 存在即返回
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @author hzj
     * @date 2019-10-08
     */
    public function getInspections(Request $request)
    {
        $inspection = Chemical::where('chemical_id', $request->id)->where('unit_id', $request->unit_id)
            ->with('laboratories')
            ->get()->toArray();
        if (count($inspection) == 0) {
            $inspection = Equipments::where("asset_number", $request->id)->where('unit_id', $request->unit_id)
                ->with('laboratories')
                ->get()->toArray();
            if (count($inspection) == 0) {
                return response()->json([
                    'canInspection' => 'no',
                    'msg' => "该二维码不存在或无权巡检"
                ], 200);
            } else {
                return response()->json($inspection[0], 200);
            }
        } else {
            return response()->json($inspection[0], 200);
        }
    }

    /**
     * 保存电子巡检记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function confirmInspection(Request $request)
    {

        $datetime = new \DateTime;
        $log = new InspectionLog();
        $user = User::where("user_id", $request->repair_user)->first();
        if ($request->type == "chemical") {
            $chemical = Chemical::where('chemical_id', $request->id)->first();
            $name = $chemical->name;
            $content = "工号为:" . $request->repair_user . "(" . $user->name . ")" . "检查了" . $chemical->laboratories->building_name . $chemical->laboratories->classroom_num . "的" . $chemical->laboratories->laboratory_name . "药品编号为:" . $chemical->chemical_id . "的" . $chemical->name . ",时间:" . $datetime->format('Y-m-d H:i:s') . ",检修结果：" . ($chemical->type == "chemical" ? "危化品" : "药品") . $chemical->status . ($request->detail == "undefined" ? '' : ",备注:" . $request->detail);
            //Log::info($content);
            if ($request->status == "正常") {
                $chemical->status = "正常";
                $chemical->fix_time = $datetime->format('Y-m-d H:i:s');
                $log->unit_id = $chemical->unit_id;
                $log->log = $content;
                $log->save();
                $chemical->save();
            } else {
                $chemical->status = "存在问题";
                $chemical->fix_time = $datetime->format('Y-m-d H:i:s');
                $log->unit_id = $chemical->unit_id;
                $log->log = $content;
                $log->save();
                $chemical->save();
            }
            $this->fixHiddenStatus($request,$name);

            return response()->json('检修完成', 200);
        } elseif ($request->type == "equipment") {
            $equipment = Equipments::where('asset_number', $request->id)->first();
            $name = $equipment->equipment_name;
            $content = "工号为:" . $request->repair_user . "(" . $user->name . ")" . "检查了" . $equipment->laboratories->building_name . $equipment->laboratories->classroom_num . "的" . $equipment->laboratories->laboratory_name . "设别编号为:" . $equipment->asset_number . "的" . $equipment->equipment_name . ",时间:" . $datetime->format('Y-m-d H:i:s') . ",检修结果：设备" . $equipment->status . ($request->detail == "undefined" ? '' : ",备注:" . $request->detail);
            //Log::info($content);
            if ($request->status == "正常") {
                $equipment->status = "正常";
                $equipment->fix_time = $datetime->format('Y-m-d H:i:s');
                $log->unit_id = $equipment->unit_id;
                $log->log = $content;
                $log->save();
                $equipment->save();
            } else {
                $equipment->status = "维修";
                $equipment->fix_time = $datetime->format('Y-m-d H:i:s');
                $log->unit_id = $equipment->unit_id;
                $log->log = $content;
                $log->save();
                $equipment->save();
            }
            $this->fixHiddenStatus($request,$name);
            return response()->json('检修完成', 200);
        }

    }

    /**
     * 修改hidden状态
     */
    public function fixHiddenStatus($request,$name)
    {
        if ($request->up == 1) {
            $hidden = new Hidden();
            $hidden->user_id = $request->repair_user;
            $hidden->type = "issue";
            $hidden->position = $request->position;
            $hidden->title = $request->position . $name;
            $hidden->detail = $request->detail;
            $hidden->image = env('APP_URL') . $request->image;
            $hidden->unit = $request->unit_id;
            $hidden->occurrence_time = now();
            $hidden->number = $request->inspection_id;
            $hidden->save();
        }
        if ($request->up == 0) {
            $hidden_id = Hidden::where('number', $request->inspection_id)
                ->where('position', $request->position)
                ->get('id');
            //判断改变状态
            $hidden = Hidden::where('number', $request->inspection_id)->first();
            $hidden->isSolve = 1;
            $hidden->number = null;
            $hidden->save();
            //添加log到数据库
            $name = (new ToolController)->getUsername($request->repair_user);
            $hiddenLog = new HiddensLog();
            $hiddenLog->hidden_id = $hidden_id[0]['id'];
            $hiddenLog->user_name = $name;
            $hiddenLog->reason = $request->detail;
            if ($request->image != null) {
                $hiddenLog->image = env('APP_URL') . $request->image;
                $hiddenLog->isSolve = '1';
            } else {
                $hiddenLog->image = null;
            }
            $hiddenLog->save();
        }
    }

    /**
     * 获取电子巡检记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInspectionRecords(Request $request)
    {
//        Log::info($request);
        $get = $request->get("role");
        $str = explode('"', $get);
        $arr = array();
        //判断权限
        for ($i = 0; $i < sizeof($str); $i++) {
            if ($i % 2 == 1) {
                array_push($arr, $str[$i]);
            }
        }
        //Log::info($arr);
        //Log::info($request);

        if (in_array("校级管理员", $arr)) {
            //Log::info("校级管理员");
            return response()->json(InspectionLog::where([])->orderBy("created_at", 'desc')->get(), 200);
        } else {
            //Log::info("院级管理员");
            return response()->json(InspectionLog::where('unit_id', $request->unit_id)->orderBy("created_at", 'desc')->get(), 200);
        }

    }
}
