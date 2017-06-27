@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">个人面板</div>

                <div class="panel-body">
                    <h2>您已经成功登录!</h2>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <a class="btn btn-primary" href="/article/create" role="button">发表文章</a>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h2>本站最新注册用户:</h2>
                            <div class="list-group">
                                @foreach($users as $user)
                                <a href="/user/{{ $user->id }}" class="list-group-item">
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
    </div>
</div>
@endsection
