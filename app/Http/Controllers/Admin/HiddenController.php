<?php

namespace App\Http\Controllers\Admin;

use App\Hidden;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HiddenController extends Controller
{
    /**
     * 隐患列表
     */
    public function getHiddensList(){
        $hiddens = Hidden::with('user')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.hiddensList')
                ->with('hiddens',$hiddens);
        }

        abort(404);
    }
}
