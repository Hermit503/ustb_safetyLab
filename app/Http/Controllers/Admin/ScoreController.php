<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ScoreController extends Controller
{
    /**
     * 成绩管理
     */
    public function getScoreList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.scoreList');
        }

        abort(404);
    }
}
