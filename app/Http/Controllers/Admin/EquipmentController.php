<?php

namespace App\Http\Controllers\Admin;

use App\Equipments;
use App\Laboratory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EquipmentController extends Controller
{
    /**
     * 获取实验室设备列表
     */
    public function getEquipmentsList(){
        $equipments = Equipments::with('laboratories')
                        ->where('isDelete','0')
                        ->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.equipmentsList')
                ->with('equipments',$equipments);
        }

        abort(404);
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

    public function getClassroomName($unit_id,$build_name,$classroom){
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

    public function test(){
        return $this->getClassroom('1','7教');
    }

    /**
     * 获取实验室地址列表
     * @param Request $request
     * @return array
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
//            $tmp_build[$i] = $item;
            array_push($tmp_build,$item);
//            $i = $i+1;
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
        $laboratory_build = $request->build_id;
        $laboratory_class = $request->classroom;
        $laboratory_name = $request->laboratory;
        $build_id = Auth::id();
        $unit_id = $request->unit_id;
        $status = $request->status;
        $storage_time = $request->storage_time;
        $scrap_time = $request->scrap_time;
        $fix_time = $request->fix_time;

        $laboratory = Laboratory::where('building_name',$laboratory_build)
            ->where('classroom_num',$laboratory_class)
            ->where('laboratory_name',$laboratory_name)
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
        $equipment->fix_time = $fix_time;

        if(Gate::allows('access-admin',Auth::user())) {
            $equipment->save();
            Log::info('管理员'.$build_id."添加了设备，具体信息如下：资产编号：".$asset_number.",设备名：".$equipment_name.",设备类型".$equipment_type.",实验室id：".$laboratory_id.",单位id:".$unit_id.",状态：".$status.",最近检修时间：".$fix_time.",入库时间：".$storage_time.",预计报废时间".$scrap_time);
            return "添加成功";
        }

        abort(404);
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
        $laboratory_id = $result[0]['laboratory_id'];
        $laboratory = Laboratory::where('id',$laboratory_id)->get(['building_name','classroom_num','laboratory_name']);
        $result[0]['laboratory'] = $laboratory[0];
        if(Gate::allows('access-admin',Auth::user())) {
            return $result[0];
        }
        abort(404);
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

        //教学楼
        $laboratory_build = $request->build_id;
        //教室
        $laboratory_class = $request->classroom;
        //实验室名称
        $laboratory_name = $request->laboratory;


        //创建人
        $build_id = Auth::id();
        //单位
        $unit_id = $request->unit_id;
        $status = $request->status;
        $storage_time = $request->storage_time;
        $scrap_time = $request->scrap_time;
        $fix_time = $request->fix_time;

        $laboratory = Laboratory::where('building_name',$laboratory_build)
            ->where('classroom_num',$laboratory_class)
            ->where('laboratory_name',$laboratory_name)->get();
        $laboratory_id = $laboratory[0]['id'];

        if(Gate::allows('access-admin',Auth::user())) {
            Equipments::where('id', (int)$id)
                ->update([
                    'asset_number' => $asset_number,
                    'equipment_name' => $equipment_name,
                    'equipment_type' => $equipment_type,
                    'laboratory_id' => (int)$laboratory_id,
                    'build_id' => (int)$build_id,
                    'unit_id' => (int)$unit_id,
                    'status' => $status,
                    'fix_time' => $fix_time,
                    'storage_time' => date("Y-m-d H:i:s", strtotime($storage_time)),
                    'scrap_time' => date("Y-m-d", strtotime($scrap_time))
                ]);
            Log::info('管理员'.$build_id."修改了id为".$id."的设备，具体信息如下：资产编号：".$asset_number.",设备名：".$equipment_name.",设备类型".$equipment_type.",实验室id：".$laboratory_id.",单位id:".$unit_id.",状态：".$status.",最近检修时间：".$fix_time.",入库时间：".$storage_time.",预计报废时间".$scrap_time);
            return "修改成功";
        }
        abort(404);
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
        if(Gate::allows('access-admin',Auth::user())) {
            Equipments::where('id', '=', $id)
                ->update(['isDelete' => '1']);
            Log::alert('管理员' . Auth::id() . "删除了id为" . $id . "的设备");
            return response('Deleted', 204);
        }
        abort(404);
    }
}
