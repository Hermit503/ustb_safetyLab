<?php

namespace App\Http\Controllers\Admin;

use App\Chemical;
use App\Equipments;
use App\Hidden;
use App\Laboratory;
use App\Notice;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WelcomeController extends Controller
{
    /**
     * 数据统计
     */
    public function getWelcome(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.welcome',[
                'unitCount' =>Unit::all()->count(),
                'userCount'=>User::where('isDelete','0')->count(),
                'laraboryCount' => Laboratory::all()->count(),
                'equipmentCount' => Equipments::where('isDelete','0')->count(),
                'chemicalCount' => Chemical::all()->count(), //TODO:数据库改完之后这里改一下
                'noticeCount' => Notice::all()->count(),
                'hiddenCount' => Hidden::all()->count()
            ]);
        }
        abort(404);
    }
}
