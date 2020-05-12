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
        <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
        <script src="../lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="../js/xadmin.js"></script>
        <script src="../lib/layui/lay/modules/laydate.js"></script>
        <script src="/js/common.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    </head>

    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>巡检记录</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>单位</th>
                                        <th>巡检记录</th>
                                        <th>巡检时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($InspectionLogs as $InspectionLog)
                                    <tr>
                                        <td>{{$InspectionLog['id']}}</td>
                                        <td>{{$InspectionLog['unit']['unit_name']}}</td>
                                        <td>{{$InspectionLog['log']}}</td>
                                        <td>{{$InspectionLog['created_at']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$InspectionLogs->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </body>
    <script>layui.use(['laydate', 'form'],
        function() {
            var laydate = layui.laydate;
            var today = getToday();
            //执行一个laydate实例
            laydate.render({
                elem: '#new_storage_time', //指定元素
                trigger: 'click',
                value: today
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#new_scrap_time', //指定元素
                trigger: 'click',
                value: today
            });

            laydate.render({
                elem: '#new_fix_time', //指定元素
                trigger: 'click',
                value: today
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#modify_storage_time', //指定元素
                trigger: 'click'
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#modify_scrap_time', //指定元素
                trigger: 'click'
            });

            laydate.render({
                elem: '#modify_fix_time', //指定元素
                trigger: 'click'
            });
        });
    </script>

</html>
