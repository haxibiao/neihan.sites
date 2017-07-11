@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ Auth::user()->name }}, 您已成功登录!</h2>
                </div>

                <div class="panel-body">

                    <div class="row">
                        @if(Auth::user()->is_editor)
                        <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">编辑功能:</h3>
                            </div>
                            <div class="panel-body">
                                <a class="btn btn-primary top5" href="/article/create" role="button">创建文章</a>
                                <a class="btn btn-warning top5" href="/article" role="button">管理文章</a>
                                <a class="btn btn-danger top5" href="/category" role="button">管理分类</a>
                            </div>
                        </div>
                        </div>
                        @endif

                        @if(Auth::user()->is_seoer)
                        <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEO功能:</h3>
                            </div>
                            <div class="panel-body">
                                <a class="btn btn-danger top5" href="/user/{{ Auth::user()->id }}/edit" role="button">SEO配置</a>
                                <a class="btn btn-primary top5" href="/traffic" role="button">查看流量</a>
                            </div>
                        </div>
                        </div>
                        @endif

                        @if(Auth::user()->is_admin)
                        <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">管理功能:</h3>
                            </div>
                            <div class="panel-body"> 
                                <a class="btn btn-warning top5" href="/admin/users" role="button">管理用户</a>
                            </div>
                        </div>
                        </div>
                        @endif
                    </div>
                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">流量:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title='流量' chart-data='{{ json_encode($data['traffic']) }}' chart-labels='{!! json_encode($labels['traffic']) !!}'/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">文章:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <line-chart title='文章' chart-data='{{ json_encode($data['article']) }}' chart-labels='{{ json_encode($labels['article']) }}'/>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <div class="panel-heading">                    
                    <h3 class="panel-title">本站新用户:</h3 class="panel-title">
                </div>
                <div class="panel-body">
                    
                        @foreach($users as $user)
                        <div class="col-xs-6">
                            <a href="/user/{{ $user->id }}" class="thumbnail">
                                <img src="{{ get_avatar($user) }}" alt="" class="img img-circle">
                                <p class="strip_title">{{ $user->name }}</p>
                            </a>
                        </div>
                        @endforeach
                </div>
                <div class="panel-footer">
                    <a href="/user" class="btn btn-default">全部用户</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
