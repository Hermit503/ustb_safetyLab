<?php

namespace App\Http\Controllers;

use App\Equipments;
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
        $equipments = Equipments::where('unit_id',$unit_id)
            ->where('isDelete','=','0')
            ->paginate(15);
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
     * 获取指定教学楼的教室
     * @param $unit_id
     * @param $building_name
     * @return array
     * @author lj
     * @time 2019-8-28
     */
    public function getClassroom($unit_id,$building_name)
    {
        $tmp = Laboratory::where('unit_id',$unit_id)
            ->where('building_name',$building_name)
            ->get();
        $result = [];
        foreach ($tmp as $item){
            array_push($result,$item['classroom_num']);
        }
        return $result;
    }

    public function getClassroomName($unit_id,$build_name,$classroom)
    {
        $tmp_class = Laboratory::where('building_name',$build_name)
            ->where('classroom_num',$classroom)
            ->where('unit_id',$unit_id)
            ->get()
            ->toArray();
        $result = [];
        foreach ($tmp_class as $item){
            array_push($result,$item['laboratory_name']);
        }
        return $result;
    }

    /**
     * 获取实验室地址列表
     * @author lj
     * @param Request $request
     * @return json
     * @time 2019-07-09
     * 修改列表获取方式
     * @author lj
     * @time 2019-08-27
     */
    public function getLaboratoryList(Request $request){
        $unit_id = $request->unit_id;
        $result = [];
        $laboratory = Laboratory::where('unit_id',$unit_id)->get();
        $laboratoryBuild  = [];

        //获取教学楼名儿
        foreach ($laboratory as $item){
            array_push($laboratoryBuild ,$item['building_name']);
        }

        $tmp_build = [];
        $tmp_class = [];
        $tmp_class_name = [];

        $i = 0;
        //教学楼去重 源：$laboratoryBuild 去重后：$tmp_build
        foreach (array_unique($laboratoryBuild ) as $item){
            $tmp_build[$i] = $item;
            $i = $i+1;
        }

        $i = 0;
        //遍历教学楼，获取教学楼对应的教室号 $classroom
        foreach ($tmp_build as $key=>$item){
//            echo $item."<br/>";
            //获取教室号
            $classroom = $this->getClassroom($unit_id,$item);
            //教师号去重 源：$classroom 去重后：$tmp_class
            $j = 0;
            foreach (array_unique($classroom) as $key_2 => $class){
//                //把实验室教室号放入数组中
                $arr = [];
                array_push($arr,$class);
                $tmp_class[$i][$j][0] = $arr;
//                var_dump($tmp_class[$j][0]);
                //获取实验室名称 $classroomName
                $classroomName = $this->getClassroomName($unit_id,$item,$class);
//
//                //将实验室名称放入数组中 $tmp_class_name
                $z = 0;
                foreach ($classroomName as $name){
////                    var_dump($result[$i][1]);
                    $tmp_class_name[$z] = $name;
//
                    $tmp_class[$i][$j]['children'][$z] = $name;
                    $z = $z + 1;
                }
                $j = $j+1;
            }

            $arr = [];
            //将字符串放入数组中
            array_push($arr,$item);
            //二维数组
            array_push($result,$arr);
            //三维的
//            $arr['children'] = [];
            $result[$i]['children'] = $tmp_class[$i];
            $i = $i+1;
        }
//        return $tmp_class;
        return $result;
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
        $laboratory_build = $request->laboratory_build;
        $laboratory_class = $request->laboratory_class;
        $build_id = $request->build_id;
        $unit_id = $request->unit_id;
        $status = $request->status;
        $storage_time = $request->storage_time;
        $scrap_time = $request->scrap_time;

        $laboratory = Laboratory::where('building_name',$laboratory_build)
            ->where('classroom_num',$laboratory_class)
            ->get();
        $laboratory_id = $laboratory[0]['id'];

        $equipment = new Equipments();

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
        $result = Equipments::where('id',$id)->get();
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

        Equipments::where('id','=', $id)
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
        Equipments::where('id','=', $id)
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
        $result = Equipments::where('equipment_name',$keyword)->get()->toArray();
        if($result){
            return $result;
        }else{
            return "您管辖的部门暂无此设备";
        }
    }
}
