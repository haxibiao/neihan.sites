{{-- 首页显示的专题分类 --}}
<div class="classification">
@foreach($categories as $category)
    <a class="collection" href="/{{ $category->name_en }}" target="_blank">
        <img src="{{ get_category_logo($category->logo) }}"/>
        <div class="name">
            {{ $category->name }}
        </div>
    </a>
@endforeach
    <a class="collection more_hot_collection" href="/v1/categories" target="_blank">
        <div class="name">
            更多热门专题
            <i class="iconfont icon-youbian">
            </i>
        </div>
    </a>
</div>