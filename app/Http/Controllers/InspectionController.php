<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\Equipments;
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
//        Log::info(Equipments::with('laboratories')->get());
        $inspection = Chemical::where('chemical_id', $request->id)
            ->with('laboratories')
            ->get()->toArray();
        if (count($inspection) == 0) {
            $inspection = Equipments::where("asset_number", $request->id)
                ->with('laboratories')
                ->get()->toArray();
            if (count($inspection) == 0) {
                return response()->json("该二维码不存在", 200);
            } else {
                return response()->json($inspection[0], 200);
            }
        } else {
            return response()->json($inspection[0], 200);
        }
    }

    public function confirmInspection(Request $request)
    {

        $datetime = new \DateTime;
        Log::info($request);
        $log = new InspectionLog();
        $user = User::where("user_id",$request->repair_user)->first();
        if ($request->type == "chemical") {
            $chemical = Chemical::where('chemical_id', $request->id)->first();
            $content = "工号为:".$request->repair_user."(".$user->name.")"."检查了".$chemical->laboratories->building_name.$chemical->laboratories->classroom_num."的".$chemical->laboratories->laboratory_name."药品编号为:".$chemical->chemical_id."的".$chemical->name.",时间:".$datetime->format('Y-m-d H:i:s').",检修结果：".($chemical->type=="chemical"?"危化品":"药品").$chemical->status.($request->detail=="undefined"?'':",备注:".$request->detail);
            Log::info($content);
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
            return response()->json('检修完成',200);
        } elseif ($request->type == "equipment") {
            $equipment = Equipments::where('asset_number', $request->id)->first();
            $content = "工号为:".$request->repair_user."(".$user->name.")"."检查了".$equipment->laboratories->building_name.$equipment->laboratories->classroom_num."的".$equipment->laboratories->laboratory_name."设别编号为:".$equipment->asset_number."的".$equipment->equipment_name.",时间:".$datetime->format('Y-m-d H:i:s').",检修结果：设备".$equipment->status.($request->detail=="undefined"?'':",备注:".$request->detail);
            Log::info($content);
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
            return response()->json('检修完成',200);
        }

    }


    public function getInspectionRecords(Request $request)
    {
        $get = $request->get("role");
        $str = explode('"',$get);
        $arr =array();
        //判断权限
        for ($i=0;$i<sizeof($str);$i++){
            if($i%2==1){
                array_push($arr,$str[$i]);
            }
        }
        Log::info($arr);
        Log::info($request);

        if (in_array("校级管理员",$arr)){
            Log::info("校级管理员");
            return response()->json(InspectionLog::where([])->orderBy("created_at",'desc')->get(),200);
        }else{
            Log::info("院级管理员");
            return response()->json(InspectionLog::where('unit_id',$request->unit_id)->orderBy("created_at",'desc')->get(),200);
        }

    }
}
