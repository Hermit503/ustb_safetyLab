<?php

namespace App\Http\Controllers\Admin;

use App\examManage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PaperController extends Controller
{
    /**
     * 试题管理
     */
    public function getPaperList(){
        $exam = examManage::with('units')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.papersList')->with('exams',$exam);
        }

        abort(404);
    }

    /**
     * 添加
     * @param Request $request
     * @return string
     */
    public function newPaper(Request $request){
        $unit_id = $request->unit_id;
        $aqts = $request->aqts;
        $dqaq = $request->dqaq;
        $hxp = $request->hxp;
        $jxjz = $request->jxjz;
        $tzsb = $request->tzsb;
        $xfaq = $request->xfaq;
        $yxsw = $request->yxsw;

        $examManage = new examManage();

        $examManage->unit_id = $unit_id;
        $examManage->aqts = $aqts;
        $examManage->dqaq = $dqaq;
        $examManage->hxp = $hxp;
        $examManage->jxjz = $jxjz;
        $examManage->tzsb = $tzsb;
        $examManage->xfaq = $xfaq;
        $examManage->yxsw = $yxsw;

        if(Gate::allows('access-admin',Auth::user())){
            $examManage->save();
            Log::info('管理员'.Auth::user()['user_id'].'添加了试题题目数量规定，具体如下,单位id'.$unit_id .',安全通识题目数量:'.$aqts.",电气题目数量:".$dqaq.',化学题目数量:'.$hxp.',机械建筑题目数量:'.$jxjz .',特种设备题目数量:'.$tzsb.',消防安全题目数量:'.$xfaq.',医学生物安全题目数量:'.$yxsw);
            return "添加成功";
        }
        abort(404);
    }

    /**
     * 修改
     * @param Request $request
     * @return string
     */
    public function modifyPaper(Request $request){
        $id = $request->id;
        $aqts = $request->aqts;
        $dqaq = $request->dqaq;
        $hxp = $request->hxp;
        $jxjz = $request->jxjz;
        $tzsb = $request->tzsb;
        $xfaq = $request->xfaq;
        $yxsw = $request->yxsw;

        if(Gate::allows('access-admin',Auth::user())) {
            examManage::where('id',$id)
                ->update([
                    'aqts' => $aqts,
                    'dqaq' => $dqaq,
                    'hxp' => $hxp,
                    'jxjz' => $jxjz,
                    'tzsb' => $tzsb,
                    'xfaq' => $xfaq,
                    'yxsw' => $yxsw,
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
            Log::info('管理员'.Auth::user()['user_id'].'修改了id为'.$id.'的试题题目数量规定，具体如下,安全通识题目数量:'.$aqts.",电气题目数量:".$dqaq.',化学题目数量:'.$hxp.',机械建筑题目数量:'.$jxjz .',特种设备题目数量:'.$tzsb.',消防安全题目数量:'.$xfaq.',医学生物安全题目数量:'.$yxsw);
            return "修改成功";
        }
        abort(404);

    }

    /**
     * 删除
     * @param Request $request
     * @return string
     */
    public function deletePaper(Request $request){
        $id = $request->id;
        if(Gate::allows('access-admin',Auth::user())) {
            examManage::find($id)->delete();
            Log::alert('管理员'.Auth::user()['user_id']."删除了id为".$id."的记录");
            return "删除成功";
        }
        abort(404);
    }
}
