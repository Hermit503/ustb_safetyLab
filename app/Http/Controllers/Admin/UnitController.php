<?php

namespace App\Http\Controllers\Admin;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    /**
     * 获取单位列表
     */
    public function getUnitList(){
        $units = Unit::paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.unitList')
                ->with('units',$units);
        }

        abort(404);
    }

    /**
     * 新增单位
     */
    public function newUnit(Request $request){
        $unit_name = $request->unit_name;
        $unit_type = $request->unit_type;
        $created_at = date("Y-m-d H:i:s");

        $unit = new Unit;
        $unit->unit_name = $unit_name;
        $unit->unit_type = $unit_type;
        $unit->created_at = $created_at;
        $unit->updated_at = $created_at;
        if(Gate::allows('access-admin',Auth::user())){
            $unit->save();
            Log::info("管理员".Auth::user()['user_id']."添加单位：".$unit_name."(".$unit_type.")");
            return "新增成功";
        }else{
            abort(404);
        }
    }

    /**
     * 修改单位
     */
    public function modifyUnit(Request $request){
        $id = $request->id;
        $unit_name = $request->unit_name;
        $unit_type = $request->unit_type;
        $updated_at = date("Y-m-d H:i:s");

        $unit = Unit::find($id);
        $unit->unit_name = $unit_name;
        $unit->unit_type = $unit_type;
        $unit->updated_at = $updated_at;
        if(Gate::allows('access-admin',Auth::user())){
            $unit->save();
            Log::info("管理员".Auth::user()['user_id']."修改单位：".$unit_name."(".$unit_type.")");
            return "修改成功";
        }else{
            abort(404);
        }
    }
}
