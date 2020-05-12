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
    <script>
        //select改变时
        layui.use(['form'], function(){
            var form = layui.form
                ,layer = layui.layer;
            form.on('select(date)', function(data){
                var date = data.value
                $.ajax({
                    url: '/admin/getLogs',
                    dataType: "text",
                    data:{
                        'date':date
                    },
                    success: function (data) {
                        var result = eval("(" + data + ")");
                        $("#no_log").hide()
                        var i = 0;
                        if(result['success'] == false){
                            $("#no_log").show()
                        }else {
                            $("tbody").empty()
                            for (i = 0; i < result['data']['logs'].length; i++) {
                                var btn_class = "layui-btn";
                                if (result['data']['logs'][i]['type'] == 'ERROR') {
                                    btn_class = "layui-btn layui-btn-danger";
                                } else if (result['data']['logs'][i]['type'] == 'ALERT') {
                                    btn_class = "layui-btn  layui-btn-warm";
                                }

                                $("tbody").append("<tr>" +
                                    "<td>" + result['data']['logs'][i]['timestamp'] + "</td>" +
                                    "<td><button class='" + btn_class + "'>" + result['data']['logs'][i]['type'] + "</button></td>" +
                                    "<td>" + result['data']['logs'][i]['message'] + "</td>" +
                                    "</tr>")
                            }
                        }
                    }
                })
            });
        })
    </script>
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">消息管理</a>
                <a>
                    <cite>日志列表</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <form class="layui-form" style="float: right;" lay-filter="selectDate">

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="date" lay-filter="date">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <colgroup>
                            <col width="150">
                            <col width="200">
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>类型</th>
                            <th>信息</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div style="width: 100%;height:200px;line-height: 200px;text-align: center" id="no_log">
                        <h1>今日没有日志</h1>
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function formatDate(num, format) {
        const formateArr = ['Y', 'M', 'D', 'h', 'm', 's'];
        const returnArr = [];
        const date = new Date(num);
        returnArr.push(date.getFullYear()); // 取得4位数的年份
        returnArr.push(this.formatNumber(date.getMonth() + 1)); // 取得日期中的月份，其中0表示1月，11表示12月
        returnArr.push(this.formatNumber(date.getDate())); // 返回日期月份中的天数（1到31）
        returnArr.push(this.formatNumber(date.getHours())); // 返回日期中的小时数（0到23）
        returnArr.push(this.formatNumber(date.getMinutes())); // 返回日期中的分钟数（0到59）
        returnArr.push(this.formatNumber(date.getSeconds())); // 返回日期中的秒数（0到59）

        for (const i in returnArr) {
            // 判断对象是否含有某个非继承属性
            if ({}.hasOwnProperty.call(returnArr, i)) {
                format = format.replace(formateArr[i], returnArr[i]); // 替换
            }
        }
        return format;
    }

    function formatNumber(n) {
        n = n.toString();
        return n[1] ? n : `0${n}`;
    }
    var timestamp = Date.parse(new Date());
    var tody = formatDate(timestamp, 'Y-M-D')
    //获取今天的日志
    $.ajax({
        url: '/admin/getLogs',
        dataType: "text",
        data:{
            'date':tody
        },
        success: function (data) {
            var result = eval("(" + data + ")");
            console.log(result)
            var i = 0;
            if(result['success'] == false){
                $("#no_log").show()
            }else {
                console.log(result['data']['logs'].length)
                $("tbody").empty()

                for (i = 0; i < result['data']['logs'].length; i++) {
                    console.log(result['data']['logs'][i]['timestamp'])
                    var btn_class = "layui-btn";
                    if (result['data']['logs'][i]['type'] == 'ERROR') {
                        btn_class = "layui-btn layui-btn-danger";
                    } else if (result['data']['logs'][i]['type'] == 'ALERT') {
                        btn_class = "layui-btn  layui-btn-warm";
                    }
                    $("#no_log").hide()
                    $("tbody").append("<tr>" +
                        "<td>" + result['data']['logs'][i]['timestamp'] + "</td>" +
                        "<td><button class='" + btn_class + "'>" + result['data']['logs'][i]['type'] + "</button></td>" +
                        "<td>" + result['data']['logs'][i]['message'] + "</td>" +
                        "</tr>")
                }

                for (i = 0; i < result['data']['available_log_dates'].length; i++) {
                    $("#date").append("<option value='" + result['data']['available_log_dates'][i] + "'>" + result['data']['available_log_dates'][i] + "</option>")
                    if(result['data']['available_log_dates'][i] == tody){
                        flag = 1;
                    }
                }

                if(flag == 1){
                    $('#date').val(tody);
                }else {
                    $('#date').val(result['data']['available_log_dates'][0]);
                }
                layui.form.render('select', 'selectDate');
            }
        }
    })


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
