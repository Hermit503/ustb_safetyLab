<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\Equipments;
use App\Http\Controllers\ToolController;
use App\Hidden;
use App\HiddensLog;
use App\User;
use mysql_xdevapi\Exception;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HiddenController extends Controller
{
    /** 隐患上传
     * @author hzj
     * @date 2019-07-10
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addHidden(Request $request)
    {
        $hidden = new Hidden();
        $hidden->user_id = $request->user_id;
        $hidden->type = $request->type;
        $hidden->position = $request->position;
        $hidden->title = $request->title;
        $hidden->detail = $request->detail;
        $hidden->image = env('APP_URL') . $request->image;
        $hidden->occurrence_time = now();
        $hidden->save();
//        Log::info($request);
        return response('', 201);
    }

    /**返回隐患列表 倒序输出
     * @author hzj
     * @date 2019-07-22
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHidden(Request $request)
    {
        $hidden = Hidden::join('users','users.user_id','hiddens.user_id')
                ->select('hiddens.*','users.unit_id')
                ->where('unit_id',$request->unit_id)
                ->orderBy('created_at','desc')
                ->paginate(15);
        return response()->json([
            'hidden' => $hidden
        ], 200);
    }

    /**保存图片 返回路径
     * @author hzj
     * @date 2019-07-11
     * @param Request $request
     * @return mixed
     */
    public function saveHiddenImage(Request $request)
    {
        $path = $request->file('file')->store('public/hiddensImage');
//        Log::info(Storage::url($path));
        return Storage::url($path);//storage/hiddensImage/sPgs2P4lIkfaphxymlrF2dRj4rshfQMFxJ3clkZy.jpeg
    }

    /**
     * 获取单个隐患详情
     * @date 2019-07-21
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHiddenDetail(Request $request)
    {
//        Log::info($request);
        $detail = Hidden::where('id', $request->id)
            ->where('title', $request->title)
            ->with('hiddens_logs')
            ->get();
        $user = User::where('user_id', $request->user_id)->first();
//        Log::info($detail);
        return response()->json([
            'detail' => $detail,
            'user' => $user
        ], 200);
    }

    /***
     * 处理隐患  生成记录
     * @date 2019-07-24
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addHiddenLog(Request $request)
    {
        //修改设备药品状态
        $number = Hidden::where('id',$request->hidden_id)->get('number');
        $eq = Equipments::where('asset_number',$number[0]['number'])->first();
        $cm = Chemical::where('chemical_id',$number[0]['number'])->first();
        if($eq!=null){
            $eq->status="正常";
            $eq->save();
        }
        if($cm=null){
            $cm->status="正常";
            $cm->save();
        }
        //判断改变状态
        if ($request->solveStatus == "true") {
                $hidden =Hidden::where('user_id', $request->reportPerson)
                    ->where('title', $request->title)
//                    ->where('number','<>',null)
                    ->first();
                $hidden->isSolve = 1;
                $hidden->number = null;
                $hidden->save();
        }

        //添加隐患处理日志到数据库
        $name = (new ToolController)->getUsername($request->reportPerson);
        $hiddenLog = new HiddensLog();
        $hiddenLog->hidden_id = $request->hidden_id;
        $hiddenLog->user_name = $name;
        $hiddenLog->reason = $request->reason;
        if ($request->image!=null) {
            $hiddenLog->image = env('APP_URL') .$request->image;
            $hiddenLog->isSolve = '1';
        } else{
            $hiddenLog->image = null;
        }
        $hiddenLog->save();
        return response()->json([], 201);

    }





}
