<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\Equipments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function getInspections(Request $request)
    {

//        Log::info(Equipments::with('laboratories')->get());
        $inspection = Chemical::where('chemical_id', $request->id)
            ->with('laboratories')
            ->get()->toArray();
        if(count($inspection)==0){
            $inspection = Equipments::where("asset_number", $request->id)
                ->with('laboratories')
                ->get()->toArray();
            if(count($inspection)==0){
                return response()->json("该二维码不存在", 200);
            }else{
                return response()->json($inspection[0], 200);
            }
        }else{
            return response()->json($inspection[0], 200);
        }
    }
}
