<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Laboratory;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            ->where('isDelete','=','0')
            ->paginate(10);
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
        $id = $request->id;
        $msg = Laboratory::where('id',$id)->get();
        return $msg;
    }

    /**
     * 获取实验室地址列表
     * @author lj
     * @param Request $request
     * @return json
     * @time 2019-07-09
     */
    public function getLaboratoryList(Request $request){
        $unit_id = $request->unit_id;
        $laboratory = Laboratory::where('unit_id',$unit_id)->get();
        $laboratoryAll = [];
        $laboratoryBuild = [];
        $laboratoryClassroom = [];
        $i = 0;
        foreach ($laboratory as $item) {
            $laboratoryBuild[$i] = $item->building_name;
            $laboratoryClassroom[$i] = $item->classroom_num;
            $i = $i+1;
        }

        $length=count($laboratoryBuild);
        $length2=count($laboratoryClassroom);

        for($i = 0 ; $i < $length ; $i++){
            if(array_key_exists($i,$laboratoryBuild)){
                for($j = $i+1; $j < $length ; $j++){
                    if(strcmp($laboratoryBuild[$j] , $laboratoryBuild[$i]) == 0){
                        unset($laboratoryBuild[$j]);
                    }else{
                        continue;
                    }
                }
            }else{
                continue;
            }
        }

        for($x = 0 ; $x < $length2 ; $x++){
            if(array_key_exists($x,$laboratoryClassroom)){
                for($y = $x+1; $y < $length2 ; $y++){
                    if(strcmp($laboratoryClassroom[$y] , $laboratoryClassroom[$x]) == 0){
                        unset($laboratoryClassroom[$y]);
                    }else{
                        continue;
                    }
                }
            }else{
                continue;
            }
        }

        $q = 0;
        $w = 0;
        foreach ($laboratoryBuild as $item1){
            $laboratoryAll[0][$q] = $item1;
            $q += 1;
        }

        foreach ($laboratoryClassroom as $item2){
            $laboratoryAll[1][$w] = $item2;
            $w += 1;
        }

        return $laboratoryAll;
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
    public function getOneEquipment(Request $request){
        $id = $request->id;
        $result = Equipment::where('id',$id)->get();
        return response()->json([
            'result'=>$result
        ],200);
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
        return response()->json([], 200);
    }

    /**
     * 删除设备
     * @author lj
     * @param Request $request
     * @return string
     * @time 2019-07-05
     * 新增字段isDelete,删除时为1,默认为0
     * @author lj
     * @time 2019-07-07
     */
    public function deleteEquipment(Request $request){
        $id = $request->id;
        Equipment::where('id','=', $id)
            ->update(['isDelete' => '1']);
        Log::alert('有设备信息被删除 ', $request->all());
        return response('Deleted',204);
    }

    /**
     * 搜索设备
     * @author lj
     * @param Request $request
     * @return Object/string
     * @time 2019-07-11
     */
    public function searchEquipment(Request $request)
    {
        $keyword = $request->keyword;
        $result = Equipment::where('equipment_name',$keyword)->get()->toArray();
        if($result){
            return $result;
        }else{
            return "您管辖的部门暂无此设备";
        }
    }
}
