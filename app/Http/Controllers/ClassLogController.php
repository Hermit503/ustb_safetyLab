<?php

namespace App\Http\Controllers;

use App\ClassLog;
use App\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClassLogController extends Controller
{
    public function createClassLog(Request $request)
    {
        Log::info($request);
        $classLog = new ClassLog();
        $classLog->experimentalName=$request->experimentalName;
        $classLog->buildingName=$request->buildingName;
        $classLog->classroomNum=$request->classroomNum;
        $classLog->className=$request->className;
        $classLog->classNum=$request->classNum;
        $classLog->studentNum=$request->studentNum;
        $classLog->status=$request->status;
        $classLog->save();
        return response()->json("提交成功",200);
    }

    public function getLaboratoryList(Request $request)
    {
        $arr = array();
        $laboratories = Laboratory::where([])->orderBy('building_name','asc')->distinct()->get('building_name');
        for ($i = 0;$i<count($laboratories);$i++){
            $lab = Laboratory::where('building_name', $laboratories[$i]->building_name)->get();
            $arr[$i]=$lab;
        }
        return response()->json($arr,200);

    }

    public function getClassLog(Request $request)
    {
        $classLogs = ClassLog::where([])->orderBy('created_at','desc')->get();
        return response()->json($classLogs,200);
    }
}
