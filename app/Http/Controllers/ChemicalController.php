<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\ChemicalsNotice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChemicalController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function getChemical(Request $request)
    {
        if ($request->unit_id !== "null") {
            $chemicalList = Chemical::where('unit_id', $request->unit_id)
                ->with('unit')
                ->with('user')
                ->get();
            return response()->json([
                'chemicalList' => $chemicalList
            ], 200);
        } elseif ($request->unit_id == "null") {
            $chemicalList = Chemical::where([])
                ->with('unit')
                ->with('user')
                ->get();
            return response()->json([
                'chemicalList' => $chemicalList
            ], 200);
        }
    }

    /**  出入库的逻辑  判断危化品、药品类型 //TODO：实现五双政策
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inout(Request $request)
    {
//        Log::info($request);
        if ($request->chemicalType == "medcine") {
            $chemical = Chemical::where('chemical_id', $request->chemicalId)->first();
            if (($chemical->stock + $request->stock) < 0) {
                return response()->json('库存不够了哦  无法取出哦！', 200);
            } else {
                $chemical->stock += $request->stock;
                $chemical->save();
                if ($request->stock > 0) {
                    return response()->json('入库完成！', 200);
                } elseif ($request->stock < 0) {
                    return response()->json('出库完成！', 200);
                }
            }
        } elseif ($request->chemicalType == "chemical") {
            $chemical = Chemical::where('chemical_id', $request->chemicalId)->first();
            if (($chemical->stock + $request->stock) < 0) {
                return response()->json('库存不够了哦  无法取出哦！', 200);
            } else {
                $chemicalNotice = new ChemicalsNotice();
                $chemicalNotice->unit_id = $request->unitId;
                $chemicalNotice->user_id_1 = $request->userId;
                $chemicalNotice->user_name_1 = $request->userName;
                $chemicalNotice->user_id_2 = $request->monitorId;
                $chemicalNotice->user_name_2 = User::where("user_id", $request->userId)->get("name")[0]["name"];
                $chemicalNotice->chemical_id = $request->chemicalId;
                $chemicalNotice->chemical_name = Chemical::where("chemical_id", $request->chemicalId)->get("name")[0]["name"];
                if ($request->stock > 0) {
                    $chemicalNotice->type = "入库";
                } elseif ($request->stock < 0) {
                    $chemicalNotice->type = "出库";
                }
                $chemicalNotice->stock = abs($request->stock);//量
                $chemicalNotice->unit_type = $request->unitType;//单位
                $chemicalNotice->remarks = $request->remarks;
                $chemicalNotice->save();
                return response()->json('需要完成双人入库操作', 200);
            }
        }
    }

    /**
     * 确认出入库
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author hzj
     * @date 2019-8-26 15：12
     */
    public function inoutConfirm(Request $request)
    {
        $chemicalNotice = ChemicalsNotice::where("id", $request->id)->first();
        $chemical = Chemical::where("chemical_id", $request->chemicalId)->first();

        if ($request->status == "同意") {
            //出入库操作
            if ($request->type == "入库") {
                //出库入确认
                $chemicalNotice->isConfirm_2 = "1";
                $chemicalNotice->receive = "1"; //已收到
                $chemicalNotice->save();
                $chemical->stock += abs($request->stock);
                $chemical->save();
                return response()->json("入库成功", 200);
            } elseif ($request->type == "出库") {
                //出库入确认
                $chemicalNotice->isConfirm_2 = "1";
                $chemicalNotice->receive = "1"; //已收到
                $chemicalNotice->save();
                $chemical->stock += -abs($request->stock);
                $chemical->save();
                return response()->json("出库成功", 200);
            }
        } else {
            //给谁发 发什么
            $chemicalNotice->receive = "1"; //已收到未确认
            $chemicalNotice->save();
            return response()->json("驳回申请完成", 200);
        }
    }

    public function know(Request $request)
    {
        $id = $request->id;
        $chemicalsNotice = ChemicalsNotice::where('id', $id)->first();
        $chemicalsNotice->receive = "2";
        $chemicalsNotice->save();
        return response('yes', 200);
    }


}
