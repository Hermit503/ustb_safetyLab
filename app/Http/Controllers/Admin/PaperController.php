<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaperController extends Controller
{
    /**
     * 试题管理
     */
    public function getPaperList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.papersList');
        }

        abort(404);
    }
}
