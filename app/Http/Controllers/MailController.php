<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail (Request $request) {
        $email = $request->email;
        Mail::raw("您有一条通知，请登录微信小程序查看", function ($message) use ($email) {
            // * 如果你已经设置过, mail.php中的from参数项,可以不用使用这个方法,直接发送
            // $message->from("1182468610@qq.com", "laravel学习测试");
            $message->subject("实验室安全管理系统");
            // 指定发送到哪个邮箱账号
            $message->to($email);
        });

        // 判断邮件是否发送失败
        if(count(Mail::failures())) {
            return '邮件发送失败';
        }

    }
}
