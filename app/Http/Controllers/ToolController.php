<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getUserRole()
    {
        $user = User::where('unit_id','0')->has('roles')->get();
        dd($user);
    }
}
