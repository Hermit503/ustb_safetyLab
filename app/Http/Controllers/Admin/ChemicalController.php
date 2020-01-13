<?php

namespace App\Http\Controllers\Admin;

use App\Chemical;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ChemicalController extends Controller
{
    /**
     * 获取药品列表
     */
    public function getChemicalsList(){
        $chemicals = Chemical::with('laboratories')
            ->with('unit')
            ->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.chemicalsList')
                ->with('chemicals',$chemicals);
        }

        abort(404);
    }
}
