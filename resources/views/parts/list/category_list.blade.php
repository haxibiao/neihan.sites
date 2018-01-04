{{-- 首页显示的专题分类 --}}
<div class="classification">
@foreach($categories as $category)
    <a class="collection" href="/{{ $category->name_en }}" target="_blank">
        <img src="{{ $category->smallLogo() }}"/>
        <div class="name">
            {{ $category->name }}
        </div>
    </a>
@endforeach
    <a class="collection more_collection" href="/cate" target="_blank">
        <div class="name">
            更多热门专题
            <i class="iconfont icon-youbian">
            </i>
        </div>
    </a>
</div>