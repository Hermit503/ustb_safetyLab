<?php

namespace App\Http\Controllers;

use App\Hidden;
use App\HiddensLog;
use App\User;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HiddenController extends Controller
{
    public function addHidden(Request $request)
    {

        $hidden = new Hidden();
        $hidden->user_id = $request->user_id;
        $hidden->position = $request->position;
        $hidden->title = $request->title;
        $hidden->detail = $request->detail;
        $hidden->image = env('APP_URL') . $request->image;
        $hidden->occurrence_time = now();
        $hidden->save();
        Log::info($request);
        return response('', 201);
    }

    public function getHidden(Request $request)
    {
        $hidden = Hidden::orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'hidden' => $hidden
        ], 200);
    }

    /**
     * @author hzj
     * @date 2019-07-11
     * @param Request $request
     * @return mixed
     */
    public function saveHiddenImage(Request $request)
    {
        Log::info($request);
        $path = $request->file('file')->store('public/hiddensImage');
        return Storage::url($path);//storage/hiddensImage/sPgs2P4lIkfaphxymlrF2dRj4rshfQMFxJ3clkZy.jpeg
    }

    /**
     * 获取隐患详情  、
     * @date 2019-07-21
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHiddenDetail(Request $request)
    {
        $detail = Hidden::where('user_id', $request->user_id)
            ->where('title', $request->title)
            ->with('hiddens_logs')
            ->get();
        $user = User::where('user_id', $request->user_id)->first();

        Log::info($detail);
        return response()->json([
            'detail' => $detail,
            'user' => $user
        ], 200);
    }

    /***
     * @date 2019-07-24
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addHiddenLog(Request $request)
    {
        $hidden_id = Hidden::where('user_id', $request->reportPerson)
            ->where('title', $request->title)
            ->get('id');
        //判断改变状态
        if (strlen($request->solveStatus)==4){
            $hidden = Hidden::where('user_id', $request->reportPerson)
                ->where('title', $request->title)->first();
            $hidden->isSolve =1;
            $hidden->save();
        }
        //添加log到数据库
        $hiddenLog = new HiddensLog();
        $hiddenLog->hidden_id = $hidden_id[0]->id;
        $hiddenLog->user_id = $request->user_id;
        $hiddenLog->reason = $request->reason;
        $hiddenLog->image = $request->image;
        $hiddenLog->save();
        return response()->json([],201);
    }
}
