@extends('layouts.movie')

@push('head-styles')
    <link rel="stylesheet" href="{{ mix('css/movie/search.css') }}">
@endpush

@section('content')
    <div class="row justify-content-center search_container">
        <div class="row container-xl padding-0">
            <div class="main col-xl-9 col-lg-9">
                <div class="search_result">
                    @foreach ($result as $movie)
                        @include('movie.parts.result_item')
                    @endforeach
                    <div>
                        {{ $result->links() }}
                    </div>
                    @if (count($result) < 1)
                        <div class="result_tips result_empty" rep-tpl="true" r-notemplate="true">
                            <div class="tips_title">抱歉，没有找到“<em class="hl">{{ $queryKeyword }}</em>”的相关视频</div>
                            <div class="tips_desc">{{ seo_site_name() }}建议您：缩短搜索词 或 更换搜索词</div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="side col-xl-3 col-lg-3">
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
                                        <span class="num">{{ $loop->index + 1 }}</span>
                                        <span> <a href="/movie/{{ $hot_movie->id }}">{{ $hot_movie->name }}</a></span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('foot-scripts')
<script type="text/javascript" src="{{ mix('js/movie/search.js') }}"></script>
@endpush --}}
