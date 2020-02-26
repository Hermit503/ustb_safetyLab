<?php

namespace App\Http\Controllers\Admin;

use App\Chemical;
use App\Laboratory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ChemicalController extends Controller
{
    /**
     * 获取药品列表
     */
    public function getChemicalsList(){
        $chemicals = Chemical::with('laboratories')
            ->with('unit')
            ->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.chemicalsList')
                ->with('chemicals',$chemicals);
        }

        abort(404);
    }

    /**
     * 添加新药品
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-26
     */
    public function newChemical(Request $request){
        $chemical_id = $request->chemical_id;
        $CAS = $request->CAS;
        $name = $request->name;
        $type = $request->type;
        $unit_id = $request->unit_id;
        $build = $request->build;
        $classroom = $request->classroom;
        $laboratory = $request->laboratory;
        $user_id = $request->user_id;
        $monitor_id = $request->monitor_id;
        $status = $request->status;
        $stock = $request->stock;
        $unit_type = $request->unit_type;
        $fix_time = $request->fix_time;
        $remarks = $request->remarks;

        $laboratory = Laboratory::where('building_name',$build)
            ->where('classroom_num',$classroom)
            ->where('laboratory_name',$laboratory)
            ->get();
        $laboratory_id = $laboratory[0]['id'];

        $chemical = new Chemical();

        $chemical->chemical_id = $chemical_id;
        $chemical->CAS = $CAS;
        $chemical->name = $name;
        $chemical->type = $type;
        $chemical->unit_id = $unit_id;
        $chemical->laboratory_id = $laboratory_id;
        $chemical->user_id = $user_id;
        $chemical->monitor_id = $monitor_id;
        $chemical->status = $status;
        $chemical->stock = $stock;
        $chemical->unit_type = $unit_type;
        $chemical->fix_time = $fix_time;
        $chemical->remarks = $remarks;

        if(Gate::allows('access-admin',Auth::user())) {
            $chemical->save();
            Log::info("管理员".Auth::id()."添加了药品，具体信息如下：药品id编号:".$chemical_id.",单位id：".$unit_id.",管理员id:".$user_id.",实验室id:".$laboratory_id .",状态：".$status.",管理者：".$monitor_id.",类型：".$type.",药品名：".$name.",CAS编号：".$CAS.",库存量：".$stock.$unit_type.",最近核查时间：".$fix_time.",备注：".$remarks);
            return "添加成功";
        }
        abort(404);
    }

    /**
     * 修改药品信息
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-26
     */
    public function modifyChemical(Request $request){
        $id = $request->id;
        $chemical_id = $request->chemical_id;
        $CAS = $request->CAS;
        $name = $request->name;
        $type = $request->type;
        $unit_id = $request->unit_id;
        $build = $request->build;
        $classroom = $request->classroom;
        $laboratory = $request->laboratory;
        $user_id = $request->user_id;
        $monitor_id = $request->monitor_id;
        $status = $request->status;
        $stock = $request->stock;
        $unit_type = $request->unit_type;
        $fix_time = $request->fix_time;
        $remarks = $request->remarks;

        $laboratory = Laboratory::where('building_name',$build)
            ->where('classroom_num',$classroom)
            ->where('laboratory_name',$laboratory)
            ->get();
        $laboratory_id = $laboratory[0]['id'];

        if(Gate::allows('access-admin',Auth::user())) {
            Chemical::where('id',$id)
                ->update([
                    'chemical_id' => $chemical_id,
                    'CAS' => $CAS,
                    'name' => $name,
                    'type' => $type,
                    'unit_id' => $unit_id,
                    'laboratory_id' => $laboratory_id,
                    'user_id' => $user_id,
                    'monitor_id' => $monitor_id,
                    'status' => $status,
                    'stock' => $stock,
                    'unit_type' => $unit_type,
                    'fix_time' => $fix_time,
                    'remarks' => $remarks
                ]);
            Log::info("管理员".Auth::id()."添加了药品，具体信息如下：药品id编号:".$chemical_id.",单位id：".$unit_id.",管理员id:".$user_id.",实验室id:".$laboratory_id .",状态：".$status.",管理者：".$monitor_id.",类型：".$type.",药品名：".$name.",CAS编号：".$CAS.",库存量：".$stock.$unit_type.",最近核查时间：".$fix_time.",备注：".$remarks);
            return "修改成功";
        }
        abort(404);
    }

    /**
     * 删除药品
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-02-26
     */
    public function deleteChemical(Request $request){
        $id = $request->id;
        //TODO: 软删除
        if(Gate::allows('access-admin',Auth::user())) {
            Chemical::find($id)->delete();
            Log::alert('管理员'.Auth::id()."删除了id为".$id."的药品");
            return "删除成功";
        }
        abort(404);
    }
}
