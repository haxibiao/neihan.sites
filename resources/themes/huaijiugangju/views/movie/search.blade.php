@php
$hot=array_fill(0, 10,array('id'=>'1','name'=>'顶楼'));
$hot_star=array_fill(0,
6,array('id'=>'1','avatar'=>'https://img-3.hlmw.com.cn/uploads/star/2020-03-22/adf51c9f2ebd124ed0c71c3ab28abe18.jpg','name'=>"李钟硕"
));
$result=array_fill(0, 5,array(
'name'=>'不死者之王2',
'movie_type'=>"动漫",
'id'=>1,
'type_name'=>'明星',
'cover_url'=>"https://cdn-youku-com.diudie.com/app/image/image-5facd03dea2ea1.10509556.jpg",
'introduction'=>"时为2138年。曾卷起一大风潮的虚拟现实体感型网络游戏《YGGDRASIL》即将迎来停服。 玩家飞鼠在曾经以同伴和荣华自傲的根据地纳萨力克地下大坟墓，独自一人安静等待着那一刻。
但，发生了结束时间已过却没有登出的异常事态。 NPC们以自己的意志行动，不止如此，纳萨力克之外展开了从未见过的异世界。 飞鼠为了寻找过去的同伴， 以公会名安兹·乌尔·恭自称，决定在异世界扬名立万。
他与宣誓绝对忠诚的部下一同，向新的地域进军。 将世界收于掌中的死之支配者，于此再临！！下一同，向新的地域进军。 将世界收于掌中的死之支配者，于此再临！！",
'year'=>'2020',
'first_time'=>'2020-10-24',
'producer'=>['导演1','导演2'],
'actors'=>['日野聪',' 原由实',' 上坂堇','加藤英美里',' 内山夕实','加藤将之',' 三宅 ']
));
$keywords="石原里美"
@endphp
@extends('layouts.app')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/search.css') }}">
@endpush

@section('title', '搜索好剧好电影')
@section('keywords', '，在线看电影，无广告无坑人套路')
@section('description', '，在线看电影，无广告无坑人套路，还你一个舒适的追剧环境')

@section('content')
    <div class="container-xl">
        <div class="row col-9 padding-0">
            <p class="keywords">
                搜索关键词"<span class="keyword">{{ $keywords }}</span>"
            </p>
        </div>
        <div class="row">
            <div class="main col-lg-9">
                <div class="search_result">
                    @foreach ($result as $movie)
                        @include('parts/movie.result_item')
                    @endforeach
                    <div>
                        {{-- {{ $result->links() }} --}}
                    </div>
                </div>
            </div>
            <div class="side col-lg-3">
                {{-- <div class="row"> --}}
                    <div class="search_mod module-style">
                        <div class="mod_box" id="hotlist" r-notemplate="true" _r-cid="21" _r-component="hot-board">
                            <div class="mod_title">
                                <h3 class="title">人气港星</h3>
                                <div class="bg_rank_ball"></div>
                            </div>
                            <div class="mod-list">
                                <ol class="hot-list clearfix">
                                    @foreach ($hot_star as $star)
                                        <li class="item item_1">
                                            <p>
                                                <span class="num">{{ $loop->index + 1 }}</span>
                                                <img src={{ $star['avatar'] }} href="/star/{{ $star['id'] }}"
                                                    class="avatar-img">
                                                <a href="/star/{{ $star['id'] }}">
                                                    <span class="name">{{ $star['name'] }}</span>
                                                </a>
                                            </p>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="search_mod module-style">
                        <div class="mod_box" id="hotlist" r-notemplate="true" _r-cid="21" _r-component="hot-board">
                            <div class="mod_title">
                                <h3 class="title">热搜榜单</h3>
                                <div class="bg_rank_ball"></div>
                            </div>
                            <div class="mod-list">
                                <ol class="hot-list clearfix">
                                    @foreach ($hot as $hot_movie)
                                        <li class="item item_1">
                                            <p>
                                                <span class="num">{{ $loop->index + 1 }}</span>
                                                <a href="/movie/{{ $hot_movie['id'] }}">
                                                    <span class="name">{{ $hot_movie['name'] }}</span>
                                                </a>
                                            </p>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                    {{--
                </div> --}}
            </div>
        </div>
    </div>
@endsection
