<?php

namespace App\Policies;

use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class LoginPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function Admin(){
        $roles = Role::where('user_id',Auth::user()->user_id)->get('role');
        foreach($roles as $role){
            if($role['role'] === '超级管理员'){
                return true;
            }
        }
        return false;
    }
}
