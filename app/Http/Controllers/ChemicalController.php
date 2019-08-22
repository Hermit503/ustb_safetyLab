<?php

namespace App\Http\Controllers;

use App\Chemical;
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
        $chemocalList = Chemical::where('unit_id', $request->unit_id)
            ->with('unit')
            ->with('user')
            ->get();
        return response()->json([
            'chemicalList' => $chemocalList
        ], 200);
    }

    /**  出入库的逻辑  判断危化品、药品类型 //TODO：实现五双政策
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inout(Request $request)
    {
        Log::info($request);
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
            return response()->json('需要完成双人入库操作', 200);
        }
    }
}
