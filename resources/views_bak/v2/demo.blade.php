@extends('v2.layouts.blank')

@section('title')
    测试组件 - 爱你城
@stop
@section('content')
    <div class="container" style="margin-top: 40px;">
        {{-- 轮播图 --}}
        <div class="posters" id="poster"></div>
        {{-- 分类专题按钮 --}}
        <a class="collection" href="" target="_blank">
            <img src="/images/category_01.jpeg"/>
            <div class="name">
                王者荣耀
            </div>
        </a>
        {{-- 更多分类专题按钮 --}}
        <a class="collection more_collection" href="" target="_blank">
            <div class="name">
                更多热门专题
                <i class="iconfont icon-youbian">
                </i>
            </div>
        </a>
    </div>
@stop

@push('scripts')
<script>
    var options = {
    'container':'#poster',
    'data':[
        ['/v2/detail','/images/carousel001.jpg'],
        ['/v2/detail','/images/carousel002.jpg'],
        ['/v2/detail','/images/carousel003.jpg'],
        ['/v2/detail','/images/carousel004.jpg'],
        ['/v2/detail','/images/carousel005.jpg'],
        ['/v2/detail','/images/carousel006.jpg'],
        ['/v2/detail','/images/carousel007.jpg'],
        ['/v2/detail','/images/carousel008.jpg']
    ],
    'speed':'5000',
    'auto':true
    }
    let poster = new Poster(options);
    poster.init();
</script>
@endpush