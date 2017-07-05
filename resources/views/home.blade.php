@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">个人面板</div>

                <div class="panel-body">
                    <h2>{{ Auth::user()->name }}, 您已成功登录!</h2>

                    <div class="col-md-12">
                        {!! Form::open(['method' => 'POST', 'route' => ['user.update', Auth::user()->id], 'class' => 'form-horizontal', 'enctype' => "multipart/form-data"]) !!}
                            {!! Form::hidden('_method', 'put') !!}
                            <div class="form-group{{ $errors->has('qq') ? ' has-error' : '' }}">
                                {!! Form::label('qq', 'QQ') !!}
                                {!! Form::text('qq', $user->qq, ['class' => 'form-control', 'required' => 'required']) !!}
                                <small class="text-danger">{{ $errors->first('qq') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                                {!! Form::label('avatar', '头像') !!}
                                {!! Form::file('avatar') !!}
                                <p class="help-block">建议使用本人真实头像</p>
                                <small class="text-danger">{{ $errors->first('avatar') }}</small>
                            </div>

                            <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
                                {!! Form::label('introduction', '自我介绍') !!}
                                {!! Form::textarea('introduction', $user->introduction, ['class' => 'form-control', 'required' => 'required']) !!}
                                <small class="text-danger">{{ $errors->first('introduction') }}</small>
                            </div>
                        
                            <div class="btn-group pull-right">
                                {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
                                {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
                            </div>
                        
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>您可以的操作:</h2>
                </div>
                <div class="panel-body">
                    @if(Auth::user()->is_editor)
                    <a class="btn btn-primary top5" href="/article/create" role="button">创建文章</a>
                    <a class="btn btn-warning top5" href="/article" role="button">管理文章</a>
                    <a class="btn btn-danger top5" href="/category" role="button">管理分类</a>
                    @endif

                    @if(Auth::user()->is_admin)
                    <a class="btn btn-warning top5" href="/admin/users" role="button">管理用户</a>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">                    
                    <h2>本站新用户:</h2>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($users as $user)
                            <a href="/user/{{ $user->id }}" class="list-group-item">
                                <img src="{{ get_avatar($user) }}" alt="" class="img img-circle">
                                <h4 class="list-group-item-heading">{{ $user->name }}</h4>
                                <p class="list-group-item-text">{{ $user->email }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
