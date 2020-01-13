<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * 角色管理
     */
    public function getRolesList(){
        $roles = Role::with('user')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.roleList')
                ->with('roles',$roles);
        }

        abort(404);
    }
}
