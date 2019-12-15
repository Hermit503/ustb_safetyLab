<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\Equipments;
use App\Hidden;
use App\Laboratory;
use App\Unit;
use App\User;
use App\Notice;
use App\Permission;
use App\Role;
use Illuminate\Support\Facades\Gate;
use EasyWeChat\Kernel\Messages\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * 数据统计
     */
    public function getWelcome(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.welcome',[
                'unitCount' =>Unit::all()->count(),
                'userCount'=>User::all()->count(),
                'laraboryCount' => Laboratory::all()->count(),
                'equipmentCount' => Equipments::all()->count(),
                'chemicalCount' => Chemical::all()->count(),
                'noticeCount' => Notice::all()->count(),
                'hiddenCount' => Hidden::all()->count()
                ]);
        }
        abort(404);
    }

    /**
     * 获取单位列表
     */
    public function getUnitList(){
        $units = Unit::paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.unitList')
         ->with('units',$units);
        }
        
        abort(404);
    }

    /**
     * 获取实验室列表
     */
    public function getLarboriesList(){
        $larbories = Laboratory::join('units','laboratories.unit_id','=','units.id')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.larboriesList')
            ->with('larbories',$larbories);
        }

        abort(404);
    }

    /**
     * 获取实验室设备列表
     */
    public function getEquipmentsList(){
        $equipments = Equipments::join('laboratories','laboratory_id','=','laboratories.id')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.equipmentsList')
            ->with('equipments',$equipments);
        }

        abort(404);
    }

    /**
     * 获取药品列表
     */
    public function getChemicalsList(){
        $chemicals = Chemical::with('laboratories')
                     ->with('unit')
                     ->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.chemicalsList')
            ->with('chemicals',$chemicals);
        }

        abort(404);
    }

    /**
     * 获取用户列表
     */
    public function getUserList(){
        // $users = User::paginate(10)->with('unit')->get();
        $users = User::join('units','users.unit_id','=','units.id')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.userList')
            ->with('users',$users);
        }

        abort(404);
    }

    /**
     * 获取管理员列表
     */
    public function getAdmin(){
        $admins = User::join('units','users.unit_id','=','units.id')->where('title','=','admin')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.adminList')
            ->with('admins',$admins);
            // return $admins;
        }

        abort(404);
    }

    /**
     * 隐患列表
     */
    public function getHiddensList(){
        $hiddens = Hidden::with('user')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.hiddensList')
            ->with('hiddens',$hiddens);
        }

        abort(404);
    }

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
        $messages = Notice::join('users','user_id','build_id')->paginate(10);
        
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

    /**
     * 日志列表
     */
    public function getLogList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.logList');
        }

        abort(404);
    }

    /**
     * 试题管理
     */
    public function getPaperList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.papersList');
        }

        abort(404);
    }

    /**
     * 成绩管理
     */
    public function getScoreList(){
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.scoreList');
        }

        abort(404);
    }

    /**
     * 角色管理
     */
    public function getRolesList(){
        $roles = Role::join('users','roles.user_id','users.user_id')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.roleList')
            ->with('roles',$roles);
        }

        abort(404);
    }

    /**
     * 权限管理
     */
    public function getPermission(){
        $permissions = Permission::join('users','permissions.user_id','users.user_id')->paginate(10);
        if(Gate::allows('access-admin',Auth::user())){
            return view('admin.permissionList')
            ->with('permissions',$permissions);
            
        }

        abort(404);
    }

    /**
     * 自定义登录
     */

    public function login(Request $request){
        //获取表单输入
        $user_id = $request->input('user_id');
        $password = $request->input('password');
        //获取用户信息
        $user = User::where('user_id',$user_id)->first();
        //数据库中取的密码
        $pass = $user->password;
        $str = Hash::make($pass);
        if (Hash::check($password, $pass)) {
            Auth::login($user);
            return redirect('/admin/index');
        }else{
            return redirect('/admin');
        }
    }

}
