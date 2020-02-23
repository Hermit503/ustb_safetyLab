<?php

namespace App\Http\Controllers\Admin;

use App\Laboratory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class LaboratoryController extends Controller
{
    /**
     * 获取实验室列表
     */
    public function getLaboratoriesList(){
        $laboratories = Laboratory::with('unit')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.laboratoriesList')
                ->with('laboratories',$laboratories);
        }

        abort(404);
    }

    /**
     * 新增实验室
     * @param Request $request
     * @author lj
     * @time 2020-01-12
     */
    public function newLaboratory(Request $request){
        $laboratory_name = $request->new_laboratory_name;
        $building_name = $request->new_building_name;
        $classroom_num = $request->new_classroom_name;
        $unit_id = $request->new_unit_id;
        $laboratory_type = $request->new_laboratory_type;
        $laboratory_attribute = $request->new_laboratory_attribute;
        $laboratory_status = $request->new_laboratory_status;
        $safety_head = $request->new_safety_head;
        $maintenance_people1 = $request->new_laboratory_people1;
        $maintenance_people2 = $request->new_laboratory_people2;
        $created_at = date("Y-m-d H:i:s");

        $laboratory = new Laboratory;
        $laboratory->laboratory_name = $laboratory_name;
        $laboratory->building_name = $building_name;
        $laboratory->classroom_num = $classroom_num;
        $laboratory->unit_id = $unit_id;
        $laboratory->laboratory_type = $laboratory_type;
        $laboratory->laboratory_attribute = $laboratory_attribute;
        $laboratory->laboratory_status = $laboratory_status;
        $laboratory->safety_head = $safety_head;
        $laboratory->maintenance_people1 = $maintenance_people1;
        $laboratory->maintenance_people2 = $maintenance_people2;
        $laboratory->created_at = $created_at;
        $laboratory->updated_at = $created_at;

        if(Gate::allows('access-admin',Auth::user())){
            $laboratory->save();
            Log::info("管理员".Auth::user()['user_id']."添加了实验室：地址：".$building_name.$classroom_num.$laboratory_name .",单位：".$unit_id."，实验室类型：".$laboratory_type."，实验室属性：".$laboratory_attribute."，实验室状态：".$laboratory_status."，实验室安全负责人：".$safety_head."，实验室管理员：".$maintenance_people1."和" .$maintenance_people2);
            return "新增成功";
        }else{
            abort(404);
        }

    }

    /**
     * 修改实验室
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-12
     */
    public function modifyLaboratory(Request $request){
        $id = $request->id;
        $laboratory_name = $request->modify_laboratory_name;
        $building_name = $request->modify_building_name;
        $classroom_num = $request->modify_classroom_name;
        $unit_id = $request->modify_unit_id;
        $laboratory_type = $request->modify_laboratory_type;
        $laboratory_attribute = $request->modify_laboratory_attribute;
        $laboratory_status = $request->modify_laboratory_status;
        $safety_head = $request->modify_safety_head;
        $maintenance_people1 = $request->modify_laboratory_people1;
        $maintenance_people2 = $request->modify_laboratory_people2;
        $updated_at = date("Y-m-d H:i:s");

        $laboratory = Laboratory::find($id);
        $laboratory->laboratory_name = $laboratory_name;
        $laboratory->building_name = $building_name;
        $laboratory->classroom_num = $classroom_num;
        $laboratory->unit_id = $unit_id;
        $laboratory->laboratory_type = $laboratory_type;
        $laboratory->laboratory_attribute = $laboratory_attribute;
        $laboratory->laboratory_status = $laboratory_status;
        $laboratory->safety_head = $safety_head;
        $laboratory->maintenance_people1 = $maintenance_people1;
        $laboratory->maintenance_people2 = $maintenance_people2;
        $laboratory->updated_at = $updated_at;

        if(Gate::allows('access-admin',Auth::user())){
            $laboratory->save();
            Log::info("管理员".Auth::user()['user_id']."修改实验室：id：".$id."地址：".$building_name.$classroom_num.$laboratory_name .",单位：".$unit_id."，实验室类型：".$laboratory_type."，实验室属性：".$laboratory_attribute."，实验室状态：".$laboratory_status."，实验室安全负责人：".$safety_head."，实验室管理员：".$maintenance_people1."和" .$maintenance_people2);
            return "修改成功";
        }else{
            abort(404);
        }
    }
}
