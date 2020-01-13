<?php

namespace App\Http\Controllers\Admin;

use App\Notice;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    /**
     * 人员的数组
     */
    public function getUser(Array $array){
        $users = [];
        for($i = 0 ; $i < sizeof($array) ; $i++){
            $user = User::where('user_id',$array[$i])->get();
            $username = $user[0]['name'];
            array_push($users,$username);
        }
        return $users;
    }

    /**
     * 收到消息的人员
     */
    public function getReceivedUser(String $str = ""){
        $names = [];
        if($str == ""){
            return "暂无";
        }else{
            //将0062 0061转为数组
            $users = explode(" ", $str);

            foreach ($users as $item) {
                $name = User::where('user_id', $item)->get(['name']);
                array_push($names, $name[0]);
            }

            return $names;
        }
    }

    /**
     * 消息列表
     */
    public function getMessagesList(){
        $messages = Notice::with('user')->paginate(10);

        if(Gate::allows('access-admin',Auth::user())){

            foreach($messages as $key => $message){
                $users = $this->getUser(json_decode($message['users']));
                $message['users'] = $users;
                if($message['received_users'] != null){
                    $message['received_users'] = $this->getReceivedUser($message['received_users']);
                }else{
                    $message['received_users'] = [];
                }
            }
            return view('admin.messagesList')
                ->with('messages',$messages);
            // return $messages;
        }

        abort(404);
    }
}
