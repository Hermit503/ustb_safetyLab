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
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script>
            //添加新单位
            function openAddModak(){
                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'添加单位',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#addModel')//打开的内容
                    });
                })
            }
            //修改单位
            function openModifyModak(that){
                var id = $(that).attr("data-id")
                var unitName = $(that).attr("data-unitName")
                var unitType = $(that).attr("data-unitType")

                $("[id='modifyUnitId']").val(id);//向模态框中赋值
                $("[id='modifyUnitName']").val(unitName);
                $("[id='modifyUnitType']").val(unitType);

                layui.use(['layer'],function () {
                    var layer = layui.layer,$=layui.$;
                    layer.open({
                        type:1,//类型
                        area:['1000px','400px'],//定义宽和高
                        title:'修改单位',//题目
                        shadeClose:true,//点击遮罩层关闭
                        content: $('#modifyModel')//打开的内容
                    });
                })
                layui.form.render('select', 'modify');
            }
            // //删除单位
            // function openDeleteModak(that){
            //     var id = $(that).attr("data-id")
            //     var unitName = "确认删除"+$(that).attr("data-unitName")+"?"
            //
            //     $("[id='deleteId']").val(id);//向模态框中赋值
            //     $("[id='deleteUnitName']").text(unitName);
            //     layui.use(['layer'],function () {
            //         var layer = layui.layer,$=layui.$;
            //         layer.open({
            //             type:1,//类型
            //             area:['360px','200px'],//定义宽和高
            //             title:'确认删除？',//题目
            //             shadeClose:true,//点击遮罩层关闭
            //             content: $('#deleteModel')//打开的内容
            //         });
            //     })
            // }
        </script>
    </head>

    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">基本管理</a>
                <a>
                <cite>单位列表</cite></a>
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
                                        <th>单位名称</th>
                                        <th>单位类型</th>
                                        <th>创建时间</th>
                                        <th>修改时间</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                    <tr>
                                        <td>{{$unit['unit_name']}}</td>
                                        <td>{{$unit['unit_type']}}</td>
                                        <td>{{$unit['created_at']}}</td>
                                        <td>{{$unit['updated_at']}}</td>
                                        <td class="td-manage">
                                            <a title="修改" data-id="{{$unit['id']}}" data-unitName="{{$unit['unit_name']}}" data-unitType="{{$unit['unit_type']}}" onclick="openModifyModak(this)" href="javascript:;">
                                                <i class="layui-icon">&#xe63c;</i></a>
{{--                                            <a title="删除" class="a" data-unitName="{{$unit['unit_name']}}" data-id="{{$unit['id']}}" onclick="openDeleteModak(this)" href="javascript:;">--}}
{{--                                                <i class="layui-icon">&#xe640;</i></a>--}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                    {{$units->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--新增单位-->
        <div id="addModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form">
                            @csrf
                            <div class="layui-form-item">
                                <label class="layui-form-label">单位名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="new_unit_name" required  lay-verify="required" placeholder="请输入单位名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">单位类型</label>
                                <div class="layui-input-block">
                                    <select id="new_unit_type" name="new_unit_type" lay-verify="required">
                                        <option value="二级单位">二级单位</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="newUnit()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--修改单位-->
        <div id="modifyModel" style="display: none;">
            <div class="layui-container">
                <br><br>
                <div class="layui-row">
                    <div class="layui-col-md10 layui-col-md-offset1">
                        <form class="layui-form" lay-filter="modify">
                            <input type="text" name="id" id="modifyUnitId" hidden>
                            <div class="layui-form-item">
                                <label class="layui-form-label">单位名称</label>
                                <div class="layui-input-block">
                                    <input type="text" id="modifyUnitName" name="modify_unit_name" required  lay-verify="required" placeholder="请输入单位名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">单位类型</label>
                                <div class="layui-input-block">
                                    <select id="modifyUnitType" name="modify_unit_type" lay-verify="required">
                                        <option value="二级单位">二级单位</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" onclick="modifyUnit()">立即提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--删除单位-->
{{--        <div id="deleteModel" style="display: none;">--}}
{{--            <div class="layui-container">--}}
{{--                <br><br>--}}
{{--                <div class="layui-row">--}}
{{--                    <div class="layui-col-md10 layui-col-md-offset1">--}}
{{--                        <form class="layui-form">--}}
{{--                            <input type="text" id="deleteId" hidden>--}}
{{--                            <p id="deleteUnitName"></p>--}}
{{--                            <br><br>--}}
{{--                            <div class="layui-form-item">--}}
{{--                                <div class="layui-input-block">--}}
{{--                                    <button class="layui-btn  layui-btn-danger" onclick="deleteUnit()">删除</button>--}}
{{--                                    <button type="reset" class="layui-btn layui-btn-primary">取消</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
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
            function newUnit(){
                var unitName = $("input[name='new_unit_name']").val()
                var unitType = $("#new_unit_type").val()
                console.log(unitType)
                $.ajax({
                    url:'/admin/newUnit',
                    method:"POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{
                        "unit_name": unitName,
                        "unit_type": unitType
                    },
                    dataType:"text",
                    success:function(data){
                        window.location()
                    },
                });
            }

            function modifyUnit(){
                var unitName = $("input[name='modify_unit_name']").val()
                var unitType = $("#modifyUnitType").val()
                var id = $("#modifyUnitId").val()
                $.ajax({
                    url:'/admin/modifyUnit?id='+id+'&unit_name='+unitName+'&unit_type='+unitType,
                    method:"PUT",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType:"text",
                    success:function(data){
                        window.location()
                    },
                });
            }

            // function deleteUnit(){
            //     var id = $("#deleteId").val()
            //     $.ajax({
            //         url:'/admin/deleteUnit?id='+id,
            //         method:"DELETE",
            //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            //         dataType:"text",
            //         success:function(data){
            //             window.location()
            //         },
            //     });
            // }
        </script>
    </body>
</html>
