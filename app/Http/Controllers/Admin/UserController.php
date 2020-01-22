<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 获取用户列表
     */
    public function getUserList(){
        // $users = User::paginate(10)->with('unit')->get();
        $users = User::with('unit')->where('isDelete','0')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.userList')
                ->with('users',$users);
        }

        abort(404);
    }

    /**
     * 添加新用户
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-22
     */
    public function newUser(Request $request){
        $name = $request->name;
        $user_id = $request->user_id;
        $sex = $request->sex;
        $phone_number = $request->phone_number;
        $title = $request->title;
        $unit_id = $request->unit_id;
        $email = $request->email;
        $password = $request->password;
        $parent_id = $request->parent_id;

        $user = new User;

        $user->name = $name;
        $user->user_id = $user_id;
        $user->sex = $sex;
        $user->phone_number = $phone_number;
        $user->title = $title;
        $user->unit_id = $unit_id;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->parent_id = $parent_id;

        if(Gate::allows('access-admin',Auth::user())) {
            $user->save();
            return "添加成功";
        }

        abort(404);
    }

    /**
     * 修改用户信息
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-22
     */
    public function modifyUser(Request $request){
        $user = User::where('user_id', $request->user_id)->first();
        $user->name = $request->name;
        $user->sex = $request->sex;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->unit_id = $request->unit_id;
        $user->parent_id = $request->parent_id;
        $user->title = $request->title;

        if(Gate::allows('access-admin',Auth::user())) {
            $user->save();
            return "修改成功";
        }
        abort(404);
    }

    /**
     * 删除用户
     * @param Request $request
     * @return string
     * @author lj
     * @time 2020-01-22
     */
    public function deleteUser(Request $request){
        if(Gate::allows('access-admin',Auth::user())) {
            User::where('user_id', '=', $request->user_id)
                ->update(['isDelete' => '1']);
            return "删除成功";
        }
        abort(404);
    }
}
