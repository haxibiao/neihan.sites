@php
$movies = array_fill(0, 16, 'movieObject');
$category=['分类'=>'动漫','类型'=>'喜剧','年份'=>'2015','排序'=>'人气'];
$categories=[
'分类'=>['港剧','动漫','电影','综艺'],
'类型'=>['喜剧','动作','爱情','科幻','动画','悬疑','惊悚','恐怖','犯罪','传记','历史','战争','奇幻','冒险','灾难','古装','情色','真人秀','青春','家庭','纪录片'],
'年份'=>['2005','2006','2007','2008','2009','2010','2011','2012','2013','2014','2015','2016','2017','2018','2019','2020'],
'排序'=>['时间','人气','评分'],
];
@endphp

@section('title', '更多分类')
@section('keywords', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')
@section('description', '怀旧港剧' . '，在线好剧免费看，精品电影免费看，无需登录注册即可在线追剧，无坑人套路无广告，还你一个干净舒适的追剧环境')

    @extends('layouts.app')

    @push('head-styles')
        <link rel="stylesheet" href="{{ mix('css/movie/category.css') }}">
    @endpush

@section('content')
    <div class="movie_category">
        <div class="container-xl padding-0">
            <div class="category-nav-panel">
                <div class="panel-box">
                    <div class="panel_head clearfix">
                        @foreach ($category as $item => $current)
                            <div class="nav-item underline">
                                <ul class="nav_list clearfix">
                                    <li><a class="btn-order btn-muted">{{ $item }}</a></li>
                                    @foreach ($categories[$item] as $word)
                                        @if ($current == $word)
                                            <li><a class="btn-order active"
                                                    href="/category/?order={{ $word }}">{{ $word }}</a>
                                            </li>
                                        @else
                                            <li><a class="btn-order" href="/category/?order={{ $word }}">{{ $word }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                        {{-- <h3 class="title">
                            动漫
                        </h3> --}}
                    </div>
                    {{-- <div class="panel_body">
                        <div class="nav-item">
                            <ul class="nav_list clearfix">
                                <li><a class="btn-order btn-muted">排序</a></li>
                                @php
                                $orders = [
                                'time' => '时间',
                                'hot' => '人气',
                                'score' => '评分',
                                ];
                                @endphp
                                @foreach ($orders as $order => $word)
                                    @if ($orderBy == $order)
                                        <li><a class="btn-order active" href="/category/?order={{ $order }}">{{ $word }}</a>
                                        </li>
                                        @else
                                        <li><a class="btn-order" href="/category/?order={{ $order }}">{{ $word }}</a>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="main module-style">
                <div class="movie-list clearfix">
                    <ul class="row movie-row">
                        @foreach ($movies as $movie)
                            <li class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-0">
                                @include('parts/movie.movie_item')
                            </li>
                        @endforeach
                    </ul>
                </div>
                {{-- <div class="panel-footer text-center center" style="padding-top:10px">
                    {{ $movies->links() }}
                </div> --}}
            </div>
            <div>
                <div class="row justify-content-center ">
                    <div class="bottom-page paging-box-big">
                        <ul class="pagination">
                            <li><a href="javascript:;" class="first">首页</a></li>
                            <li><a href="javascript:;" class="back">上一页</a></li>
                            <li><a href="javascript:;" class="hide-xs active">1</a></li>
                            <li><a href="javascript:;" class="hide-xs">2</a></li>
                            <li><a href="javascript:;" class="hide-xs">3</a></li>
                            <li><a href="javascript:;" class="hide-xs">4</a></li>
                            <li><a href="javascript:;" class="hide-xs">...</a></li>
                            <li><a href="javascript:;" class="hide-xs">79</a></li>
                            <li><a href="javascript:;" class="show-xs-inline-block hide-sm active">1/79</a></li>
                            <li><a href="javascript:;" class="next">下一页</a></li>
                            <li><a href="javascript:;" class="last">尾页</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="page-jump">
                        至<input type="text" class="input" />页
                        <a href="javascript:;" class="forward">跳转</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
