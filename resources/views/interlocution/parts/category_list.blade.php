{{-- 问答分类 --}}
<div class=" col-xs-12">
    <div class="classification">
      @foreach($categories as $category)
        <a class="collection active" href="/{{ $category->name_en }}" target="_blank">
            <img src="{{ $category->logo }}"/>
            <div class="name">
                {{ $category->name }}
            </div>
        </a>
      @endforeach
  {{--       <a class="collection" href="/category/2" target="_blank">
            <img src="/images/details_04.jpeg"/>
            <div class="name">
                娱乐
            </div>
        </a>
        <a class="collection" href="/category/2" target="_blank">
            <img src="/images/category_08.jpg"/>
            <div class="name">
                恋爱
            </div>
        </a>
        <a class="collection" href="/category/2" target="_blank">
            <img src="/images/category_03.jpg"/>
            <div class="name">
                游戏
            </div>
        </a>
        <a class="collection" href="/category/2" target="_blank">
            <img src="/images/category_04.jpeg"/>
            <div class="name">
                昵称
            </div>
        </a> --}}
{{--         <a class="collection more_collection" href="/interlocution/more" target="_blank">
            <div class="name">
                更多
                <i class="iconfont icon-youbian">
                </i>
            </div>
        </a> --}}
    </div>
</div>
