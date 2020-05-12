<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\InspectionLog;

class InspectionController extends Controller
{
    //获取巡检记录
    public function getInspectionRecords(){
        $InspectionLogs = InspectionLog::with('unit')->orderBy('created_at','desc')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.inspectionLog')->with('InspectionLogs',$InspectionLogs);
        }
        abort(404);
    }
}
