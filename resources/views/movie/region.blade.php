@extends('layouts.movie')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/category.css') }}">
@endpush

@section('content')
    <div class="movie_category">
        <div class="container-xl padding-0">
            <div class="category-nav-panel">
                <div class="panel-box">
                    <div class="panel_head clearfix">
                        <h3 class="title">
                            {{ $cate }}
                        </h3>
                    </div>
                    <div class="panel_body">
                        <div class="nav-item">
                            <ul class="nav_list clearfix">
                                <li><a class="btn-order btn-muted">排序</a></li>
                                @php
                                $orders = [
                                'year' => '时间',
                                'hits' => '人气',
                                'score' => '评分',
                                ];
                                @endphp

                                {{-- FIXME：绑定真实的排序 --}}
                                @foreach ($orders as $order => $word)
                                    @if ((request('order') ?? 'time') == $order)
                                        <li>
                                            <a class="btn-order active" href="?order={{ $order }}">{{ $word }}</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="btn-order" href="?order={{ $order }}">{{ $word }}</a>
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
                                @include('movie.parts.movie_item')
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="panel-footer text-center center" style="padding-top:10px">
                    {{ $movies->links() }}
                </div>
            </div>
            <div>

            </div>
        </div>
    </div>
@endsection
