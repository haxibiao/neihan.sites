@extends('layouts.movie')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/collection.css') }}">
@endpush

@section('content')
    <div class="movie_collection">
        <div class="container-xl padding-0">
            <div class="collection-nav-panel">
                <div class="panel-box">
                    <div class="panel_head clearfix">
                        <h3 class="title">
                            个人收藏夹
                        </h3>
                    </div>
                    <div class="panel_body">
                        <div class="nav-item">
                            <ul class="nav_list clearfix">
                                <li><a class="btn-order btn-muted">分类</a></li>
                                @php
                                $orders = [
                                'like' => '喜欢',
                                'favorite' => '收藏',
                                'history' => '足迹',
                                ];
                                @endphp
                                @foreach ($orders as $order => $word)
                                    @if ($word == $cate)
                                        <li>
                                            <a class="btn-order active" href="/movie/collection?type={{ $order }}">{{ $word }}</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="btn-order" href="/movie/collection?type={{ $order }}">{{ $word }}</a>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main">
                <div class="movie-list clearfix">
                    <ul class="row">
                        @foreach ($movies as $movie)
                            <li class="col-lg-2 col-md-3 col-sm-3 col-xs-4 padding-10">
                                @include('movie.parts.collecte_movie_item')
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel-footer text-center center" style="padding-top:10px">
                    {{ $movies->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
