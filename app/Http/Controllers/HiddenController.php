<?php

namespace App\Http\Controllers;

use App\Hidden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HiddenController extends Controller
{
    public function addHidden(Request $request)
    {
        $hidden = new Hidden();
        $hidden->user_id=$request->user_id;
        $hidden->position=$request->position;
        $hidden->title=$request->title;
        $hidden->detail=$request->detail;
        $hidden->image=$request->image;
        $hidden->occurrence_time=now();
        $hidden->save();
        Log::info($request);
        return response('',201);
    }
}
