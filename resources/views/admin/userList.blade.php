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
        <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            function getNewLead(){
                var vs = $("#unit_id").val();
                $("#parent_id").empty();
                $.ajax({
                    url:'/tool',
                    data:{
                        "unit_id":vs
                    },
                    success:function(data){
                        console.log(data);
                        var result = eval("("+data+")");
                        var length = result.length;
                        if(length == 0){
                            $("#parent_id").append("<option>该部门暂无领导</option>")
                            $("#parent_id").attr("disabled","true");
                        } else{
                            $("#parent_id").removeAttr("disabled");
                            for(var index in result){
                                $("#parent_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                            }
                            layui.form.render('select','add');
                        }
                    },
                    dataType:"text"
                });
            }
            function getModifyLead(){
                var vs = $("#modify_unit_id").val();
                $("#modify_parent_id").empty();
                $.ajax({
                    url:'/tool',
                    data:{
                        "unit_id":vs
                    },
                    success:function(data){
                        console.log(data);
                        var result = eval("("+data+")");
                        var length = result.length;
                        if(length == 0){
                            $("#modify_parent_id").append("<option>该部门暂无领导</option>")
                            $("#modify_parent_id").attr("disabled","true");
                        } else{
                            $("#modify_parent_id").removeAttr("disabled");
                            for(var index in result){
                                $("#modify_parent_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                            }
                            layui.form.render('select','modify');
                        }
                    },
                    dataType:"text"
                });
            }
            //添加用户
            function openAddModak(){
                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'添加用户',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#addModel'),//打开的内容
                        end:function (res) {
                            $("#addModel").css("display",'none');
                        }
                    });
                    getNewLead();
                })
                layui.form.on('select(unit_id)',function() {
                    getNewLead();
                })
                layui.form.render('select', 'add');
            }
            //修改用户信息
            function openModifyModak(that){
                var name = $(that).attr("data-name")
                var userId = $(that).attr("data-userId")
                var sex = $(that).attr("data-sex")
                var phone_number = $(that).attr("data-phoneNumber")
                var title = $(that).attr("data-title")
                var unit_id = $(that).attr("data-unitId")
                var email = $(that).attr("data-email")
                var parent_id = $(that).attr("data-parentId")

                $("[id='modify_name']").val(name);//向模态框中赋值
                $("[id='modify_user_id']").val(userId);
                $("[id='modify_sex']").val(sex);
                $("[id='modify_phone_number']").val(phone_number)
                $("[id='modify_title']").val(title)
                $("[id='modify_unit_id']").val(unit_id)
                $("[id='modify_email']").val(email)
                $("[id='modify_parent_id']").val(parent_id)

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
            //删除用户
            function openDeleteModak(that){
                var id = $(that).attr("data-id")
                var userName = "确认删除"+$(that).attr("data-name")+"?"

                $("[id='delete_user_id']").val(id);//向模态框中赋值
                $("[id='deleteName']").text(userName);
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
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user['id']}}</td>
                                        <td>{{$user['user_id']}}</td>
                                        <td>{{$user['name']}}</td>
                                        <td>{{$user['phone_number']}}</td>
                                        <td>{{$user['email']}}</td>
                                        <td>{{$user['unit']['unit_name']}}</td>
                                        <td class="td-manage">
                                            <a title="修改" onclick="openModifyModak(this)" href="javascript:;"
                                                data-name="{{$user['name']}}"
                                                data-userId="{{$user['user_id']}}"
                                                data-sex="{{$user['sex']}}"
                                                data-phoneNumber="{{$user['phone_number']}}"
                                                data-title="{{$user['title']}}"
                                                data-unitId="{{$user['unit_id']}}"
                                                data-email="{{$user['email']}}"
                                                data-password="{{$user['password']}}"
                                                data-parentId="{{$user['parent_id']}}"
                                            >
                                                <i class="layui-icon">&#xe63c;</i></a>
                                            <a title="删除" onclick="openDeleteModak(this)" data-id="{{$user['user_id']}}" data-name="{{$user['name']}}" href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i></a>
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
        <script>
            $.ajax({
                url:'/units',
                data:{},
                dataType:"text",
                success:function(data){
                    var result = eval("("+data+")");
                    var length = result.length;
                    console.log(result);
                    if(length == 0){
                        $("#unit_id").append("<option>暂无部门</option>")
                        $("#unit_id").attr("disabled","true");
                    } else{
                        $("#unit_id").removeAttr("disabled");
                        for(var index in result){
                            $("#unit_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                            $("#modify_unit_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                        }
                    }
                },
            });
        </script>
        <!--新增用户-->
        <div id="addModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="add">
                            @csrf
                            <div class="layui-form-item">
                                <label class="layui-form-label">姓名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" id="name" required  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">工号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="user_id" id="user_id" required  lay-verify="required" placeholder="请输入工号" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="sex" name="sex" lay-verify="required">
                                            <option value="男">男</option>
                                            <option value="女">女</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="phone_number" id="phone_number" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">职称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" id="title" required  lay-verify="required" placeholder="请输入职称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属单位</label>
                                <div class="layui-input-block">
                                    <select id="unit_id" name="unit_id" lay-verify="required" lay-search lay-filter="unit_id">

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">上级领导</label>
                                <div class="layui-input-block">
                                    <select id="parent_id" name="parent_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="email" id="email" required  lay-verify="required" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">密码</label>
                                <div class="layui-input-block">
                                    <input type="password" name="password" id="password" required  lay-verify="required" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">重复密码</label>
                                <div class="layui-input-block">
                                    <input type="password" name="repassword" id="repassword" required  lay-verify="required" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newUser()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
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
                                <label class="layui-form-label">姓名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_name" id="modify_name" required  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">工号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_user_id" id="modify_user_id" required  lay-verify="required" disabled autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_sex" name="modify_sex" lay-verify="required">
                                            <option value="男">男</option>
                                            <option value="女">女</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_phone_number" id="modify_phone_number" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">职称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_title" id="modify_title" required  lay-verify="required" placeholder="请输入职称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属单位</label>
                                <div class="layui-input-block">
                                    <select id="modify_unit_id" name="modify_unit_id" lay-verify="required" lay-search lay-filter="modify_unit_id">

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">上级领导</label>
                                <div class="layui-input-block">
                                    <select id="modify_parent_id" name="modify_parent_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_email" id="modify_email" required  lay-verify="required" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="modifyUser()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--删除用户-->
        <div id="deleteModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form">
                            <input type="text" id="delete_user_id" name="delete_user_id">
                            <p id="deleteName"></p>
                            <br><br>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn  layui-btn-danger" onclick="deleteUser()">删除</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">取消</button>
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
            function newUser(){
                var name = $("input[name='name']").val()
                var user_id = $("input[name='user_id']").val()
                var sex = $("#sex").val()
                var phone_number = $("input[name='phone_number']").val()
                var title = $("input[name='title']").val()
                var unit_id = $("#unit_id").val()
                var parent_id = $("#parent_id").val()
                var email = $("input[name='email']").val()
                var password = $("input[name='password']").val()
                var repassword = $("input[name='repassword']").val()

                if(password != repassword){
                    alert('两次输入的密码不一致');
                }else {
                    $.ajax({
                        url: '/admin/newUser',
                        method: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {
                            'name':name,
                            'user_id':user_id,
                            'sex':sex,
                            'phone_number':phone_number,
                            'title':title,
                            'unit_id':unit_id,
                            'parent_id':parent_id,
                            'email':email,
                            'password':password
                        },
                        dataType: "text",
                        success: function (data) {
                            window.location()
                        },
                    });
                }
            }

            function modifyUser(){
                var name = $("input[name='modify_name']").val()
                var user_id = $("input[name='modify_user_id']").val()
                var sex = $("#modify_sex").val()
                var phone_number = $("input[name='modify_phone_number']").val()
                var title = $("input[name='modify_title']").val()
                var unit_id = $("#modify_unit_id").val()
                var parent_id = $("#modify_parent_id").val()
                var email = $("input[name='modify_email']").val()

                $.ajax({
                    url: '/admin/modifyUser',
                    method: "PUT",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'name':name,
                        'user_id':user_id,
                        'sex':sex,
                        'phone_number':phone_number,
                        'title':title,
                        'unit_id':unit_id,
                        'parent_id':parent_id,
                        'email':email,
                    },
                    dataType: "text",
                    success: function (data) {
                        window.location()
                    },
                });
            }
            function deleteUser(){
                var user_id = $("input[name='delete_user_id']").val()

                $.ajax({
                    url: '/admin/deleteUser',
                    method: "DELETE",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        'user_id':user_id
                    },
                    dataType: "text",
                    success: function (data) {
                        window.location()
                    },
                });
            }
        </script>
    </body>
</html>
