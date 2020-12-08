@extends('layouts.app')
@section('title')
    收藏的文章 - {{ seo_site_name() }}
@stop
@section('content')
        <div id="bookmarks">
            <div class="main center">
                <div class="page-banner">
                    {{-- <img src="/images/collect-note.png" alt=""> --}}
                    <div class="banner-img collect-note">
                        <div>
                            <i class="iconfont icon-biaoqian"></i>
                            <span>收藏的作品</span>
                        </div>
                    </div>
                </div>
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#articles" aria-controls="articles" role="tab" data-toggle="tab">收藏的作品</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="fade in tab-pane active" id="articles">
                            <ul class="article-list">
                                @if(count($movies)==0)
                                   <blank-content></blank-content>
                                @endif
                                <div class="main">
                                    <div class="movie-list clearfix">
                                        
                                        <table>
                                            @for($h = 0;$h < 3;$h++)
                                                <tr style="height:250px">
                                                    @for($index = 0;$index < 4;$index++)
                                                        @if(($h*4+$index) < count($movies))
                                                            <td style="width:200px">@include('parts.movie_item',['movie'=>$movies[$h*4+$index]])</td>
                                                        @else
                                                            <td style="width:200px"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            @endfor
                                        </table>
                                    </div>
                                    <div class="panel-footer text-center center" style="padding-top:10px">
                                        {{ $movies->links() }}
                                    </div>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@stop