@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ Auth::user()->name }}, 欢迎回来，下面是您的个人面板</h2>
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

            <div class="panel panel-primary">
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
                    <img alt="{{ $user->name }}" class="img img-circle" src="{{ get_avatar($user) }}">
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
                @if(Auth::user()->is_editor || Auth::user()->is_admin)
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">文章:</h3>
                        </div>
                        <div class="panel-body">
                            <a class="btn btn-primary top5" href="/article/create" role="button">创建文章</a>
                            <a class="btn btn-warning top5" href="/article" role="button">管理文章</a>
                            <a class="btn btn-danger top5" href="/category?type=article" role="button">文章分类</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">视频:</h3>
                        </div>
                        <div class="panel-body">
                            <a class="btn btn-primary top5" href="/video/create" role="button">创建视频</a>
                            <a class="btn btn-warning top5" href="/video" role="button">管理视频</a>
                            <a class="btn btn-danger top5" href="/category?type=video" role="button">视频分类</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">片段:</h3>
                        </div>
                        <div class="panel-body">
                            <a class="btn btn-primary top5" href="/snippet/create" role="button">创建片段</a>
                            <a class="btn btn-warning top5" href="/snippet" role="button">管理片段</a>
                            <a class="btn btn-danger top5" href="/category?type=snippet" role="button">片段分类</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">屏蔽词:</h3>
                        </div>
                        <div class="panel-body">
                            <a class="btn btn-primary top5" href="/badword/create" role="button">创建屏蔽词</a>
                            <a class="btn btn-warning top5" href="/badword" role="button">所有屏蔽词</a>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::user()->is_seoer || Auth::user()->is_admin)
                <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">SEO:</h3>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-danger top5" href="/user/{{ Auth::user()->id }}/edit" role="button">SEO配置</a>
                        <a class="btn btn-primary top5" href="/traffic" role="button">查看流量</a>
                    </div>
                </div>
                </div>
                @endif

                @if(Auth::user()->is_admin)
                <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">管理:</h3>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-warning top5" href="/admin/users" role="button">管理用户</a>
                    </div>
                </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
