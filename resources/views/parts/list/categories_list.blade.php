@extends('layouts.app')

@section('title')
    热门专题 - 爱你城
@stop
@section('content')
<div id="categories">
    <div class="container">
        <div class="recommend">
            <img src="/images/recommend_banner.png"/>
            <a class="help" href="javascript:;" target="_blank">
                <i class="iconfont icon-send1179291easyiconnet">
                </i>
                如何创建并玩转专题
            </a>
            <div>
                <!-- Nav tabs -->
                <ul class="trigger_menu" role="tablist">
                    <li class="active" role="presentation">
                        <a aria-controls="tuijian" data-toggle="tab" href="#tuijian" role="tab">
                            <i class="iconfont icon-tuijian1">
                            </i>
                            推荐
                        </a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                            <i class="iconfont icon-huo">
                            </i>
                            热门
                        </a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="chengshi" data-toggle="tab" href="#chengshi" role="tab">
                            <i class="iconfont icon-icon2">
                            </i>
                            城市
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tuijian" role="tabpanel">
                        <div class="row clearfix">
                           @include('parts.list.categories_list_item',['categories'=>$data['commend']])
                          {{--  <category-list api="{{ request()->path() }}?recommend=1" start-page="2"></category-list> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="huo" role="tabpanel">
                        <div class="row clearfix">
                           @include('parts.list.categories_list_item',['categories'=>$data['hot']])
                       {{--     <category-list api="{{ request()->path() }}?hot=1" start-page="2"></category-list> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="chengshi" role="tabpanel">
                        <div class="row clearfix">
                           @include('parts.list.categories_list_item',['categories'=>$data['city']])
                           {{-- <category-list api="{{ request()->path() }}?city=1" start-page="2"></category-list> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
