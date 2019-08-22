<?php

namespace App\Http\Controllers;

use App\Notice;
use App\Role;
use App\User;
use Carbon\Carbon;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{

    /**
     * 获取下属列表
     * @param Request $request
     * @return array
     * @author lj
     */
    public function getList(Request $request){
        $unit_id = $request->unit_id;
        $id = $request->user_id;
        $result = [];
        if (strpos($request->roles, '校级管理员') !== false) {
            //校级管理员向所有unit发通知
            $laborary_users = User::all();

            $i = 0;
            for($j = 0 ; $j < count($laborary_users) ; $j++){
                if(Role::where('user_id',$laborary_users[$j]->user_id)->where('role','院级管理员')->get('user_id')->isNotEmpty()){
                    $result[$i]['name'] = $laborary_users[$j]->name;
                    $result[$i]['id'] = $laborary_users[$j]->user_id;
                    $i++;
                }
            }
            return $result;
        }elseif (strpos($request->roles, '院级管理员') !== false) {
            //院级管理员向实验室管理员发通知
            //所属单位一致，身份为实验室管理员

            //同一单位
            $laborary_users = User::where('unit_id',$unit_id)->get();

            $i = 0;
            for($j = 0 ; $j < count($laborary_users) ; $j++){
                if(Role::where('user_id',$laborary_users[$j]->user_id)->where('role','实验室管理员')->get('user_id')->isNotEmpty()){
                    $result[$i]['name'] = $laborary_users[$j]->name;
                    $result[$i]['id'] = $laborary_users[$j]->user_id;
                    $i++;
                }
            }

            return $result;
        }elseif (strpos($request->roles, '实验室管理员') !== false) {
            //实验室管理员向教师发通知
            //所属单位一致，roles为教师，parent_id为自己的id
            $teachers = User::where('unit_id',$unit_id)->where('parent_id',$id)->get();
            $i = 0;
            for ($j = 0 ; $j < count($teachers) ; $j++){
                if(Role::where('user_id',$teachers[$j]->user_id)->where('role','教师')->get('user_id')->isNotEmpty()){
                    $result[$i]['name'] = $teachers[$j]->name;
                    $result[$i]['id'] = $teachers[$j]->user_id;
                    $i++;
                }
            }
            return $result;
        }
    }

    /**
     * 保存图片
     * @param Request $request
     * @return mixed
     * @author lj
     */
    public function saveImage(Request $request)
    {
        Log::info($request);
        $path = $request->file('file')->store('public/noticeImage');
        return Storage::url($path);
    }

    /**
     * 保存文件
     * @param Request $request
     * @return mixed
     * @author lj
     * TODO:当文件有任何格式时上传的文件为.zip
     */
    public function saveFile(Request $request)
    {
        Log::info($request);
        $path = $request->file('file')->store('public/noticeFile');
        return Storage::url($path);
    }

    /**
     * 发送邮件
     * @param Request $request
     * @author lj
     */
    public function sendEmail (Request $request) {
        $users = $request->users;
        $usersList = json_decode($users);
        //$usersList[0];
        for($i=0;$i<sizeof($usersList);$i++){
            $email = User::where('user_id',$usersList[$i])->get('email');
            $emails[$i] = $email[0]['email'];
        }
        for($j=0;$j<count($emails);$j++) {
            $email = $emails[$j];
            echo $email;
            Mail::raw("您有一条通知，请登录微信小程序查看", function ($message) use ($email) {
                // * 如果你已经设置过, mail.php中的from参数项,可以不用使用这个方法,直接发送
                // $message->from("1182468610@qq.com", "laravel学习测试");
                $message->subject("实验室安全管理系统");
                // 指定发送到哪个邮箱账号
                $message->to($email);
            });
        }
    }

    /**
     * 保存表单数据
     * @param Request $request
     * @return string
     * @author lj
     */
    public function saveData(Request $request){
        $title = $request->title;
        $users = $request->users;
        $comment = $request->comment;
        $build_id = $request->build_id;


//        $pictures = implode(',',$request->pictures);
        $pictures = $request->pictures;
        $file = $request->file;

        $notice = new Notice();
        $notice->title = $title;
        $notice->users = $users;
        $notice->comment = $comment;
        $notice->build_id = $build_id;
        $notice->pictures = $pictures;
        $notice->file = $file;

        $notice->save();

        return "上传成功";

    }

    /**
     * 获取某人上传的通知
     * @param Request $request
     * @return mixed
     * @author lj
     */
    public function getOwnerList(Request $request){
        $user_id = $request->user_id;
        $lists = Notice::where('build_id',$user_id)->orderBy('created_at','desc') ->paginate(15);
        return $lists;
    }

    /**
     * 比较时间返回几天前几月前...
     * @param $time  如2019-08-18 20:51:56
     * @return mixed|string
     * @throws \Exception
     * @author lj
     */
    private function getTimeDifference($time){
        //正数为几天后，负数为几天前，false代表区分正负，true代表取绝对值
        $int = (new Carbon)->diffInSeconds ($time, true);
        $retArr = array('刚刚','秒前','分钟前','小时前','天前','月前','年前');

        switch($int){
            case $int == 0://刚刚
                $text = $retArr[0];
                break;
            case $int < 60:// 几秒前
                $text = $int.$retArr[1];
                break;
            case $int < 3600://几分钟前
                $text = floor($int / 60).$retArr[2];
                break;
            case $int < 86400://几小时前
                $text = floor($int / 3600).$retArr[3];
                break;
            case $int < 2592000: //几天前
                $text = floor($int / 86400).$retArr[4];
                break;
            case $int < 31536000: //几个月前
                $text = floor($int / 2592000).$retArr[5];
                break;
            default : //几年前
                $text = floor($int / 31536000).$retArr[6];
        }

        return $text;
    }

    /**
     * 调用getTimeDifference获取created_time的。。。。
     * @param Request $request
     * @return array
     * @author lj
     * @throws \Exception
     */
    public function getTime(Request $request){
        $user_id = $request->user_id;
        $time_list = Notice::where('build_id',$user_id)->paginate(15);
        $result = [];
        $i = 0;
        foreach($time_list as $time){
            $carbon = $time['created_at'];
            $result[$i] = $this->getTimeDifference($carbon);
            $i++;
        }
        return $result;
    }

    /**
     * 获取某条通知的具体内容
     * @param Request $request
     * @return mixed
     * @author lj
     */
    public function getOneNotice(Request $request){
        $id = $request->id;
        $result = Notice::where('id',$id)->get();
        $names = $this->getNames(json_decode($result[0]['users'])) ;
        $result[0]['pictures'] = json_decode($result[0]['pictures']);
        $result[0]['users'] = $names;
        return $result;
    }

    /**
     * 查询人员姓名
     * @param $names
     * @return array
     * @author lj
     */
    public function getNames($names){
        $results = [];
        $length = count($names);

        for($i = 0 ; $i < $length ; $i++){
            $result = User::where('user_id',$names[$i])->get();
            $results[$i] = $result[0]['name'];
        }
        return $results;
    }


}
