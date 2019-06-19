<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class UserController extends Controller
{
    /* hzj
     * 2019-06-13
     *  get wx openid,session_key save in users_table
     *
     * */
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
            return response()->json(['status' => '400']);
        } else {
            //判断密码是否正确
            if (!Hash::check($userPwd, $user->password)) {
                return response()->json(['status' => '401']);
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
