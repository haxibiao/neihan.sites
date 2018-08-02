@extends('layouts.app')
@section('title')
    编辑面板 - {{ env('APP_NAME') }}
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ Auth::user()->name }}，</h2>
                    <p class="small">欢迎进入编辑面板...</p>
                </div>

                <div class="panel-body">
                    <div class="row">
 

                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">文章:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <line-chart title='文章' chart-data='{{ json_encode($data['article']) }}' chart-labels='{{ json_encode($labels['article']) }}'/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">流量:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title-one='流量' chart-data='{{ json_encode($data['traffic']) }}' chart-labels='{!! json_encode($labels['traffic']) !!}' 
                                    color="orange"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">微信流量:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title-one='微信流量' chart-data='{{ json_encode($data['traffic_wx']) }}' chart-labels='{!! json_encode($labels['traffic_wx']) !!}' 
                                    color="green"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">昨日其他编辑</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">发布的文章数:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title-one='文章' chart-data='{{ json_encode($data['article_editors']) }}' chart-labels='{!! json_encode($labels['article_editors']) !!}'/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">在本站的流量:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title-one='流量' chart-data='{{ json_encode($data['traffic_editors']) }}' chart-labels='{!! json_encode($labels['traffic_editors']) !!}' color="orange"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">在本站微信流量:</h3>
                                </div>
                                <div class="panel-body"> 
                                    <bar title-one='流量' chart-data='{{ json_encode($data['wxtraffic_editors']) }}' chart-labels='{!! json_encode($labels['traffic_editors']) !!}' color="green"/>
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
                    <h3 class="panel-title">
                        {{ $user->name }}
                    </h3>
                </div>
                <div class="panel-body">
                    <img alt="{{ $user->name }}" class="img img-circle avatar_small" src="{{ $user->avatar() }}">
                        <h4>
                            <a href="/user/{{ $user->id }}">{{ $user->name }}</a>
                        </h4>
                        <p>
                            {{ $user->email }}
                        </p>
                        <p>
                            {{ $user->introduction }}
                        </p>
                    </img>
                </div>
            </div>
            <div class="row">
                @editor
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">专题:</h3>
                        </div>
                        <div class="panel-body admin-section">
                            <a class="btn btn-sm btn-primary" href="/category/create" role="button">创建专题</a>
                            <a class="btn btn-sm btn-danger" href="/category/list" role="button">管理专题</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">文章:</h3>
                        </div>
                        <div class="panel-body admin-section">
                            <a class="btn btn-sm btn-primary" href="/article/create" role="button">创建文章</a>
                            <a class="btn btn-sm btn-warning" href="/article" role="button">管理文章</a>
                            <a class="btn btn-sm btn-warning" href="/drafts" role="button">管理草稿</a>
                        </div>
                    </div>
                </div>
                @endeditor
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">视频:</h3>
                        </div>
                        <div class="panel-body admin-section">
                            <a class="btn btn-sm btn-primary" href="/user/{{ Auth::id() }}/videos" role="button">我的视频</a>
                            <a class="btn btn-sm btn-warning" href="/video/list" role="button">管理视频</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">片段:</h3>
                        </div>
                        <div class="panel-body admin-section">
                            <a class="btn btn-sm btn-primary" href="/snippet/create" role="button">创建片段</a>
                            <a class="btn btn-sm btn-warning" href="/snippet" role="button">管理片段</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">管理:</h3>
                        </div>
                        <div class="panel-body admin-section">
                            <a class="btn btn-sm btn-primary" href="/searchQuery" role="button">搜索记录</a>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>
@endsection
