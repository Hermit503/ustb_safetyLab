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

    /**
     * 提交电子巡检
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function confirmInspection(Request $request)
    {
        $datetime = new \DateTime;

        $user = User::where("user_id", $request->repair_user)->first();
        if ($request->type == "chemical") {
            $chemical = Chemical::where('chemical_id', $request->id)->first();
            $name = $chemical->name;
            if ($request->status == '存在问题') {
                if ($request->up == 1) {
                    //上报问题
                    $this->createHidden($request, $name);
                    //修改药品状态为存在问题
                    $chemical->status = "存在问题";
                    $chemical->fix_time = $datetime->format('Y-m-d H:i:s');
                    $chemical->save();
                    $this->createInspectionLog($request, $chemical, $type = 'chemical');
                    Log::info($name."将".$chemical->name."(".$chemical->chemical_id.")"."：状态：由正常改为存在问题，并自动提交为问题");
                } else if ($request->up == 0) {
                    //检修过后还是处于存在问题状态
                    $this->noHandled($request);
                    $this->createInspectionLog($request, $chemical, $type = 'chemical');
                    Log::info($name."将".$chemical->name."(".$chemical->chemical_id.")"."：状态：未处理成功,存在问题");
                }
            } else if ($request->status == '正常') {
                if ($request->up == 1) {
                    //修改问题状态
                    $hidden = Hidden::where('number', $request->inspection_id)->first();
                    $hidden->isSolve = 1;
                    $hidden->number = null;
                    $hidden->save();
                    $chemical->status = "正常";
                    $chemical->fix_time = $datetime->format('Y-m-d H:i:s');
                    $this->createHiddenRecode($request, $hidden['id']);
                    $this->createInspectionLog($request, $chemical, $type = 'chemical');
                    Log::info($name."将".$chemical->name."(".$chemical->chemical_id.")"."：状态：由存在问题改为正常");
                } else if ($request->up == 0) {
                    $this->createInspectionLog($request, $chemical, $type = 'chemical');
                    Log::info($name."巡检".$chemical->name."(".$chemical->chemical_id.")"."：状态：正常");
                }

            }
        } else if ($request->type == "equipment") {
            $equipment = Equipments::where('asset_number', $request->id)->first();
            $name = $equipment->equipment_name;
            $laboratory = $equipment->laboratories;
            if ($request->status == '维修') {
                if ($request->up == 1) {
                    //上报问题
                    $this->createHidden($request, $name);
                    //修改设备状态为维修
                    $equipment->status = "维修";
                    $equipment->fix_time = $datetime->format('Y-m-d H:i:s');
                    $equipment->save();
                    $this->createInspectionLog($request, $equipment, $type = 'equipment');
                    Log::info($name."将". $equipment->equipment_name."(".$equipment->asset_number.")"."：状态：由正常改为维修状态，并自动提交为问题");
                } else if ($request->up == 0) {
                    //检修过后还是处于维修状态
                    $this->noHandled($request);
                    $this->createInspectionLog($request, $equipment, $type = 'equipment');
                    Log::info($name."将".$equipment->equipment_name."(".$equipment->asset_number.")"."：状态：处理状态：未处理成功");
                }
            } else if ($request->status == '正常') {
                if ($request->up == 1) {
                    $hidden = Hidden::where('number', $request->inspection_id)->first();
                    $hidden->isSolve = 1;
                    $hidden->number = null;
                    $hidden->save();
                    $equipment->status = "正常";
                    $equipment->fix_time = $datetime->format('Y-m-d H:i:s');
                    $equipment->save();
                    $this->createHiddenRecode($request, $hidden['id']);
                    $this->createInspectionLog($request, $equipment, $type = 'equipment');
                    Log::info($name."将". $equipment->equipment_name."(".$equipment->asset_number.")"."：状态：由维修状态转为正常");
                } else if ($request->up == 0) {
                    $this->createInspectionLog($request, $equipment, $type = 'equipment');
                    Log::info($name."将". $equipment->equipment_name."(".$equipment->asset_number.")"."：状态：正常");
                }
            }
        }
        return response()->json('检修完成', 200);
    }

    /**
     * 创建隐患问题
     * @param $request
     * @param $name
     */
    public function createHidden($request, $name)
    {
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

    /**
     * 若没处理也需要添加巡检记录
     * @param $request
     */
    public function noHandled($request)
    {
        //查询这个问题是否不存在
        $hidden_id = Hidden::where('number', $request->inspection_id)
            ->where('position', $request->position)
            ->first();
        $name = (new ToolController)->getUsername($request->repair_user);
        $hiddenLog = new HiddensLog();
        $hiddenLog->hidden_id = $hidden_id['id'];
        $hiddenLog->user_name = $name;
        $hiddenLog->reason = "巡检但未处理成功！";
        $hiddenLog->isSolve = '0';
        $hiddenLog->image = null;
        $hiddenLog->save();
    }

    /**
     * 创建问题隐患处理记录
     * @param $request
     * @param $hidden_id
     */
    public function createHiddenRecode($request, $hidden_id)
    {
        $name = (new ToolController)->getUsername($request->repair_user);
        $hiddenLog = new HiddensLog();
        $hiddenLog->hidden_id = $hidden_id;
        $hiddenLog->user_name = $name;
        $hiddenLog->reason = $request->detail;
        $hiddenLog->image = env('APP_URL') . $request->image;
        $hiddenLog->isSolve = '1';
        $hiddenLog->save();
    }

    /**
     * 创建巡检记录
     * @param $request
     * @param $data
     * @param $type
     * @throws \Exception
     */
    public function createInspectionLog($request, $data, $type)
    {
        $name = (new ToolController)->getUsername($request->repair_user);
        $datetime = new \DateTime;
        $inspectionLog = new InspectionLog();
        if ($type = 'chemical') {
            $content = "工号为:" . $request->repair_user . "(" . $name . ")" . "检查了" . $data->laboratories->building_name . $data->laboratories->classroom_num
                . "的" . $data->laboratories->laboratory_name . "药品编号为:" . $data->chemical_id . "的" . $data->name . ",时间:" . $datetime->format('Y-m-d H:i:s')
                . ",检修结果：" . ($data->type == "chemical" ? "危化品" : "药品") . $data->status . ($request->detail == "undefined" ? '' : ",备注:" . $request->detail);
        } else if ($type = 'equipment') {
            $content = "工号为:" . $request->repair_user . "(" . $name . ")" . "检查了" . $data->laboratories->building_name . $data->laboratories->classroom_num
                . "的" . $data->laboratories->laboratory_name . "设别编号为:" . $data->asset_number . "的" . $data->equipment_name . ",时间:"
                . $datetime->format('Y-m-d H:i:s') . ",检修结果：设备" . $data->status . ($request->detail == "undefined" ? '' : ",备注:" . $request->detail);
        }
        $inspectionLog->unit_id = $data->unit_id;
        $inspectionLog->log = $content;
        $inspectionLog->save();
    }
}
