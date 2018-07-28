@extends('layouts.black')

@section('title')
	视频: {{ $video->article->title }} - @if($video->article && $category){{ $category->name }}@endif {{ env('APP_NAME') }}
@stop

@push('seo_og_result') 
@if($video->article)
<meta property="og:type" content="{{ $video->article->type }}" />
<meta property="og:url" content="https://{{ get_domain() }}/video/{{ $video->article->video_id }}" />
<meta property="og:title" content="{{ $video->article->title }}" />
<meta property="og:description" content="{{ $video->article->description() }}" />
<meta property="og:image" content="{{ $video->article->cover() }}" />
<meta name="weibo: article:create_at" content="{{ $video->article->created_at }}" />
<meta name="weibo: article:update_at" content="{{ $video->article->updated_at }}" />
@endif
@endpush  

@section('logo')
    <a class="logo" href="/">
        <img src="/logo/{{ get_domain() }}.text.png" alt="">
    </a>
@stop

@section('content')
<div class="player-container">
    
    <div class="playerBox">
        <div class="container">
            <div class="player-basic clearfix">
                <div class="playerArea col-sm-8">
                    <div class="h5-player">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video controls="" poster="{{ $video->article->cover() }}" preload="auto" autoplay="true">
                                <source src="{{ $video->url() }}" type="video/mp4"> 
                                </source> 
                            </video>
                        </div> 
                    </div>
                    <div class="h5-option"> 
                       <like id="{{ $video->article->id }}" type="article" is-login="{{ Auth::check() }}"></like> 
                       <div class="share-circle">
                            <a data-action="weixin-share" data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="分享到微信">
                                 <i class="iconfont icon-weixin1 weixin"></i>
                            </a>
                            <a href="javascript:void((function(s,d,e,r,l,p,t,z,c){var%20f='http://v.t.sina.com.cn/share/share.php?appkey=1881139527',u=z||d.location,p=['&amp;url=',e(u),'&amp;title=',e(t||d.title),'&amp;source=',e(r),'&amp;sourceUrl=',e(l),'&amp;content=',c||'gb2312','&amp;pic=',e(p||'')].join('');function%20a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width-440)/2,',top=',(s.height-430)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent,'','','', '《{{$video->article->title }}》（ 分享自 @爱你城 ）','{{ url('/video/'.$video->article->video_id) }}?source=weibo','页面编码gb2312|utf-8默认gb2312'));" data-toggle="tooltip" data-placement="top" title="分享到微博">
                                <i class="iconfont icon-sina weibo"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="listArea col-sm-4 hidden-xs">
                    <div class="classify">
                        @if($category)
                            <div>
                                <a href="/{{ $category->name_en }}" class="avatar">
                                    <img src="{{ $category->logo() }}" alt="{{ $category->name }}">
                                </a>
                                <div class="classify-info">
                                    <div>
                                        {{-- 分类 --}}
                                        <a href="/{{ $category->name_en }}">{{$category->name}}</a>
                                    </div> 
                                    {{-- 关注数 --}}
                                    <span>{{$category->count_follows}}人关注</span> 
                                    <span>- {{$category->count_videos}}个视频</span> 
                                </div>
                            </div>
                                
                            <div class="button-vd clearfix">
                                <follow  
                                    type="categories" 
                                    id="{{ $category->id }}"   
                                    user-id="{{ user_id() }}" 
                                    followed="{{ is_follow('categories', $category->id) }}"
                                    size-class="btn-md"
                                    >
                                </follow>
                            </div>
                        @endif
                    </div> 
                    <div class="related-video">
                        <ul class="video-list">
                            @foreach($data['related'] as $article) 
                                <li class="video-item hz">
                                    <a href="{{$article->content_url()}}" class="clearfix" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">  
                                        <div class="cover">
                                            <img src="{{ $article->primaryImage() }}" alt="{{ $article->title }}">
                                            {{-- 时长 --}}
                                            <span class="duration">@sectominute($article->video->duration)</span>
                                        </div>
                                        <div class="info">
                                            <div class="video-title">{{ $article->title }}</div> 
                                            <span class="amount">
                                                {{-- 播放量 --}}
                                                {{ $article->hits }}次播放
                                            </span>
                                        </div>
                                    </a> 
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="video-info">
                <div class="video-title">
                    {{ $video->article->title }}
                </div>                 
                <div class="video-description">
                    {{ $video->article->body }}
                </div> 
                <div class="desc">
                    <span class="upload-time hidden-xs">上传于 {{$video->createdAt()}}</span> 
                    <span class="upload-user">
                        @if($video->user)
                        <a href="/user/{{ $video->user_id }}" class="sub">
                            <img src="{{ $video->user->avatar }}" alt="{{ $video->user->name }}">
                            <span>{{ $video->user->name }}</span>
                        </a>
                        @endif
                        @if( $video->user && !$video->user->isSelf() )
                            <span class="button-vd">
                                <follow 
                                    type="users" 
                                    id="{{ $video->user->id }}" 
                                    user-id="{{ user_id() }}" 
                                    followed="{{ is_follow('users', $video->user->id) }}"
                                    size-class="btn-md"
                                    >
                                </follow>
                            </span>
                        @endif
                    </span>
                    <div class="pull-right">
                        <a class="btn-base btn-light btn-sm" href="/video/{{ $video->id }}/edit">编辑视频</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sectionBox">
        <div class="container clearfix">
            <div class="row">
                <div class="col-sm-8">  
                    {{-- 评论中心 --}} 
                    <comments comment-replies={{ $video->article->count_replies }} type="articles" id="{{ $video->article->id }}" author-id="{{ $video->user_id }}"></comments>
                </div>
                <div class="guess-like col-sm-4 hidden-xs">
                    <div class="guessVideo">
                        <h4></h4>
                        <ul class="video-list"> 
                            <div class="top10">
                                
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @include('parts.footer')
    </div>
    

</div>
@stop

@push('scripts')
    @include('parts.js_for_app')
@endpush
@push('modals')
  {{-- 分享到微信 --}}
  <modal-share-wx  url="{{ url()->full() }}" aid="{{ $video->article->video_id }}"></modal-share-wx>
@endpush