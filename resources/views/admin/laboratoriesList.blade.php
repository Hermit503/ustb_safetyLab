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
            //新增实验室
            function openAddModak(){
                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'添加实验室',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#addModel')//打开的内容
                    });
                })
            }

            //修改实验室
            function openModifyModak(that){
                var id = $(that).attr("data-id")
                var modify_laboratory_name = $(that).attr("data-laboratoryName")
                var modify_building_name = $(that).attr("data-buildingName")
                var modify_classroom_name = $(that).attr("data-classroomName")
                var modify_unit_id = $(that).attr("data-unitId")
                var modify_laboratory_type = $(that).attr("data-laboratoryType")
                var modify_laboratory_attribute = $(that).attr("data-laboratoryAttr")
                var modify_laboratory_status = $(that).attr("data-laboratoryStatus")
                var modify_safety_head = $(that).attr("data-safetyHead")
                var modify_laboratory_people1 = $(that).attr("data-people1")
                var modify_laboratory_people2 = $(that).attr("data-people2")

                $("[id='id']").val(id);//向模态框中赋值
                $("[id='modify_laboratory_name']").val(modify_laboratory_name);
                $("[id='modify_building_name']").val(modify_building_name);
                $("[id='modify_classroom_name']").val(modify_classroom_name);
                $("[id='modify_unit_id']").val(modify_unit_id);
                $("[id='modify_laboratory_type']").val(modify_laboratory_type);
                $("[id='modify_laboratory_attribute']").val(modify_laboratory_attribute);
                $("[id='modify_laboratory_status']").val(modify_laboratory_status);
                $("[id='modify_safety_head']").val(modify_safety_head);
                $("[id='modify_laboratory_people1']").val(modify_laboratory_people1);
                $("[id='modify_laboratory_people2']").val(modify_laboratory_people2);


                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'修改实验室',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#modifyModel')//打开的内容
                    });
                })
                layui.form.render('select', 'modify');
            }
        </script>
    </head>

    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                    <cite>实验室列表</cite></a>
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
                                        <th>实验室名称</th>
                                        <th>实验室位置</th>
                                        <th>所属单位</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($laboratories as $laboratory)
                                    <tr>
                                        <td>{{$laboratory['id']}}</td>
                                        <td>{{$laboratory['laboratory_name']}}</td>
                                        <td>{{$laboratory['building_name']}}{{$laboratory['classroom_num']}}</td>
                                        <td>{{$laboratory['unit']['unit_name']}}</td>
                                        <td>{{$laboratory['created_at']}}</td>
                                        <td>{{$laboratory['updated_at']}}</td>
                                        <td class="td-manage">
                                            <a title="查看" onclick="openModifyModak(this)"
                                               data-id="{{$laboratory['id']}}"
                                               data-laboratoryName = "{{$laboratory['laboratory_name']}}"
                                               data-buildingName = "{{$laboratory['building_name']}}"
                                               data-classroomName = "{{$laboratory['classroom_num']}}"
                                               data-unitId = "{{$laboratory['unit']['id']}}"
                                               data-laboratoryType = "{{$laboratory['laboratory_type']}}"
                                               data-laboratoryAttr = "{{$laboratory['laboratory_attribute']}}"
                                               data-laboratoryStatus = "{{$laboratory['laboratory_status']}}"
                                               data-safetyHead = "{{$laboratory['safety_head']}}"
                                               data-people1="{{$laboratory['maintenance_people1']}}"
                                               data-people2="{{$laboratory['maintenance_people2']}}"
                                               href="javascript:;">
                                                <i class="layui-icon">&#xe63c;</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$laboratories->links()}}
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
                    if(length == 0){
                        $("#new_unit_id").append("<option>暂无部门</option>")
                        $("#new_unit_id").attr("disabled","true");
                        $("#modify_unit_id").append("<option>暂无部门</option>")
                        $("#modify_unit_id").attr("disabled","true");
                    } else{
                        $("#new_unit_id").removeAttr("disabled");
                        $("#modify_unit_id").removeAttr("disabled");
                        for(var index in result){
                            $("#new_unit_id").append("<option value='"+index+"'>"+result[index]+"</option>")
                        }
                        for(var index in result){
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
                    for(var index in result){
                        $("#new_safety_head").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                        $("#new_laboratory_people1").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                        $("#new_laboratory_people2").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                        $("#modify_safety_head").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                        $("#modify_laboratory_people1").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                        $("#modify_laboratory_people2").append("<option value='"+result[index]['id']+"'>"+result[index]['name']+"</option>")
                    }
                },
            });
        </script>
        <!--新增实验室-->
        <div id="addModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form">
                            @csrf
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_laboratory_name" required  lay-verify="required" placeholder="请输入实验室名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">地址</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new_building_name" name="new_building_name" lay-verify="required" lay-search>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{$i}}教">{{$i}}教</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="layui-inline">
                                        <select id="new_classroom_name" name="new_classroom_name" lay-verify="required" lay-search>
                                            @for ($i = 1; $i <= 4; $i++)
                                                @for ($j = 0; $j <= 8; $j++)
                                                    <option value="{{$i}}0{{$j}}">{{$i}}0{{$j}}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属单位</label>
                                <div class="layui-input-block">
                                    <select id="new_unit_id" name="new_unit_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室类别</label>
                                <div class="layui-input-block">
                                    <select id="new_laboratory_type" name="new_laboratory_type" lay-verify="required" lay-search>
                                        <!--这块忘了写啥了-->
                                        <option value="教学">教学</option>
                                        <option value="实验">实验</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室属性</label>
                                <div class="layui-input-block">
                                    <select id="new_laboratory_attribute" name="new_laboratory_attribute" lay-verify="required" lay-search>
                                        <option value="自建">自建</option>
                                        <option value="共建">共建</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室状态</label>
                                <div class="layui-input-block">
                                    <select id="new_laboratory_status" name="new_laboratory_status" lay-verify="required" lay-search>
                                        <option value="批复建设">批复建设</option>
                                        <option value="施工在建">施工在建</option>
                                        <option value="建成使用">建成使用</option>
                                        <option value="废弃不用">废弃不用</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室安全负责人</label>
                                <div class="layui-input-block">
                                    <select id="new_safety_head" name="new_safety_head" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室维护人</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="new_laboratory_people1" name="new_laboratory_people1" lay-verify="required" lay-search>

                                        </select>
                                    </div>
                                   <div class="layui-inline">
                                        <select id="new_laboratory_people2" name="new_laboratory_people2" lay-verify="required" lay-search>

                                        </select>
                                   </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newLarborary()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--修改实验室-->
        <div id="modifyModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="modify">
                            @csrf
                            <input type="text" id="id" name="id" hidden>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室名称</label>
                                <div class="layui-input-block">
                                    <input type="text" id="modify_laboratory_name" name="modify_laboratory_name" required  lay-verify="required" placeholder="请输入实验室名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">地址</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_building_name" name="modify_building_name" lay-verify="required" lay-search>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{$i}}教">{{$i}}教</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="layui-inline">
                                        <select id="modify_classroom_name" name="modify_classroom_name" lay-verify="required" lay-search>
                                            @for ($i = 1; $i <= 4; $i++)
                                                @for ($j = 0; $j <= 8; $j++)
                                                    <option value="{{$i}}0{{$j}}">{{$i}}0{{$j}}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属单位</label>
                                <div class="layui-input-block">
                                    <select id="modify_unit_id" name="modify_unit_id" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室类别</label>
                                <div class="layui-input-block">
                                    <select id="modify_laboratory_type" name="modify_laboratory_type" lay-verify="required" lay-search>
                                        <!--这块忘了写啥了-->
                                        <option value="教学">教学</option>
                                        <option value="实验">实验</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室属性</label>
                                <div class="layui-input-block">
                                    <select id="modify_laboratory_attribute" name="modify_laboratory_attribute" lay-verify="required" lay-search>
                                        <option value="自建">自建</option>
                                        <option value="共建">共建</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室状态</label>
                                <div class="layui-input-block">
                                    <select id="modify_laboratory_status" name="modify_laboratory_status" lay-verify="required" lay-search>
                                        <option value="批复建设">批复建设</option>
                                        <option value="施工在建">施工在建</option>
                                        <option value="建成使用">建成使用</option>
                                        <option value="废弃不用">废弃不用</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室安全负责人</label>
                                <div class="layui-input-block">
                                    <select id="modify_safety_head" name="modify_safety_head" lay-verify="required" lay-search>

                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实验室维护人</label>
                                <div class="layui-input-block">
                                    <div class="layui-inline">
                                        <select id="modify_laboratory_people1" name="modify_laboratory_people1" lay-verify="required" lay-search>

                                        </select>
                                    </div>
                                   <div class="layui-inline">
                                        <select id="modify_laboratory_people2" name="modify_laboratory_people2" lay-verify="required" lay-search>

                                        </select>
                                   </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="modifyLaborary()">立即提交</button>
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

            function newLarborary(){
                var new_laboratory_name = $("input[name='new_laboratory_name']").val()
                var new_building_name = $("#new_building_name").val()
                var new_classroom_name = $("#new_classroom_name").val()
                var new_unit_id = $("#new_unit_id").val()
                var new_laboratory_type = $("#new_laboratory_type").val()
                var new_laboratory_attribute = $("#new_laboratory_attribute").val()
                var new_laboratory_status = $("#new_laboratory_status").val()
                var new_safety_head = $("#new_safety_head").val()
                var new_laboratory_people1 = $("#new_laboratory_people1").val()
                var new_laboratory_people2 = $("#new_laboratory_people2").val()
                console.log(new_classroom_name)
                $.ajax({
                    url:'/admin/newLarborary',
                    method:"POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{
                        "new_laboratory_name": new_laboratory_name,
                        "new_building_name": new_building_name,
                        "new_classroom_name": new_classroom_name,
                        "new_unit_id": new_unit_id,
                        "new_laboratory_type": new_laboratory_type,
                        "new_laboratory_attribute": new_laboratory_attribute,
                        "new_laboratory_status": new_laboratory_status,
                        "new_safety_head": new_safety_head,
                        "new_laboratory_people1": new_laboratory_people1,
                        "new_laboratory_people2": new_laboratory_people2
                    },
                    dataType:"text",
                    success:function(data){
                        window.location()
                    },
                });
            }

            function modifyLaborary(){
                var id = $("input[name='id']").val()
                var modify_laboratory_name = $("input[name='modify_laboratory_name']").val()
                var modify_building_name = $("#modify_building_name").val()
                var modify_classroom_name = $("#modify_classroom_name").val()
                var modify_unit_id = $("#modify_unit_id").val()
                var modify_laboratory_type = $("#modify_laboratory_type").val()
                var modify_laboratory_attribute = $("#modify_laboratory_attribute").val()
                var modify_laboratory_status = $("#modify_laboratory_status").val()
                var modify_safety_head = $("#modify_safety_head").val()
                var modify_laboratory_people1 = $("#modify_laboratory_people1").val()
                var modify_laboratory_people2 = $("#modify_laboratory_people2").val()
                console.log(modify_classroom_name)
                $.ajax({
                    url:'/admin/modifyLaborary',
                    method:"PUT",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{
                        "id": id,
                        "modify_laboratory_name": modify_laboratory_name,
                        "modify_building_name": modify_building_name,
                        "modify_classroom_name": modify_classroom_name,
                        "modify_unit_id": modify_unit_id,
                        "modify_laboratory_type": modify_laboratory_type,
                        "modify_laboratory_attribute": modify_laboratory_attribute,
                        "modify_laboratory_status": modify_laboratory_status,
                        "modify_safety_head": modify_safety_head,
                        "modify_laboratory_people1": modify_laboratory_people1,
                        "modify_laboratory_people2": modify_laboratory_people2
                    },
                    dataType:"text",
                    success:function(data){
                        window.location()
                    },
                });
            }
        </script>
    </body>
</html>
