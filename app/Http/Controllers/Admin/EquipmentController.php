<?php

namespace App\Http\Controllers\Admin;

use App\Equipments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
}
