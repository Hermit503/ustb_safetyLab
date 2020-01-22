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
        <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
        <script type="text/javascript" src="../js/xadmin.js"></script>

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            //添加管理员
            function openAddModak(){

                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'添加管理员',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#addModel'),//打开的内容
                        end:function (res) {
                            $("#addModel").css("display",'none');
                        }
                    });
                })
                layui.form.render('select', 'add');
            }
            //删除管理员
            function openDeleteModak(that){
                var id = $(that).attr("data-userId")

                $("[id='deleteUserId']").val(id);//向模态框中赋值
                $("[id='deleteMessage']").text("确认删除"+id+"的管理权限?");
                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['360px','200px'],//定义宽和高
                        title:'确认删除？',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#deleteModel'),//打开的内容
                        end:function (res) {
                            $("#deleteModel").css("display",'none');
                        }
                    });
                })
                layui.form.render(null, 'delete');
            }
        </script>
    </head>

    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>人员列表</cite></a>
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
                            <button class="layui-btn" onclick="openAddModak()">
                                <i class="layui-icon"></i>添加</button></div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>工号</th>
                                        <th>姓名</th>
                                        <th>手机号</th>
                                        <th>邮箱</th>
                                        <th>所属单位</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                    <tr>
                                        <td>{{$admin['id']}}</td>
                                        <td>{{$admin['user_id']}}</td>
                                        <td>{{$admin['name']}}</td>
                                        <td>{{$admin['phone_number']}}</td>
                                        <td>{{$admin['email']}}</td>
                                        <td>{{$admin['unit']['unit_name']}}</td>
                                        <td class="td-manage">
                                            <a title="删除" onclick="openDeleteModak(this)" data-userId="{{$admin['user_id']}}" href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $.ajax({
                url:'/admin/allUsers',
                data:{},
                dataType:"text",
                success:function(data){
                    var result = eval("("+data+")");
                    console.log(result)
                    for(var index in result){
                        $("#new").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                        // $("#test").append("<li>"+result[index]['name']+"</li>")
                    }
                },
            });
        </script>
        <!--新增管理员-->
        <div id="addModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="add">

                            <div class="layui-form-item">
                                <label class="layui-form-label">人员姓名及工号</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new" name="new_safety_head">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newAdmin()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--删除管理员-->
        <div id="deleteModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="delete">
                            <p id="deleteMessage">11</p>
                            <input type="text" id="deleteUserId" hidden>
                            <br><br>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn  layui-btn-danger" onclick="deleteAdmin()">删除</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">取消</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        layui.use('form', function(){
            var form = layui.form;
            // form.render(null, 'add'); //更新 lay-filter="add" 所在容器内的全部表单状态
        });
        function newAdmin(){
            var new_user_id = $("#new").val()
            $.ajax({
                url:'/admin/newAdminer',
                method:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data:{
                    "new_admin_id": new_user_id
                },
                dataType:"text",
                success:function(data){
                    window.location()
                },
            });
        }

        function deleteAdmin(){
            var user_id = $("#deleteUserId").val()
            $.ajax({
                url:'/admin/deleteAdminer?user_id='+user_id,
                method:"DELETE",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                success:function(data){
                    window.location()
                },
            });
        }
    </script>

</html>
