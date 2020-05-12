<?php

namespace App\Http\Controllers;

use App\Chemical;
use App\ChemicalsNotice;
use App\Notice;
use App\Role;
use App\User;
use Carbon\Carbon;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class NoticeController extends Controller
{

    /**
     * 获取下属列表
     * @param Request $request
     * @return array
     * @author lj
     */
    public function getList(Request $request)
    {

        $unit_id = $request->unit_id;
        $id = $request->user_id;
        $result = [];
        if (strpos($request->roles, '校级管理员') !== false) {
            //校级管理员向所有unit发通知
            $laborary_users = User::all();

            $i = 0;
            for ($j = 0; $j < count($laborary_users); $j++) {
                if (Role::where('user_id', $laborary_users[$j]->user_id)->where('role', '院级管理员')->get('user_id')->isNotEmpty()) {
                    $result[$i]['name'] = $laborary_users[$j]->name;
                    $result[$i]['id'] = $laborary_users[$j]->user_id;
                    $i++;
                }
            }
            return $result;
        } elseif (strpos($request->roles, '院级管理员') !== false) {
            //院级管理员向实验室管理员发通知
            //所属单位一致，身份为实验室管理员

            //同一单位
            $laborary_users = User::where('unit_id', $unit_id)->get();

            $i = 0;
            for ($j = 0; $j < count($laborary_users); $j++) {
                if (Role::where('user_id', $laborary_users[$j]->user_id)->where('role', '实验室管理员')->get('user_id')->isNotEmpty()) {
                    $result[$i]['name'] = $laborary_users[$j]->name;
                    $result[$i]['id'] = $laborary_users[$j]->user_id;
                    $i++;
                }
            }

            return $result;
        } elseif (strpos($request->roles, '实验室管理员') !== false) {
            //实验室管理员向教师发通知
            //所属单位一致，roles为教师，parent_id为自己的id
            // Log::info($request );
            $teachers = User::where('unit_id', $unit_id)->where('title', '教师')->get();
            $i = 0;
            for ($j = 0; $j < count($teachers); $j++) {
                if (Role::where('user_id', $teachers[$j]->user_id)->where('role', '教师')->get('user_id')->isNotEmpty()) {
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
        $path = $request->file('file')->store('public/noticeFile');
        return Storage::url($path);
    }

    /**
     * 发送邮件
     * @param Request $request
     * @author lj
     */
    public function sendEmail(Request $request)
    {
        $users = $request->users;
        $usersList = json_decode($users);
        //$usersList[0];
        for ($i = 0; $i < sizeof($usersList); $i++) {
            $email = User::where('user_id', $usersList[$i])->get('email');
            $emails[$i] = $email[0]['email'];
        }
        for ($j = 0; $j < count($emails); $j++) {
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
    public function saveData(Request $request)
    {
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

        return "下发成功";
    }
    /**
     * 获取已收到消息的人员姓名
     * @param Request $request
     * @return mixed
     * @author lj
     * @time 2019-09-10
     */
    public function getNoticeReceivedUsers($received_users)
    {
        $names = [];
        //将0062 0061转为数组
        $users = explode(" ", $received_users);

        foreach ($users as $item) {
            $name = User::where('user_id', $item)->get(['user_id', 'name']);
            array_push($names, $name[0]);
        }

        return $names;
    }

    /**
     * 获取某人上传的通知
     * @param Request $request
     * @return mixed
     * @author lj
     */
    public function getOwnerList(Request $request)
    {
        $user_id = $request->user_id;
        $lists = Notice::where('build_id', $user_id)->orderBy('created_at', 'desc')->paginate(15);
        return $lists;
    }

    /**
     * 比较时间返回几天前几月前...
     * @param $time  如2019-08-18 20:51:56
     * @return mixed|string
     * @throws \Exception
     * @author lj
     */
    private function getTimeDifference($time)
    {
        //正数为几天后，负数为几天前，false代表区分正负，true代表取绝对值
        $int = (new Carbon)->diffInSeconds($time, true);
        $retArr = array('刚刚', '秒前', '分钟前', '小时前', '天前', '月前', '年前');

        switch ($int) {
            case $int == 0: //刚刚
                $text = $retArr[0];
                break;
            case $int < 60: // 几秒前
                $text = $int . $retArr[1];
                break;
            case $int < 3600: //几分钟前
                $text = floor($int / 60) . $retArr[2];
                break;
            case $int < 86400: //几小时前
                $text = floor($int / 3600) . $retArr[3];
                break;
            case $int < 2592000: //几天前
                $text = floor($int / 86400) . $retArr[4];
                break;
            case $int < 31536000: //几个月前
                $text = floor($int / 2592000) . $retArr[5];
                break;
            default: //几年前
                $text = floor($int / 31536000) . $retArr[6];
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
    public function getTime(Request $request)
    {
        $user_id = $request->user_id;
        $time_list = Notice::where('build_id', $user_id)->orderBy('created_at', 'desc')->paginate(15);
        $result = [];
        $i = 0;
        foreach ($time_list as $time) {
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
    public function getOneNotice(Request $request)
    {
        $id = $request->id;
        $result = Notice::where('id', $id)->get();
        $names = $this->getNames(json_decode($result[0]['users']));
        $result[0]['pictures'] = json_decode($result[0]['pictures']);
        $result[0]['users'] = $names;


        $received_users = $result[0]['received_users'];
        if ($received_users == '' || $received_users == null) {
            $result[0]['received_users'] = [];
        } else {
            $result[0]['received_users'] = $this->getNoticeReceivedUsers($received_users);
        }

        return $result;
    }

    /**
     * 查询人员姓名
     * @param $names
     * @return array
     * @author lj
     */
    public function getNames($names)
    {
        $results = [];
        $length = count($names);

        for ($i = 0; $i < $length; $i++) {
            $result = User::where('user_id', $names[$i])->get();
            $results[$i] = $result[0]['name'];
        }
        return $results;
    }



    /**
     * 获取消息列表
     * @param Request $request
     * @return array
     * @author lj
     * @time 2019-8-25
     * 添加缓存 有效期一天
     * @author lj
     * @time 2019-09-15
     */
    public function getAllMessage(Request $request)
    {
        $cacheName = "allMessage".$request->user_id;
        if ($value = Cache::get($cacheName)) {
            return $value;
        } else {
            //最终结果
            $result = [];

            //未处理
            $chemicalList = ChemicalsNotice::where([
                ["user_id_2", $request->user_id],
                ["isConfirm_2", "0"],
                ["receive", "0"]
            ])->get();
            foreach ($chemicalList as $item) {
                $item['noticeType'] = "chemical";
                array_push($result, $item);
            }

            //1已处理2被驳回
            $chemicalDisagreeList = ChemicalsNotice::where([
                ["user_id_1", $request->user_id],
                ["isConfirm_2", "0"],
                ["receive", "1"]
            ])->get();
            foreach ($chemicalDisagreeList as $item) {
                $item['noticeType'] = "chemical";
                array_push($result, $item);
            }

            $tmplist = Notice::all();
            $users = [];
            $noticeList = [];
            foreach ($tmplist as $item) {
                $users[$item['id']] = json_decode($item['users']);
            }
            //遍历users
            $count = 0;
            foreach ($users as $key => $user) {
                for ($i = 0; $i < count($user); $i++) {
                    if ($user[$i] == $request->user_id) {
                        $tmp = Notice::where('id', $key)->get();
                        //已收到信息的人
                        $received_users = explode(" ", $tmp[0]['received_users']);
                        $tmpName = User::where('user_id', $tmp[0]['build_id'])->get('name')->first();
                        $tmp[0]['name'] = $tmpName['name'];
                        $tmp[0]['noticeType'] = "notice";
                        $tmp_received = [];
                        foreach ($received_users as $key => $received_user) {
                            //如果相等的话表示已提交，不push
                            if ($received_user != $request->user_id) {
                                array_push($tmp_received, $received_user);
                            } else { }
                        }
                        if (count($tmp_received) == count($received_users)) {
                            array_push($noticeList, $tmp[0]);
                        }
                    }
                }
            }
            foreach ($noticeList as $item) {
                array_push($result, $item);
            }

            $len = count($result);
            for ($k = 0; $k <= $len; $k++) {
                for ($j = $len - 1; $j > $k; $j--) {

                    if ($result[$j]['created_at']->gt($result[$j - 1]['created_at'])) {
                        //                    echo $result[$j]['created_at']."比".$result[$j-1]['created_at']."大<br>";
                        $temp = $result[$j];
                        $result[$j] = $result[$j - 1];
                        $result[$j - 1] = $temp;
                    } else {
                        //                    echo $result[$j]['created_at']."比".$result[$j-1]['created_at']."小<br>";
                    }
                }
            }
            $value = Cache::put($cacheName, $result, 60*60*24);
        }

        return $result;
    }

    /**
     * 获取某时间段的消息
     * @param Request $request
     * @return array
     * @author lj
     * @time 2019-08-29
     * 加入缓存 有效期一天
     * @author lj
     * @time 2019-09-15
     */
    public function getHistoryMessage(Request $request)
    {
        $cacheName = 'historyMessage'.$request->user_id;
        if ($value = Cache::get($cacheName)) {
            return $value;
        } else {
            //传入的参数到月份 如2019-09
            $startDate = $request->startDate."-01";
            $endDate = $request->endDate."-31";

            $result = [];
            $chemicalList = ChemicalsNotice::where([
                ["user_id_2", $request->user_id],
                ["receive", "!=", "0"]
            ])
                ->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $endDate)
                ->get();
            foreach ($chemicalList as $item) {
                $item['noticeType'] = "chemical";
                //年月日
                $item['time'] = $item['updated_at']->format('Y-m-d');
                array_push($result, $item);
            }

            //被驳回已了解的
            $chemicaldisagreeList = ChemicalsNotice::where([
                ["user_id_1", $request->user_id],
                ["isConfirm_2", "0"],
                ["receive", "2"]
            ])
                ->whereDate('updated_at', '>=', $startDate)
                ->whereDate('updated_at', '<=', $endDate)
                ->get();
            foreach ($chemicaldisagreeList as $item) {
                $item['noticeType'] = "chemical";
                array_push($result, $item);
            }

            $tmplist = Notice::all();
            $users = [];
            $noticeList = [];
            foreach ($tmplist as $item) {
                //将0062 0061转为数组
                $users[$item['id']] = explode(" ", $item['received_users']);
            }
            //遍历users
            $count = 0;
            foreach ($users as $key => $user) {
                for ($i = 0; $i < count($user); $i++) {
                    if ($user[$i] == $request->user_id) {
                        $tmp = Notice::where('id', $key)
                            ->whereDate('updated_at', '>=', $startDate)
                            ->whereDate('updated_at', '<=', $endDate)
                            ->get();
                        if (count($tmp) != 0) {
                            array_push($noticeList, $tmp[0]);
                        }
                    }
                }
            }
            foreach ($noticeList as $item) {
                $item['noticeType'] = "notice";
                //年月日
                $item['time'] = $item['updated_at']->format('Y-m-d');
                $name = [$item['build_id']];
                $item['name'] = $this->getNames($name);
                array_push($result, $item);
            }

            $len = count($result);
            for ($k = 0; $k <= $len; $k++) {
                for ($j = $len - 1; $j > $k; $j--) {

                    if ($result[$j]['updated_at']->gt($result[$j - 1]['updated_at'])) {
                        //                    echo $result[$j]['created_at']."比".$result[$j-1]['created_at']."大<br>";
                        $temp = $result[$j];
                        $result[$j] = $result[$j - 1];
                        $result[$j - 1] = $temp;
                    } else {
                        //                    echo $result[$j]['created_at']."比".$result[$j-1]['created_at']."小<br>";
                    }
                }
            }
            $value = Cache::put($cacheName, $result, 60*60*24);
        }

        return $result;
    }

    /**
     * 清除所有消息的缓存
     */
    public function clearAllCache(Request $request)
    {
        $user_id = $request->user_id;

        if (Cache::has('allMessage'.$user_id)) {
            Cache::forget('allMessage'.$user_id);
        }else{
            return "没有这个缓存";
        }
    }

    /**
     * 清除历史消息的缓存
     */
    public function clearHistoryCache(Request $request)
    {
        $user_id = $request->user_id;

        if (Cache::has('historyMessage'.$user_id)) {
            Cache::forget('historyMessage'.$user_id);
        }else{
            return "没有这个缓存";
        }
    }

    /**
     * 收到消息之后确认收到
     * @param Request $request
     * @return string
     * @uses lj
     * @time 2019-08-26
     */
    public function receiveNotice(Request $request)
    {
        $id = $request->id; //notice表的id
        $user = $request->user; //收到通知用户的id
        $tmp_received_users = Notice::where('id', $id)->get('received_users');

        $received_users = $tmp_received_users[0]['received_users'];
        if ($received_users != null) {
            $received_users = $received_users . ' ' . $user; // 0001 0002 0003
        } else {
            $received_users = $user;
        }

        Notice::where('id', $id)->update(['received_users' => $received_users]);

        return '提交成功';
    }
}
