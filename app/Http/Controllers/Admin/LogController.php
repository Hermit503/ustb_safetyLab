<?php

namespace App\Http\Controllers\Admin;

use Haruncpi\LaravelLogReader\LaravelLogReader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    /**
     * 日志列表
     */
    public function getLogList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.logsList');
        }

        abort(404);
    }

    /**
     * 获取日志数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request){
        $date = $request->date;
        $logs = new LaravelLogReader(['date' => $date]);

        $log = $logs->get();
        if(Gate::allows('access-admin',Auth::user())) {
            return $log;
        }
    }
}
