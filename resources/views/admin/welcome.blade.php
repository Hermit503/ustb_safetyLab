<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="../css/xadmin.css">
        <script src="../lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="../js/xadmin.js"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <blockquote class="layui-elem-quote">欢迎管理员：
                                <span class="x-red">
                                {{Auth::user()->name}}
                                </span>！当前时间:
                                @php
                                    echo date('Y-m-d h:i:s', time());
                                @endphp
                            </blockquote>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">数据统计</div>
                        <div class="layui-card-body ">
                            <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>单位数</h3>
                                        <p>
                                            <cite>{{$unitCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>用户数</h3>
                                        <p>
                                            <cite>{{$userCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>实验室数</h3>
                                        <p>
                                            <cite>{{$laraboryCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>设备数</h3>
                                        <p>
                                            <cite>{{$equipmentCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>药品数</h3>
                                        <p>
                                            <cite>{{$chemicalCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>通知数</h3>
                                        <p>
                                            <cite>{{$noticeCount}}</cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-md2 layui-col-xs6">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>隐患数</h3>
                                        <p>
                                            <cite>{{$hiddenCount}}</cite></p>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">系统信息</div>
                        <div class="layui-card-body ">
                            <table class="layui-table">
                                <tbody>
                                    <tr>
                                        <th>服务器IP</th>
                                        <td>{{Sysinfo::ip()}}</td></tr>
                                    <tr>
                                        <th>操作系统</th>
                                        <td>{{Sysinfo::server()}}</td></tr>
                                    <tr>
                                        <th>CPU详情</th>
                                        <td>{{Sysinfo::cpu()}}</td></tr>
                                    <tr>
                                        <th>内存信息</th>
                                        <td>{{Sysinfo::memory()}}</td></tr>
                                    <tr>
                                        <th>PHP版本</th>
                                        <td>{{Sysinfo::php()}}</td></tr>
                                    <tr>
                                        <th>MySQL版本</th>
                                        <td>{{Sysinfo::mysql()}}</td></tr>
                                    <tr>
                                        <th>最大上传文件大小</th>
                                        <td>{{Sysinfo::upload_max_filesize()}}</td></tr>
                                    <tr>
                                        <th>Laravel版本</th>
                                        <td>{{Sysinfo::laraver()}}</td></tr>
                                    <tr>
                                        <th>时区信息</th>
                                        <td>{{Sysinfo::timezone()}}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">开发团队</div>
                        <div class="layui-card-body ">
                            <table class="layui-table">
                                <tbody>
                                    <tr>
                                        <th>版权所有</th>
                                        <td>实验室安全管理开发团队
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>开发者</th>
                                        <td>实验室安全管理开发团队</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <style id="welcome_style"></style>
                
        </div>
        </div>
    </body>
</html>