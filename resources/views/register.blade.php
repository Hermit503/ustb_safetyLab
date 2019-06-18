@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('注册') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('姓名') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user_id" class="col-md-4 col-form-label text-md-right">{{ __('工号') }}</label>

                            <div class="col-md-6">
                                <input id="user_id" type="text" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{ old('user_id') }}" required>

                                @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sex" class="col-md-4 col-form-label text-md-right">{{ __('性别') }}</label>

                            <div class="col-md-6">
                                <select class="form-control @error('sex') is-invalid @enderror" id="sex" name="sex" value="{{old('sex')}}">
                                    <option name="man">男</option>
                                    <option name="woman">女</option>
                                </select>
                                @error('sex')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('手机号') }}</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required>
                                @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('职称') }}</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!--TODO:单位没定，可以设置为select-->
                        <div class="form-group row">
                            <label for="unit_id" class="col-md-4 col-form-label text-md-right">{{ __('单位名称') }}</label>

                            <div class="col-md-6">
                                {{--TODO:单位应该从数据库取，方便修改--}}
                                <select class="form-control @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" value="{{old('unit_id')}}" onchange="getUnit()">
                                    <option name="index" value="0">--------请选择---------</option>
                                    <option name="index" value="1">网络中心</option>
                                    <option name="index" value="2">实验室管理中心</option>
                                    <option name="index" value="3">信息工程学院</option>
                                    <option name="index" value="4">城建学院</option>
                                    <option name="index" value="5">材料系</option>
                                </select>
                                @error('unit_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <script>
                            function getUnit(){
                                var vs = $("#unit_id").val();
                                $("#parent_id").empty();
                                $.ajax({
                                    url:'{{$app_url}}/tool',
                                    data:{
                                        "unit_id":vs
                                    },
                                    success:function(data){
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
                                        }
                                        $('#parent').css('display','block');
                                    },
                                    dataType:"text"
                                });
                            }
                        </script>
                        {{-- 上级领导--}}
                        <div id="parent" name="parent" style="display: none">
                            <div class="form-group row">
                                <label for="parent_id" class="col-md-4 col-form-label text-md-right">{{ __('上级领导') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id" value="{{old('parent_id')}}">
                                        {{-- for循环输出选择单位的领导（ 1.所属单位与选择的一致; 2.看角色，角色不是教师）--}}

                                    </select>
                                    @error('unit_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密码') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('重复密码') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('注册') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
