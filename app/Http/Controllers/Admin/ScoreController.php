<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class ScoreController extends Controller
{
    /**
     * 成绩管理
     */
    public function getScoreList()
    {
        if (Gate::allows('access-admin', Auth::user())) {
            $user = User::with('unit')->where('isDelete', '0')->paginate(10);
            return view('admin.scoreList')
                ->with('users', $user);
        }

        abort(404);
    }

    public function modifyScore(Request $request)
    {
        if (Gate::allows('access-admin', Auth::user())){
            $user = User::where('user_id',$request->user_id)->first();
            $user->exam_result=$request->score;
            $user->residue_degree=$request->degree;
            $user->save();
            return "修改成功";
        }

        abort(404);

    }
}
