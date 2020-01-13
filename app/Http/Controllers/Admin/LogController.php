<?php

namespace App\Http\Controllers\Admin;

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
            return view('admin.logList');
        }

        abort(404);
    }
}
