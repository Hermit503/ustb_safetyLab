<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * 权限管理
     */
    public function getPermission(){
        $permissions = Permission::with('user')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.permissionList')
                ->with('permissions',$permissions);

        }

        abort(404);
    }
}
