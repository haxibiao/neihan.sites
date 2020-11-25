@extends('layouts.movie')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/search.css') }}">
@endpush

@section('content')
    <div class="search_container">
        <div class="container-xl padding-0">
            <div class="side">
                <div class="search_mod">
                    <div class="mod_box" id="hotlist" r-notemplate="true" _r-cid="21" _r-component="hot-board">
                        <div class="mod_title">
                            <h3 class="title">热搜榜单</h3>
                            <div class="bg_rank_ball"></div>
                        </div>
                        <div class="mod-list">
                            <ol class="hot-list clearfix">
                                @foreach ($hot as $hot_movie)
                                    <li class="item item_1">
                                        <a href="/movie/{{ $hot_movie->id }}">
                                            <span class="num">{{ $loop->index + 1 }}</span>
                                            <span class="name">{{ $hot_movie->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main">
                <div class="search_result">
                    @foreach ($result as $movie)
                        @include('movie.parts.result_item')
                    @endforeach
                    <div>
                        {{ $result->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('foot-scripts')
<script type="text/javascript" src="{{ mix('js/movie/search.js') }}"></script>
@endpush --}}
