<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="../css/xadmin.css">
    <script src="../lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="../js/xadmin.js"></script>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script>
        function openModifyModak(that){

            var name = $(that).attr("data-name")
            var userId = $(that).attr("data-userId")
            var score = $(that).attr("data-score")
            var degree = $(that).attr("data-degree")


            $("[id='modify_name']").val(name);//向模态框中赋值
            $("[id='modify_user_id']").val(userId);
            $("[id='modify_degree']").val(degree);
            $("[id='modify_score']").val(score);

            layui.use(['layer'],function () {
                var layer = layui.layer,$=layui.$;
                layer.open({
                    type:1,//类型
                    area:['1000px','400px'],//定义宽和高
                    title:'修改用户信息',//题目
                    shadeClose:true,//点击遮罩层关闭
                    content: $('#modifyModel'),//打开的内容
                    end:function (res) {
                        $("#modifyModel").css("display",'none');
                    }
                });
                getModifyLead();
            })
            layui.form.on('select(modify_unit_id)',function() {
                getModifyLead();
            })
            layui.form.render('select', 'modify');
        }

        function modifyScore(){
            let name = $("input[name='modify_name']").val()
            let user_id = $("input[name='modify_user_id']").val()
            let degree = $("input[name='modify_degree']").val()
            let score = $("input[name='modify_score']").val()

            $.ajax({
                url: '/admin/modifyScore',
                method: "PUT",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'name':name,
                    'user_id':user_id,
                    'score':score,
                    'degree':degree,
                },
                dataType: "text",
                success: function (data) {
                    window.location()
                },
            });
        }
    </script>
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>角色列表</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
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
                            <th>工号</th>
                            <th>姓名</th>
                            <th>成绩</th>
                            <th>剩余次数</th>
                            <th>所属单位</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{$user['id']}}</td>
                                <td>{{$user['user_id']}}</td>
                                <td>{{$user['name']}}</td>
                                <td>{{$user['exam_result']}}</td>
                                <td>{{$user['residue_degree']}}</td>
                                <td>{{$user['unit']['unit_name']}}</td>
                                <td class="td-manage">
                                    <a title="修改" onclick="openModifyModak(this)" href="javascript:;"
                                       data-name="{{$user['name']}}"
                                       data-userId="{{$user['user_id']}}"
                                       data-score="{{$user['exam_result']}}"
                                       data-degree="{{$user['residue_degree']}}"
                                    >
                                        <i class="layui-icon">&#xe63c;</i></a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        {{$users->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--修改用户信息-->
<div id="modifyModel" style="display: none;">
    <div class="layui-container">
        <br><br>
        <div class="layui-row">
            <div class="layui-col-md10 layui-col-md-offset1">
                <form class="layui-form" lay-filter="modify">
                    @csrf
                    <div class="layui-form-item">
                        <label class="layui-form-label">工号</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_user_id" id="modify_user_id" required  lay-verify="required" disabled placeholder="请输入姓名" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_name" id="modify_name" required  lay-verify="required" disabled placeholder="请输入姓名" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">考试成绩</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_score" id="modify_score" required  lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">考试次数</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_degree" id="modify_degree" required  lay-verify="required"  autocomplete="off" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" onclick="modifyScore()">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use('form', function(){
        var form = layui.form;
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
    });
</script>
</body>
</html>
