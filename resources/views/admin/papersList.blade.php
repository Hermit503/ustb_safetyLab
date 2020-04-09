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
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                area:['1000px','550px'],//定义宽和高
                title:'添加用户',//题目
                shadeClose:true,//点击遮罩层关闭
                content: $('#addModel'),//打开的内容
                end:function (res) {
                    $("#addModel").css("display",'none');
                }
            });
        })
        layui.form.render('select', 'add');
    }


    //修改
    function openModifyModak(that){

        var id = $(that).attr("data-id")
        var unit_id = $(that).attr("data-unitId")
        var aqts = $(that).attr("data-aqts")
        var dqaq = $(that).attr("data-dqaq")
        var hxp = $(that).attr("data-hxp")
        var jxjz = $(that).attr("data-jxjz")
        var tzsb = $(that).attr("data-tzsb")
        var xfaq = $(that).attr("data-xfaq")
        var yxsw = $(that).attr("data-yxsw")



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
        })
        $("[id='modify_id']").val(id)
        $("#modify_unit_id").val(unit_id)
        $("#modify_aqts").val(aqts)
        $("#modify_dqaq").val(dqaq)
        $("#modify_hxp").val(hxp)
        $("#modify_jxjz").val(jxjz)
        $("#modify_tzsb").val(tzsb)
        $("#modify_xfaq").val(xfaq)
        $("#modify_yxsw").val(yxsw)
        layui.form.render('select', 'modify');
    }
    //删除
    function openDeleteModak(that){
        var id = $(that).attr("data-id")

        $("[id='delete_id']").val(id);//向模态框中赋值

        layui.use(['layer'],function () {
            var layer = layui.layer,$=layui.$;
            layer.open({
                type:1,//类型
                area:['360px','200px'],//定义宽和高
                title:'删除',//题目
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
                    <cite>试题列表</cite></a>
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
                            <th>单位</th>
                            <th>安全通识题目数量</th>
                            <th>电气题目数量</th>
                            <th>化学题目数量</th>
                            <th>机械建筑题目数量</th>
                            <th>特种设备题目数量</th>
                            <th>消防安全题目数量</th>
                            <th>医学生物安全题目数量</th>
                            <th>操作</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($exams as $exam)
                            <tr>
                                <td>{{$exam['id']}}</td>
                                <td>{{$exam['units']['unit_name']}}</td>
                                <td>{{$exam['aqts']}}</td>
                                <td>{{$exam['dqaq']}}</td>
                                <td>{{$exam['hxp']}}</td>
                                <td>{{$exam['jxjz']}}</td>
                                <td>{{$exam['tzsb']}}</td>
                                <td>{{$exam['xfaq']}}</td>
                                <td>{{$exam['yxsw']}}</td>
                                <td class="td-manage">
                                    <a title="修改" onclick="openModifyModak(this)" href="javascript:;"
                                       data-id="{{$exam['id']}}"
                                       data-unitId="{{$exam['unit_id']}}"
                                       data-aqts = "{{$exam['aqts']}}"
                                       data-dqaq = "{{$exam['dqaq']}}"
                                       data-hxp = "{{$exam['hxp']}}"
                                       data-jxjz = "{{$exam['jxjz']}}"
                                       data-tzsb = "{{$exam['tzsb']}}"
                                       data-xfaq = "{{$exam['xfaq']}}"
                                       data-yxsw = "{{$exam['yxsw']}}"
                                    >
                                        <i class="layui-icon">&#xe63c;</i></a>
                                    <a title="删除" onclick="openDeleteModak(this)" href="javascript:;" data-id="{{$exam['id']}}">
                                        <i class="layui-icon">&#xe640;</i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        {{$exams->links()}}
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
<!--新增题目数量信息-->
<div id="addModel" style="display: none;">
    <div class="layui-container">
        <br><br>
        <div class="layui-row">
            <div class="layui-col-md10 layui-col-md-offset1">
                <form class="layui-form" lay-filter="add">
                    @csrf
                    <div class="layui-form-item">
                        <label class="layui-form-label">单位</label>
                        <div class="layui-input-block">
                            <div class="layui-inline">
                                <select id="new_unit_id" name="new_unit_id" lay-verify="required" lay-filter="new_unit_id" lay-search>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item col-4">
                        <label class="layui-form-label">安全通识题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_aqts" id="new_aqts" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">电气题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_dqaq" id="new_dqaq" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">化学题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_hxp" id="new_hxp" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">机械建筑题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_jxjz" id="new_jxjz" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">特种设备题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_tzsb" id="new_tzsb" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">消防安全题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_xfaq" id="new_xfaq" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">医学生物安全题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="new_yxsw" id="new_yxsw" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" onclick="newExam()">立即提交</button>
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
<!--修改题目数量信息-->
<div id="modifyModel" style="display: none;">
    <div class="layui-container">
        <br><br>
        <div class="layui-row">
            <div class="layui-col-md10 layui-col-md-offset1">
                <form class="layui-form" lay-filter="modify">
                    @csrf
                    <input type="text" id="modify_id" hidden>
                    <div class="layui-form-item">
                        <label class="layui-form-label">单位</label>
                        <div class="layui-input-block">
                            <div class="layui-inline">
                                <select disabled id="modify_unit_id" name="modify_unit_id" lay-verify="required" lay-filter="new_unit_id">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item col-4">
                        <label class="layui-form-label">安全通识题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_aqts" id="modify_aqts" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">电气题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_dqaq" id="modify_dqaq" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">化学题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_hxp" id="modify_hxp" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">机械建筑题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_jxjz" id="modify_jxjz" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">特种设备题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_tzsb" id="modify_tzsb" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">消防安全题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_xfaq" id="modify_xfaq" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">医学生物安全题目数量</label>
                        <div class="layui-input-block">
                            <input type="text" name="modify_yxsw" id="modify_yxsw" required  lay-verify="required" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" onclick="modifyPaper()">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--删除题目数量信息-->
<div id="deleteModel" style="display: none;">
    <div class="layui-container">
        <br><br>
        <div class="layui-row">
            <div class="layui-col-md10 layui-col-md-offset1">
                <form class="layui-form">
                    <input type="text" id="delete_id" name="delete_id" hidden>
                    <p>确认删除？</p>
                    <br><br>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn  layui-btn-danger" onclick="deletePaper()">删除</button>
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
    //下边这段不要删
    layui.use(['laydate', 'form'],
        function() {

    });

    //上传文件的
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

    function newExam() {
        var unit_id = $("#new_unit_id").val()
        var aqts = $("#new_aqts").val()
        var dqaq = $("#new_dqaq").val()
        var hxp = $("#new_hxp").val()
        var jxjz = $("#new_jxjz").val()
        var tzsb = $("#new_tzsb").val()
        var xfaq = $("#new_xfaq").val()
        var yxsw = $("#new_yxsw").val()

        $.ajax({
            url:'/admin/newPaper',
            method:"POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"text",
            data:{
                'unit_id':unit_id,
                'aqts':aqts,
                'dqaq':dqaq,
                'hxp':hxp,
                'jxjz':jxjz,
                'tzsb':tzsb,
                'xfaq':xfaq,
                'yxsw':yxsw
            },
            success:function(data){
                alert(data)
                window.location.reload()
            },
        });
    }

    function modifyPaper() {
        var id = $("#modify_id").val()
        // var unit_id = $("#modify_unit_id").val()
        var aqts = $("#modify_aqts").val()
        var dqaq = $("#modify_dqaq").val()
        var hxp = $("#modify_hxp").val()
        var jxjz = $("#modify_jxjz").val()
        var tzsb = $("#modify_tzsb").val()
        var xfaq = $("#modify_xfaq").val()
        var yxsw = $("#modify_yxsw").val()
        $.ajax({
            url:'/admin/modifyPaper',
            method:"PUT",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"text",
            data:{
                'id':id,
                'aqts':aqts,
                'dqaq':dqaq,
                'hxp':hxp,
                'jxjz':jxjz,
                'tzsb':tzsb,
                'xfaq':xfaq,
                'yxsw':yxsw
            },
            success:function(data){
                alert(data)
                window.location.reload()
            },
        });
    }

    function deletePaper() {
        var id = $("input[name='delete_id']").val()
        $.ajax({
            url:'/admin/deletePaper',
            method:"DELETE",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"text",
            data:{
                'id':id
            },
            sucess:function (data) {
                alert(data)
                window.location.reload()
            }
        })
    }
</script>

</html>
