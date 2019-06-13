@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($msg === "注册成功")
                <div class="alert alert-success" role="alert">
                    {{ $msg }}
                </div>
            @endif

            @if($msg === "注册失败，请重新注册")
                    <div class="alert alert-danger" role="alert">
                        {{ $msg }}
                    </div>
                @endif
        </div>
    </div>
</div>
@endsection
