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
            function getPermission() {
                var vs = $("#new").val();
                $("#new_permission").empty();
                $.ajax({
                    url:'/admin/choosePermission',
                    data:{
                        "user_id":vs
                    },
                    success:function(data){
                        var result = eval("("+data+")");
                        var length = result.length;
                        if(length == 0){
                            $("#new_permission").append("<option>没有可选权限</option>")
                            $("#new_permission").attr("disabled","true");
                        } else{
                            $("#new_permission").removeAttr("disabled");
                            for(var index in result){
                                $("#new_permission").append("<option value='"+result[index]+"'>"+result[index]+"</option>")
                            }
                        }
                        layui.form.render('select','add');
                    },
                    dataType:"text"
                });
            }
            //添加角色
            function openAddModak(){
                $.ajax({
                    url:'/admin/allUsers',
                    data:{},
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")");
                        for(var index in result){
                            $("#new").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                        }
                    },
                });
                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'添加权限',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#addModel'),//打开的内容
                        end:function (res) {
                            $("#addModel").css("display",'none');
                        }
                    });
                    getPermission();
                })
                layui.form.on('select(new)',function() {
                    getPermission();
                })
                layui.form.render('select', 'add');
            }

            //删除角色
            function openDeleteModak(that){
                var id = $(that).attr("data-id")
                var userName = $(that).attr("data-userName")
                var userId = $(that).attr("data-userId")
                var permission = $(that).attr("data-permission")

                $("[id='delete_id']").val(id);//向模态框中赋值
                $("[id='deletePermissionName']").text("确认删除"+userName+"("+userId+")的"+permission+"权限？")


                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['360px','200px'],//定义宽和高
                        title:'删除权限',//题目
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
                    <cite>权限列表</cite></a>
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
                                        <th>权限</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{$permission['id']}}</td>
                                        <td>{{$permission['user_id']}}</td>
                                        <td>{{$permission['user']['name']}}</td>
                                        <td>{{$permission['permission']}}</td>
                                        <td class="td-manage">
                                            <a title="删除" onclick="openDeleteModak(this)"
                                               data-userName="{{$permission['user']['name']}}"
                                               data-userId="{{$permission['user_id']}}"
                                               data-id="{{$permission['id']}}"
                                               data-permission="{{$permission['permission']}}"
                                               href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$permissions->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--新增权限-->
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
                                        <select id="new" name="new_safety_head" lay-filter="new">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">可选角色</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new_permission" name="new_permission">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newPermission()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--删除权限-->
        <div id="deleteModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="delete">
                            @csrf
                            <input type="text" id="delete_id" name="delete_id" hidden>
                            <p id="deletePermissionName"></p>
                            <br><br>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="deletePermission()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            //Demo
            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(formDemo)', function(data){
                    layer.msg(JSON.stringify(data.field));
                    return false;
                });
            });

            function newPermission(){
                var new_user_id = $("#new").val()
                var new_permission = $("#new_permission").val()

                $.ajax({
                    url:'/admin/newPermission',
                    method:"POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{
                        "user_id": new_user_id,
                        "permission": new_permission
                    },
                    dataType:"text",
                    success:function(data){
                        alert(data)
                    },
                });
            }

            function deletePermission(){
                var id = $("input[name='delete_id']").val()

                $.ajax({
                    url:'/admin/deletePermission',
                    method:"DELETE",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{
                        "id": id
                    },
                    dataType:"text",
                    success:function(data){
                        alert(data)
                    },
                });
            }
        </script>
    </body>
</html>
