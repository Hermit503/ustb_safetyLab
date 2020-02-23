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
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>隐患列表</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">

                {{--                        <div class="layui-card-header">--}}
                {{--                            <button class="layui-btn" onclick="xadmin.open('添加用户','./order-add.html',800,600)">--}}
                {{--                                <i class="layui-icon"></i>添加</button></div>--}}
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>工号</th>
                            <th>姓名</th>
                            <th>类型</th>
                            <th>位置</th>
                            <th>标题</th>
                            <th>详情</th>
                            <th>发现时间</th>
                            <th>状态</th>
                            {{--                                        <th>操作</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($hiddens as $hidden)
                            <tr>
                                <td>{{$hidden['user_id']}}</td>
                                <td>{{$hidden['user']['name']}}</td>
                                <td>{{$hidden['type']}}</td>
                                <td>{{$hidden['position']}}</td>
                                <td>{{$hidden['title']}}</td>
                                <td>{{$hidden['detail']}}</td>
                                <td>{{$hidden['occurrence_time']}}</td>
                                <td>
                                    @if($hidden['isSolve'])
                                        已解决
                                    @endif
                                    @if(!$hidden['isSolve'])
                                        未解决
                                    @endif

                                </td>
                                {{--                                        <td class="td-manage">--}}
                                {{--                                            <a title="查看" onclick="xadmin.open('编辑','order-view.html')" href="javascript:;">--}}
                                {{--                                                <i class="layui-icon">&#xe63c;</i></a>--}}
                                {{--                                            <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">--}}
                                {{--                                                <i class="layui-icon">&#xe640;</i></a>--}}
                                {{--                                        </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        {{$hiddens->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    layui.use(['laydate', 'form'],
        function() {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });

</script>

</html>
