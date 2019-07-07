<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Unit;
use function foo\func;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class UserController extends Controller
{

    /**
     * wx openid,session_key save in users_table
     * @author hzj
     * @time 2019-06-13
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxLogin(Request $request)
    {
        $code = $request->code;
        $miniProgram = \EasyWeChat::miniProgram();
        //获取openid session_key
        $data = $miniProgram->auth->session($code);
        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code已过期或不正确');
        }
        $userid = $request->userid;
        $userPwd = $request->userpwd;
        $openId = $data['openid'];
        $sessionKey = $data['session_key'];
        $nickname = $request->nickname;
        //判断是否存在工号
        $user = User::where('user_id', $userid)->first();
        if (!$user) {
            return response()->json([],400);
        } else {
            //判断密码是否正确
            if (!Hash::check($userPwd, $user->password)) {
                return response()->json([],401);
            } else {
                //覆盖用户之前登录信息
                $user->open_id = $openId;
                $user->session_key = $sessionKey;
                $user->nickname = $nickname;
                $user->save();
                //创建token 设置有效期
                $createToken = $user->createToken($user->open_id);
                $createToken->token->expires_at = Carbon::now()->addDays(30);
                $createToken->token->save();
                $token = $createToken->accessToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => "Bearer",
                    'expires_in' => Carbon::now()->addDays(30),
                    'data' => $user,
                    'roles'=>$user->roles,
                    'permissions'=>$user->permissions,
                ],200);
            }
        }

    }

    /**
     * get user information
     * @author hzj
     * @date 2019-6-16
     * @param Request $request
     * @return UserCollection
     */
    public function getUser(Request $request)
    {
        if (strpos($request->role, '校级管理员')!==false) {
            return User::with('unit')->get();

        } elseif (strpos($request->role, '院级管理员') !==false) {
            return User::where('unit_id', $request->unit_id)
                ->where('parent_id',$request->id)
                ->with('unit')
                ->get()->toArray();
        }
    }


    /***
     * wx add user
     * @author hzj
     * @date 2019-6-25
     * @param Request $request
     */
    public function addUser(Request $request)
    {
        //
    }

    /**
     * 更新用户所需要的信息
     * @author hzj
     * @date 2019-07-05
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOneUser(Request $request)
    {
        $user_id = $request->id;
        $user = User::where('user_id', $user_id)
            ->with('unit')
            ->with('permissions')
            ->first();
        $units = Unit::all();
        return response()->json([
            'userInfo'=>$user,
            'units'=>$units
        ],200);
    }

    /**
     * 人员信息修改
     * @author hzj
     * @date 2019-07-06
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        $user = User::where('user_id', $request->user_id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        $user->unit_id = $request->unit_id;
        $user->save();
//        Log::info($user);
        Log::alert('有数据更新 ',$request->all());
        return response()->json([],200);
    }







    /* @ author lj
     * @ time 2019-06-13
     *  delete wx openid,session_key in users_table
     *
     * */
    public function wxLogout(Request $request){
        $id = $request->id;
        User::where('id',$id)
            ->update(['open_id' => null , 'session_key' => null]);
    }
}
