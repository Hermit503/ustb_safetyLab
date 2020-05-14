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
        //添加设备
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


        //修改用户信息
        function openModifyModak(that){

            var id = $(that).attr("data-id")

            layui.use(['layer'],function () {
                var layer = layui.layer,$=layui.$;
                layer.open({
                    type:1,//类型
                    area:['1000px','400px'],//定义宽和高
                    title:'修改设备信息',//题目
                    shadeClose:true,//点击遮罩层关闭
                    content: $('#modifyModel'),//打开的内容
                    end:function (res) {
                        $("#modifyModel").css("display",'none');
                    }
                });
                $.ajax({
                    url:'/admin/getOneEquipment?id='+id,
                    dataType:"text",
                    success:function(data){

                        var result = eval("("+data+")")
                        $("[id='modify_id']").val(id)
                        $("[id='modify_asset_number']").val(result["asset_number"]);//向模态框中赋值
                        $("[id='modify_equipment_name']").val(result["equipment_name"]);
                        $("[id='modify_equipment_type']").val(result["equipment_type"]);
                        $("[id='modify_unit_id']").val(result["unit_id"])
                        $("[id='modify_status']").val(result["status"])
                        $("[id='modify_fix_time']").val(result["fix_time"])
                        $("[id='modify_storage_time']").val(result["storage_time"])
                        $("[id='modify_scrap_time']").val(result["scrap_time"])

                        var unit_id = result["unit_id"]
                        var building_name = result["laboratory"]["building_name"]
                        var classroom_num = result["laboratory"]["classroom_num"]
                        var laboratory_name = result["laboratory"]["laboratory_name"]

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
                    }
                })
            })

            layui.form.on('select(modify_unit_id)',function() {
                var i = 0;
                //选择单位之后的
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#modify_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
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


            layui.form.on('select(modify_build)',function() {
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#modify_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data){
                        var result = eval("("+data+")")
                        var tmp_classroom = null;
                        var i = 0;

                        $("#modify_classroom").empty()
                        $("#modify_laboratory").empty()
                        for(i = 0; i<result.length ;i++){
                            if($("#modify_build").val() == result[i]["0"]){
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
                        layui.form.render('select', 'modify');
                    }
                })


            });

            layui.form.on('select(modify_classroom)',function() {
                $.ajax({
                    url:'/admin/getLaboratory',
                    data:{
                        'unit_id':$("#modify_unit_id").val()
                    },
                    dataType:"text",
                    success:function(data) {
                        $("#modify_laboratory").empty()
                        var result = eval("("+data+")")
                        var laboratoryList = null;
                        var i = 0;

                        // console.log(result[b_index]['children'])

                        for(i = 0; i<result[b_index]['children'].length ;i++){
                            if($("#modify_classroom").val() == result[b_index]['children'][i]["0"][0]){
                                laboratoryList = result[b_index]['children'][i]["children"];
                                c_index = i;
                            }
                        }

                        for(i = 0; i<laboratoryList.length;i++){
                            $("#modify_laboratory").append("<option value='" + laboratoryList[i] + "'>" + laboratoryList[i] + "</option>")
                        }

                        layui.form.render('select', 'modify');
                    }
                })
            })


        }
        //删除设备
        function openDeleteModak(that){
            var id = $(that).attr("data-id")

            $("[id='delete_equipment_id']").val(id);//向模态框中赋值

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
                    <cite>设备列表</cite></a>
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
                                <i class="layui-icon"></i>添加</button>
                                <button type="button" class="layui-btn" id="file" style="float:right;margin-right: 0px;margin-top: 5px;">
                                    <i class="layui-icon">&#xe67c;</i>上传文件
                                </button> 
                            </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>资产编号</th>
                                        <th>设备名称</th>
                                        <th>所属实验室</th>
                                        <th>状态</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipments as $equipment)
                                    <tr>
                                        <td>{{$equipment['id']}}</td>
                                        <td>{{$equipment['asset_number']}}</td>
                                        <td>{{$equipment['equipment_name']}}</td>
                                        <td>{{$equipment['laboratories']['building_name']}} {{$equipment['laboratories']['classroom_num']}} {{$equipment['laboratories']['laboratory_name']}}</td>
                                        <td>{{$equipment['status']}}</td>
                                        <td class="td-manage">
                                            <a title="修改" onclick="openModifyModak(this)" href="javascript:;"
                                               data-id="{{$equipment['id']}}"
                                            >
                                                <i class="layui-icon">&#xe63c;</i></a>
                                            <a title="删除" onclick="openDeleteModak(this)" href="javascript:;" data-id="{{$equipment['id']}}">
                                                <i class="layui-icon">&#xe640;</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$equipments->links()}}
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
    </script>
        <!--新增设备-->
        <div id="addModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="add">
                            @csrf
                            <div class="layui-form-item">
                                <label class="layui-form-label">资产编号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_asset_number" id="new_asset_number" required  lay-verify="required" placeholder="请输入资产编号" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">设备名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_equipment_name" id="new_equipment_name" required  lay-verify="required" placeholder="请输入设备名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">设备类型</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new_equipment_type" name="new_equipment_type" lay-verify="required">
                                            <option value="特种设备">特种设备</option>
                                            <option value="普通设备">普通设备</option>
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
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new_status" name="new_status" lay-verify="required">
                                            <option value="正常">正常</option>
                                            <option value="维修">维修</option>
                                            <option value="报废">报废</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">入库时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_storage_time" id="new_storage_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">预计报废时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_scrap_time" id="new_scrap_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">最近检修时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_fix_time" id="new_fix_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newEquipment()">立即提交</button>
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
        <!--修改设备信息-->
        <div id="modifyModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="modify">
                            @csrf
                            <input type="text" id="modify_id" hidden>
                            <div class="layui-form-item">
                                <label class="layui-form-label">资产编号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_asset_number" id="modify_asset_number" required  lay-verify="required" placeholder="请输入资产编号" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">设备名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_equipment_name" id="modify_equipment_name" required  lay-verify="required" placeholder="请输入设备名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">设备类型</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_equipment_type" name="modify_equipment_type" lay-verify="required">
                                            <option value="特种设备">特种设备</option>
                                            <option value="普通设备">普通设备</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属单位</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_unit_id" name="modify_unit_id" lay-verify="required" lay-search>

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
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_status" name="modify_status" lay-verify="required">
                                            <option value="正常">正常</option>
                                            <option value="维修">维修</option>
                                            <option value="报废">报废</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">入库时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_storage_time" id="modify_storage_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">预计报废时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_scrap_time" id="modify_scrap_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">最近检修时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="modify_fix_time" id="modify_fix_time" required  lay-verify="required" placeholder="请输入手机号码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="modifyEquipment()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--删除设备-->
        <div id="deleteModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form">
                            <input type="text" id="delete_equipment_id" name="delete_equipment_id" hidden>
                            <p>确认删除？</p>
                            <br><br>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn  layui-btn-danger" onclick="deleteEquipment()">删除</button>
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

        layui.use('upload', function(){
            var upload = layui.upload;

            var uploadInst = upload.render({
                elem: '#file' //绑定元素
                ,url: '/admin/uploadExamQuestion' //上传接口
                ,method:'post'
                ,accept:'file'
                ,exts:'xls|xlsx'
                ,headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                ,auto:true
                , done: function(res){
                    alert(res.msg)
                }
                ,success:function (res) {
                    console.log(res)
                }
                ,error: function(res){
                    console.log('fail');
                }
            });
        });

        function newEquipment() {
            var asset_number = $("#new_asset_number").val()
            var equipment_name = $("#new_equipment_name").val()
            var equipment_type = $("#new_equipment_type").val()
            var unit_id = $("#new_unit_id").val()

            var build_id = $("#new_build").val()
            var classroom = $("#new_classroom").val()
            var laboratory = $("#new_laboratory").val()

            var status = $("#new_status").val()
            var storage_time = $("#new_storage_time").val()
            var scrap_time = $("#new_scrap_time").val()
            var fix_time = $("#new_fix_time").val()

            $.ajax({
                url:'/admin/newEquipment',
                method:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'asset_number':asset_number,
                    'equipment_name':equipment_name,
                    'equipment_type':equipment_type,
                    'build_id':build_id,
                    'classroom':classroom,
                    'laboratory':laboratory,
                    'unit_id':unit_id,
                    'status':status,
                    'storage_time':storage_time,
                    'scrap_time':scrap_time,
                    'fix_time':fix_time
                },
                success:function(data){
                    alert(data)
                },
            });
        }

        function modifyEquipment() {
            var asset_number = $("#modify_asset_number").val()
            var equipment_name = $("#modify_equipment_name").val()
            var equipment_type = $("#modify_equipment_type").val()
            var unit_id = $("#modify_unit_id").val()

            var build_id = $("#modify_build").val()
            var classroom = $("#modify_classroom").val()
            var laboratory = $("#modify_laboratory").val()

            var status = $("#modify_status").val()
            var storage_time = $("#modify_storage_time").val()
            var scrap_time = $("#modify_scrap_time").val()
            var fix_time = $("#modify_fix_time").val()
            var id = $("#modify_id").val()
            $.ajax({
                url:'/admin/modifyEquipment',
                method:"PUT",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'id':id,
                    'asset_number':asset_number,
                    'equipment_name':equipment_name,
                    'equipment_type':equipment_type,
                    'build_id':build_id,
                    'classroom':classroom,
                    'laboratory':laboratory,
                    'unit_id':unit_id,
                    'status':status,
                    'storage_time':storage_time,
                    'scrap_time':scrap_time,
                    'fix_time':fix_time
                },
                success:function(data){
                    alert(data)
                },
            });
        }

        function deleteEquipment() {
            var id = $("input[name='delete_equipment_id']").val()
            console.log(id)
            $.ajax({
                url:'/admin/deleteEquipment',
                method:"DELETE",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"text",
                data:{
                    'id':id
                },
                sucess:function (data) {
                    alert(data)
                }
            })
        }
    </script>

</html>
