<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Laboratory;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * @author lj
     * @param Request $request
     * @return json形式的仪器列表
     */
    public function getEquipment(Request $request){
        $unit_id = $request->unit_id;
        $equipments = Equipment::where('unit_id',$unit_id)->get();
        return $equipments;
    }

    /**
     * @author lj
     * @param Request $request
     * @return true/false
     */
    public function addEquipment(Request $request){
        $asset_number = $request->asset_number;
        $equipment_name = $request->equipment_name;
        $equipment_type = $request->equipment_type;
        $laboratory_name = $request->laboratory_id;
        $build_id = $request->build_id;
        $unit_id = $request->unit_id;
        $status = $request->status;
        $storage_time = $request->storage_time;
        $scrap_time = $request->scrap_time;

        $laboratory = Laboratory::where('laboratory_name',$laboratory_name)->get();
        $laboratory_id = $laboratory[0]['id'];

        $equipment = new Equipment();

        $equipment->asset_number = $asset_number;
        $equipment->equipment_name = $equipment_name;
        $equipment->equipment_type = $equipment_type;
        $equipment->laboratory_id = $laboratory_id;
        $equipment->build_id = $build_id;
        $equipment->unit_id = $unit_id;
        $equipment->status = $status;
        $equipment->storage_time = $storage_time;
        $equipment->scrap_time = $scrap_time;

        return "上传成功";
    }
}
