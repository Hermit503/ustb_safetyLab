<?php

namespace App\Http\Controllers;

use App\Hidden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HiddenController extends Controller
{
    public function addHidden(Request $request)
    {

        $hidden = new Hidden();
        $hidden->user_id=$request->user_id;
        $hidden->position=$request->position;
        $hidden->title=$request->title;
        $hidden->detail=$request->detail;
        $hidden->image=env('APP_URL').$request->image;
        $hidden->occurrence_time=now();
        $hidden->save();
        Log::info($request);
        return response('',201);
    }

    public function getHidden(Request $request)
    {
        return Hidden::all();
    }

    public function saveHiddenImage(Request $request)
    {
        Log::info($request);
        $path = $request->file('file')->store('public/hiddensImage');
        return Storage::url($path);///storage/hiddensImage/sPgs2P4lIkfaphxymlrF2dRj4rshfQMFxJ3clkZy.jpeg
//        $file = $request->file('file');
//        if($file){
//            $info = $file->move('./public/hiddensImage/');
//            return $info->getSaveName();
//            if($info){
//                return response()->json([
//                    'file'=>$file,
//                    'isUpload'=>true
//                ],201);
//            }
//        }
    }
}
