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
        $equipments = Equipment::where('unit_id',$unit_id)
            ->where('isDelete','=','1')
            ->get();
        return $equipments;
    }

    /**
     * 获取实验室地址
     * @author lj
     * @param Request $request
     * @return Object
     * @time 2019-07-06
     */
    public function getLaboratory(Request $request){
        $id = $request->laboratory_id;
        $msg = Laboratory::where('id',$id)->get();
        return $msg;
    }

    /**
     * 添加设备
     * @author lj
     * @param Request $request
     * @return string
     * @time 2019-07-05
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

        $equipment->save();

        return "上传成功";
    }

    /**
     * 获取修改前的设备信息
     * @author lj
     * @param Request $request
     * @return Object
     * @time 2019-07-06
     */
    public function oldEquipment(Request $request){
        $id = $request->id;
        $result = Equipment::where('id',$id)->get();
        return $result;
    }

    /**
     * 修改设备信息
     * @author lj
     * @param Request $request
     * @return string
     * @time 2019-07-06
     */
    public function updateEquipment(Request $request){
        $id = $request->id;
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

        Equipment::where('id','=', $id)
            ->update([
                'asset_number' => $asset_number,
                'equipment_name' => $equipment_name,
                'equipment_type' => $equipment_type,
                'laboratory_id' => $laboratory_id,
                'build_id' => $build_id,
                'unit_id' => $unit_id,
                'status' => $status,
                'storage_time' => $storage_time,
                'scrap_time' => $scrap_time
            ]);
        return "修改成功";
    }

    /**
     * 删除设备
     * @author lj
     * @param Request $request
     * @return string
     * @time 2019-07-05
     * 新增字段isDelete,删除时为0,默认为1
     * @author lj
     * @time 2019-07-07
     */
    public function deleteEquipment(Request $request){
        $id = $request->id;
        Equipment::where('id','=', $id)
            ->update(['isDelete' => '0']);
        return "删除成功";
    }
}
