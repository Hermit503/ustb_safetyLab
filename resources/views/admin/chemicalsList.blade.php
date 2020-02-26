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
    <script>

        var b_index = 0; //新增时当前选中的build的index是几
        var m_index = 0; //修改时当前选中的build的index是几
        //添加药品
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

                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':1
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
                        $("#new_build").empty()
                        $("#new_classroom").empty()
                        $("#new_laboratory").empty()
                        if (result.length != 0) {
                            $("#new_build").removeAttr("disabled");
                            $("#new_classroom").removeAttr("disabled");
                            $("#new_laboratory").removeAttr("disabled");
                            for (i = 0; i < result.length; i++) {
                                $("#new_build").append("<option value='" + result[i]["0"] + "'>" + result[i]["0"] + "</option>")
                            }
                            for (i = 0; i < result[0]['children'].length; i++) {
                                $("#new_classroom").append("<option value='" + result[0]['children'][i]["0"][0] + "'>" + result[0]['children'][i]["0"][0] + "</option>")
                            }
                            for (i = 0; i < result[0]['children'][0]['children'].length; i++) {
                                $("#new_laboratory").append("<option value='" + result[0]['children'][0]["children"][i] + "'>" + result[0]['children'][0]["children"][i] + "</option>")
                            }
                        } else {
                            $("#new_build").append("<option>暂无实验室</option>")
                            $("#new_build").attr("disabled", "true");
                            $("#new_classroom").append("<option>暂无实验室</option>")
                            $("#new_classroom").attr("disabled", "true");
                            $("#new_laboratory").append("<option>暂无实验室</option>")
                            $("#new_laboratory").attr("disabled", "true");
                        }
                        layui.form.render('select', 'add');
                    }
                })

            })
            layui.form.on('select(new_unit_id)',function() {
                var i = 0;
                //选择单位之后的
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#new_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
                        $("#new_build").empty()
                        $("#new_classroom").empty()
                        $("#new_laboratory").empty()
                        if (result.length != 0) {
                            $("#new_build").removeAttr("disabled");
                            $("#new_classroom").removeAttr("disabled");
                            $("#new_laboratory").removeAttr("disabled");
                            for (i = 0; i < result.length; i++) {
                                $("#new_build").append("<option value='" + result[i]["0"] + "'>" + result[i]["0"] + "</option>")
                            }
                            for (i = 0; i < result[0]['children'].length; i++) {
                                $("#new_classroom").append("<option value='" + result[0]['children'][i]["0"][0] + "'>" + result[0]['children'][i]["0"][0] + "</option>")
                            }
                            for (i = 0; i < result[0]['children'][0]['children'].length; i++) {
                                $("#new_laboratory").append("<option value='" + result[0]['children'][0]["children"][i] + "'>" + result[0]['children'][0]["children"][i] + "</option>")
                            }
                        } else {
                            $("#new_build").append("<option>暂无实验室</option>")
                            $("#new_build").attr("disabled", "true");
                            $("#new_classroom").append("<option>暂无实验室</option>")
                            $("#new_classroom").attr("disabled", "true");
                            $("#new_laboratory").append("<option>暂无实验室</option>")
                            $("#new_laboratory").attr("disabled", "true");
                        }
                        layui.form.render('select', 'add');
                    }
                })

            });


            layui.form.on('select(new_build)',function() {
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#new_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
                        var tmp_classroom = null;
                        var i = 0;

                        $("#new_classroom").empty()
                        $("#new_laboratory").empty()
                        for(i = 0; i<result.length ;i++){
                            if($("#new_build").val() == result[i]["0"]){
                                b_index = i;
                                tmp_classroom = result[i]["children"]
                            }
                        }

                        for(i = 0;i<tmp_classroom.length;i++){
                            //classRoomList[i] = tmp_classroom[i]["0"][0]
                            $("#new_classroom").append("<option value='" + tmp_classroom[i]["0"][0] + "'>" + tmp_classroom[i]["0"][0] + "</option>")
                        }
                        for(i = 0;i<tmp_classroom[0]["children"].length;i++) {
                            $("#new_laboratory").append("<option value='" + tmp_classroom[0]["children"][i] + "'>" + tmp_classroom[0]["children"][i] + "</option>")
                        }
                        layui.form.render('select', 'add');
                    }
                })


            });

            layui.form.on('select(new_classroom)',function() {
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#new_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data) {
                        $("#new_laboratory").empty()
                        var result = eval("("+data+")")
                        var laboratoryList = null;
                        var i = 0;

                        // console.log(result[b_index]['children'])

                        for(i = 0; i<result[b_index]['children'].length ;i++){
                            if($("#new_classroom").val() == result[b_index]['children'][i]["0"][0]){
                                laboratoryList = result[b_index]['children'][i]["children"];
                                c_index = i;
                            }
                        }

                        for(i = 0; i<laboratoryList.length;i++){
                            $("#new_laboratory").append("<option value='" + laboratoryList[i] + "'>" + laboratoryList[i] + "</option>")
                        }

                        layui.form.render('select', 'add');
                    }
                })
            })

            layui.form.render('select', 'add');
        }


        //修改药品信息
        function openModifyModak(that) {

            var id = $(that).attr("data-id")
            var chemical_id = $(that).attr("data-chemicalId")
            var CAS = $(that).attr("data-CAS")
            var name = $(that).attr("data-name")
            var type = $(that).attr("data-type")
            var unit_id = $(that).attr("data-unitId")
            var build = $(that).attr("data-build")
            var classroom = $(that).attr("data-classroom")
            var laboratory = $(that).attr("data-laboratory")
            var user_id = $(that).attr("data-userId")
            var monitor_id = $(that).attr("data-monitorId")
            var status = $(that).attr("data-status")
            var stock = $(that).attr("data-stock")
            var unit_type = $(that).attr("data-unitType")
            var fix_time = $(that).attr("data-fixTime")
            var remarks = $(that).attr("data-remarks")

            layui.use(['layer'], function () {
                var layer = layui.layer, $ = layui.$;
                layer.open({
                    type: 1,//类型
                    area: ['1000px', '400px'],//定义宽和高
                    title: '修改药品信息',//题目
                    shadeClose: true,//点击遮罩层关闭
                    content: $('#modifyModel'),//打开的内容
                    end: function (res) {
                        $("#modifyModel").css("display", 'none');
                    }
                });

                $("[id='modify_id']").val(id)
                $("[id='modify_chemical_id']").val(chemical_id);//向模态框中赋值
                $("[id='modify_CAS']").val(CAS);
                $("[id='modify_name']").val(name);
                $("[id='modify_type']").val(type)
                $("[id='modify_unit_id']").val(unit_id)

                $("[id='modify_user_id']").val(user_id)
                $("[id='modify_monitor_id']").val(monitor_id)
                $("[id='modify_status']").val(status)
                $("[id='modify_stock']").val(stock)
                $("[id='modify_unit_type']").val(unit_type)
                $("[id='modify_fix_time']").val(fix_time)
                $("[id='modify_remarks']").val(remarks)

                var building_name = build
                var classroom_num = classroom
                var laboratory_name = laboratory

                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#modify_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
                        var tmp_classroom = null;
                        $("#modify_build").empty()
                        $("#modify_classroom").empty()
                        $("#modify_laboratory").empty()
                        if (result.length != 0) {
                            $("#modify_build").removeAttr("disabled");
                            $("#modify_classroom").removeAttr("disabled");
                            $("#modify_laboratory").removeAttr("disabled");
                            for (i = 0; i < result.length; i++) {
                                $("#modify_build").append("<option value='" + result[i]["0"] + "'>" + result[i]["0"] + "</option>")
                            }

                            $("#modify_build").val(building_name)

                            for(i = 0; i<result.length ;i++){
                                if(building_name == result[i]["0"]){
                                    b_index = i;
                                    tmp_classroom = result[i]["children"]
                                }
                            }

                            for(i = 0;i<tmp_classroom.length;i++){
                                //classRoomList[i] = tmp_classroom[i]["0"][0]
                                $("#modify_classroom").append("<option value='" + tmp_classroom[i]["0"][0] + "'>" + tmp_classroom[i]["0"][0] + "</option>")
                            }
                            for(i = 0;i<tmp_classroom[0]["children"].length;i++) {
                                $("#modify_laboratory").append("<option value='" + tmp_classroom[0]["children"][i] + "'>" + tmp_classroom[0]["children"][i] + "</option>")
                            }
                            $("[id='modify_build']").val(build)
                            $("#modify_classroom").val(classroom_num)
                            $("#modify_laboratory").val(laboratory_name)
                        } else {
                            $("#modify_build").append("<option>暂无实验室</option>")
                            $("#modify_build").attr("disabled", "true");
                            $("#modify_classroom").append("<option>暂无实验室</option>")
                            $("#modify_classroom").attr("disabled", "true");
                            $("#modify_laboratory").append("<option>暂无实验室</option>")
                            $("#modify_laboratory").attr("disabled", "true");
                        }

                        layui.form.render('select', 'modify');
                    }
                })


                layui.form.on('select(modify_unit_id)', function () {
                    var i = 0;
                    //选择单位之后的
                    $.ajax({
                        url: '/admin/getLaboratory',
                        data: {
                            'unit_id': $("#modify_unit_id").val()
                        },
                        dataType: "text",
                        success: function (data) {
                            var result = eval("(" + data + ")")
                            $("#modify_build").empty()
                            $("#modify_classroom").empty()
                            $("#modify_laboratory").empty()
                            if (result.length != 0) {
                                $("#modify_build").removeAttr("disabled");
                                $("#modify_classroom").removeAttr("disabled");
                                $("#modify_laboratory").removeAttr("disabled");
                                for (i = 0; i < result.length; i++) {
                                    $("#modify_build").append("<option value='" + result[i]["0"] + "'>" + result[i]["0"] + "</option>")
                                }
                                for (i = 0; i < result[0]['children'].length; i++) {
                                    $("#modify_classroom").append("<option value='" + result[0]['children'][i]["0"][0] + "'>" + result[0]['children'][i]["0"][0] + "</option>")
                                }
                                for (i = 0; i < result[0]['children'][0]['children'].length; i++) {
                                    $("#modify_laboratory").append("<option value='" + result[0]['children'][0]["children"][i] + "'>" + result[0]['children'][0]["children"][i] + "</option>")
                                }
                            } else {
                                $("#modify_build").append("<option>暂无实验室</option>")
                                $("#modify_build").attr("disabled", "true");
                                $("#modify_classroom").append("<option>暂无实验室</option>")
                                $("#modify_classroom").attr("disabled", "true");
                                $("#modify_laboratory").append("<option>暂无实验室</option>")
                                $("#modify_laboratory").attr("disabled", "true");
                            }
                            layui.form.render('select', 'modify');
                        }
                    })

                });


                layui.form.on('select(modify_build)', function () {
                    $.ajax({
                        url: '/admin/getLaboratory',
                        data: {
                            'unit_id': $("#modify_unit_id").val()
                        },
                        dataType: "text",
                        success: function (data) {
                            var result = eval("(" + data + ")")
                            var tmp_classroom = null;
                            var i = 0;

                            $("#modify_classroom").empty()
                            $("#modify_laboratory").empty()
                            for (i = 0; i < result.length; i++) {
                                if ($("#modify_build").val() == result[i]["0"]) {
                                    b_index = i;
                                    tmp_classroom = result[i]["children"]
                                }
                            }

                            for (i = 0; i < tmp_classroom.length; i++) {
                                //classRoomList[i] = tmp_classroom[i]["0"][0]
                                $("#modify_classroom").append("<option value='" + tmp_classroom[i]["0"][0] + "'>" + tmp_classroom[i]["0"][0] + "</option>")
                            }
                            for (i = 0; i < tmp_classroom[0]["children"].length; i++) {
                                $("#modify_laboratory").append("<option value='" + tmp_classroom[0]["children"][i] + "'>" + tmp_classroom[0]["children"][i] + "</option>")
                            }
                            layui.form.render('select', 'modify');
                        }
                    })


                });

                layui.form.on('select(modify_classroom)', function () {
                    $.ajax({
                        url: '/admin/getLaboratory',
                        data: {
                            'unit_id': $("#modify_unit_id").val()
                        },
                        dataType: "text",
                        success: function (data) {
                            $("#modify_laboratory").empty()
                            var result = eval("(" + data + ")")
                            var laboratoryList = null;
                            var i = 0;

                            // console.log(result[b_index]['children'])

                            for (i = 0; i < result[b_index]['children'].length; i++) {
                                if ($("#modify_classroom").val() == result[b_index]['children'][i]["0"][0]) {
                                    laboratoryList = result[b_index]['children'][i]["children"];
                                    c_index = i;
                                }
                            }

                            for (i = 0; i < laboratoryList.length; i++) {
                                $("#modify_laboratory").append("<option value='" + laboratoryList[i] + "'>" + laboratoryList[i] + "</option>")
                            }

                            layui.form.render('select', 'modify');
                        }
                    })
                })


            })
        }
        //删除设备
        function openDeleteModak(that){
            var id = $(that).attr("data-id")

            $("[id='delete_id']").val(id);//向模态框中赋值

            layui.use(['layer'],function () {
                var layer = layui.layer,$=layui.$;
                layer.open({
                    type:1,//类型
                    area:['360px','200px'],//定义宽和高
                    title:'删除设备',//题目
                    shadeClose:true,//点击遮罩层关闭
                    content: $('#deleteModel'),//打开的内容
                    end:function (res) {
                        $("#deleteModel").css("display",'none');
                    }
                });
            })
        }
    </script>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>药品列表</cite></a>
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
                                        <th>危化品id</th>
                                        <th>药品名</th>
                                        <th>所属单位</th>
                                        <th>所属实验室</th>
                                        <th>状态</th>
                                        <th>类型</th>
                                        <th>库存量</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($chemicals as $chemical)
                                    <tr>
                                        <td>{{$chemical['id']}}</td>
                                        <td>{{$chemical['chemical_id']}}</td>
                                        <td>{{$chemical['name']}}</td>
                                        <td>{{$chemical['unit']['unit_name']}}</td>
                                        <td>{{$chemical['laboratories']['building_name']}} {{$chemical['laboratories']['classroom_num']}} {{$chemical['laboratories']['laboratory_name']}}</td>
                                        <td>{{$chemical['status']}}</td>
                                        <td>{{$chemical['type']}}</td>
                                        <td>{{$chemical['stock']}} {{$chemical['unit_type']}}</td>
                                        <td class="td-manage">
                                            <a title="查看" onclick="openModifyModak(this)" href="javascript:;"
                                               data-id="{{$chemical['id']}}"
                                               data-chemicalId="{{$chemical['chemical_id']}}"
                                               data-CAS="{{$chemical['CAS']}}"
                                               data-name="{{$chemical['name']}}"
                                               data-type="{{$chemical['type']}}"
                                               data-unitId="{{$chemical['unit']['id']}}"
                                               data-build="{{$chemical['laboratories']['building_name']}}"
                                               data-classroom="{{$chemical['laboratories']['classroom_num']}}"
                                               data-laboratory="{{$chemical['laboratories']['laboratory_name']}}"
                                               data-userId="{{$chemical['user_id']}}"
                                               data-monitorId="{{$chemical['monitor_id']}}"
                                               data-status="{{$chemical['status']}}"
                                               data-stock="{{$chemical['stock']}}"
                                               data-unitType="{{$chemical['unit_type']}}"
                                               data-fixTime="{{$chemical['fix_time']}}"
                                               data-remarks="{{$chemical['remarks']}}"

                                            >
                                                <i class="layui-icon">&#xe63c;</i></a>
                                            <a title="删除" onclick="openDeleteModak(this)" href="javascript:;" data-id="{{$chemical['id']}}">
                                                <i class="layui-icon">&#xe640;</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$chemicals->links()}}
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
                    $("#new_unit_id").append("<option>暂无部门</option>")
                    $("#new_unit_id").attr("disabled","true");
                } else{
                    $("#new_unit_id").removeAttr("disabled");
                    for(var index in result){
                        $("#new_unit_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                        $("#modify_unit_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                    }
                }
            },
        });
        $.ajax({
            url:'/admin/allUsers',
            data:{},
            dataType:"text",
            success:function(data){
                var result = eval("("+data+")");
                console.log(result)
                for(var index in result){
                    $("#new_user_id").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                    $("#new_monitor_id").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                    $("#modify_user_id").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                    $("#modify_monitor_id").append("<option value='"+result[index]['user_id']+"'>"+result[index]['name']+" "+result[index]['user_id']+"</option>")
                    // $("#test").append("<li>"+result[index]['name']+"</li>")
                }
            },
        });
    </script>
    <!--新增药品-->
    <div id="addModel" style="display: none;">
        <div class="layui-container">
            <br><br>
            <div class="layui-row">
                <div class="layui-col-md10 layui-col-md-offset1">
                    <form class="layui-form" lay-filter="add">
                        @csrf
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_chemical_id" id="new_chemical_id" required  lay-verify="required" placeholder="请输入药品编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品CAS编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_CAS" id="new_CAS" required  lay-verify="required" placeholder="请输入药品CAS编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_name" id="new_name" required  lay-verify="required" placeholder="请输入药品名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品类型</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_type" name="new_type" lay-verify="required">
                                        <option value="medcine">药品</option>
                                        <option value="'chemical">危化品</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属单位</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_unit_id" name="new_unit_id" lay-verify="required" lay-filter="new_unit_id" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属实验室</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_build" name="new_build" lay-verify="required" lay-filter="new_build">

                                    </select>
                                </div>
                                <div class="layui-inline">
                                    <select id="new_classroom" name="new_classroom" lay-verify="required" lay-filter="new_classroom">

                                    </select>
                                </div>
                                <div class="layui-inline">
                                    <select id="new_laboratory" name="new_laboratory" lay-verify="required">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">管理员</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_user_id" name="new_user_id" lay-verify="required" lay-filter="new_user_id" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">管理者</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_monitor_id" name="new_monitor_id" lay-verify="required" lay-filter="new_user_id" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_status" name="new_status" lay-verify="required">
                                        <option value="正常">正常</option>
                                        <option value="存在问题">存在问题</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">库存量</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_stock" id="new_stock" required  lay-verify="required" placeholder="请输入库存量" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">计量单位</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="new_unit_type" name="new_unit_type" lay-verify="required">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                        <option value="L">L</option>
                                        <option value="mL">mL</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">最近核查时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_fix_time" id="new_fix_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <input type="text" name="new_remarks" id="new_remarks"  lay-verify="required" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" onclick="newChemical()">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--修改药品信息-->
    <div id="modifyModel" style="display: none;">
        <div class="layui-container">
            <br><br>
            <div class="layui-row">
                <div class="layui-col-md10 layui-col-md-offset1">
                    <form class="layui-form" lay-filter="modify">
                        @csrf
                        <input type="text" id="modify_id" hidden>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_chemical_id" id="modify_chemical_id" required  lay-verify="required" placeholder="请输入药品编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品CAS编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_CAS" id="modify_CAS" required  lay-verify="required" placeholder="请输入药品CAS编号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_name" id="modify_name" required  lay-verify="required" placeholder="请输入药品名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">药品类型</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_type" name="modify_type" lay-verify="required">
                                        <option value="medcine">药品</option>
                                        <option value="'chemical">危化品</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属单位</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_unit_id" name="modify_unit_id" lay-verify="required" lay-filter="modify_unit_id" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">所属实验室</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_build" name="modify_build" lay-verify="required" lay-filter="modify_build">

                                    </select>
                                </div>
                                <div class="layui-inline">
                                    <select id="modify_classroom" name="modify_classroom" lay-verify="required" lay-filter="modify_classroom">

                                    </select>
                                </div>
                                <div class="layui-inline">
                                    <select id="modify_laboratory" name="modify_laboratory" lay-verify="required">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">管理员</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_user_id" name="modify_user_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">管理者</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_monitor_id" name="modify_monitor_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_status" name="modify_status" lay-verify="required">
                                        <option value="正常">正常</option>
                                        <option value="存在问题">存在问题</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">库存量</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_stock" id="modify_stock" required  lay-verify="required" placeholder="请输入库存量" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">计量单位</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <select id="modify_unit_type" name="modify_unit_type" lay-verify="required">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                        <option value="L">L</option>
                                        <option value="mL">mL</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">最近核查时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_fix_time" id="modify_fix_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <input type="text" name="modify_remarks" id="modify_remarks"  lay-verify="required" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" onclick="modifyChemical()">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--删除药品-->
    <div id="deleteModel" style="display: none;">
        <div class="layui-container">
            <br><br>
            <div class="layui-row">
                <div class="layui-col-md10 layui-col-md-offset1">
                    <form class="layui-form">
                        <input type="text" hidden id="delete_id" name="delete_equipment_id">
                        <p>确认删除？</p>
                        <br><br>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn  layui-btn-danger" onclick="deleteChemical()">删除</button>
                                <button type="reset" class="layui-btn layui-btn-primary">取消</button>
                            </div>
                        </div>
                    </form>
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
                elem: '#new_fix_time', //指定元素
                trigger: 'click',
                value: today
            });

            laydate.render({
                elem: '#modify_fix_time', //指定元素
                trigger: 'click'
            });
        });

        function newChemical() {
            var chemical_id = $("#new_chemical_id").val()
            var CAS = $("#new_CAS").val()
            var name = $("#new_name").val()
            var type = $("#new_type").val()
            var unit_id = $("#new_unit_id").val()
            var build = $("#new_build").val()
            var classroom = $("#new_classroom").val()
            var laboratory = $("#new_laboratory").val()
            var user_id = $("#new_user_id").val()
            var monitor_id = $("#new_monitor_id").val()
            var status = $("#new_status").val()
            var stock = $("#new_stock").val()
            var unit_type = $("#new_unit_type").val()
            var fix_time = $("#new_fix_time").val()
            var remarks = $("#new_remarks").val()

            $.ajax({
                url:'/admin/newChemical',
                method:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'chemical_id':chemical_id,
                    'CAS':CAS,
                    'name':name,
                    'type':type,
                    'unit_id':unit_id,
                    'build':build,
                    'classroom':classroom,
                    'laboratory':laboratory,
                    'user_id':user_id,
                    'monitor_id':monitor_id,
                    'status':status,
                    'stock':stock,
                    'unit_type':unit_type,
                    'fix_time':fix_time,
                    'remarks':remarks
                },
                success:function (data) {
                    alert(data);
                }
            })


        }

        function modifyChemical() {
            var id = $("#modify_id").val()
            var chemical_id = $("#modify_chemical_id").val()
            var CAS = $("#modify_CAS").val()
            var name = $("#modify_name").val()
            var type = $("#modify_type").val()
            var unit_id = $("#modify_unit_id").val()
            var build = $("#modify_build").val()
            var classroom = $("#modify_classroom").val()
            var laboratory = $("#modify_laboratory").val()
            var user_id = $("#modify_user_id").val()
            var monitor_id = $("#modify_monitor_id").val()
            var status = $("#modify_status").val()
            var stock = $("#modify_stock").val()
            var unit_type = $("#modify_unit_type").val()
            var fix_time = $("#modify_fix_time").val()
            var remarks = $("#modify_remarks").val()

            $.ajax({
                url:'/admin/modifyChemical',
                method:"PUT",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'id':id,
                    'chemical_id':chemical_id,
                    'CAS':CAS,
                    'name':name,
                    'type':type,
                    'unit_id':unit_id,
                    'build':build,
                    'classroom':classroom,
                    'laboratory':laboratory,
                    'user_id':user_id,
                    'monitor_id':monitor_id,
                    'status':status,
                    'stock':stock,
                    'unit_type':unit_type,
                    'fix_time':fix_time,
                    'remarks':remarks
                },
                success:function (data) {
                    alert(data);
                }
            })
        }

        function deleteChemical() {
            var id = $("#delete_id").val()
            $.ajax({
                url:'/admin/deleteChemical',
                method:"DELETE",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'id':id
                },
                success:function (data) {
                    alert(data)
                }
            })
        }

    </script>

</html>
