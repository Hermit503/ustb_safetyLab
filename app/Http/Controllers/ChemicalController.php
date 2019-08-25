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
        $chemicalList = Chemical::where('unit_id', $request->unit_id)
            ->with('unit')
            ->with('user')
            ->get();
        return response()->json([
            'chemicalList' => $chemicalList
        ], 200);
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
            } else{
            $chemicalNotice = new ChemicalsNotice();
            $chemicalNotice->unit_id = $request->unitId;
            $chemicalNotice->user_id_1 = $request->userId;
            $chemicalNotice->user_name_1 = $request->userName;
            $chemicalNotice->user_id_2 = $request->monitorId;
            $chemicalNotice->user_name_2 =User::where("user_id",$request->userId)->get("name")[0]["name"];
            $chemicalNotice->chemical_id = $request->chemicalId;
            $chemicalNotice->chemical_name = Chemical::where("chemical_id",$request->chemicalId)->get("name")[0]["name"];
            if ($request->stock>0){
                $chemicalNotice->type = "入库";
            }elseif($request->stock<0){
                $chemicalNotice->type = "出库";
            }
            $chemicalNotice->stock = abs($request->stock);
            $chemicalNotice->remarks = $request->remarks;
            $chemicalNotice->save();
            return response()->json('需要完成双人入库操作', 200);
            }
        }
    }
}
