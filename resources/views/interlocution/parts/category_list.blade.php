{{-- 问答分类 --}}
<div class=" col-xs-12">
    <div class="classification">
        <a class="collection pay fixed" href="" target="_blank">
            <img src="/images/pay_question.png"/>
            <div class="name">
                付费
            </div>
        </a>
        <div class="collect fixed">
            <a class="collection active" href="" target="_blank">
                <img src="/images/new_huo.jpg"/>
                <div class="name">
                    热门
                </div>
            </a>
            @foreach($categories as $category)
            <a class="collection" href="/{{ $category->name_en }}" target="_blank">
                <img src="{{ $category->logo }}"/>
                <div class="name">
                    {{ $category->name }}
                </div>
            </a>
            @endforeach
        </div>
        <a class="collection more_collection fixed" href="/interlocution/more" target="_blank">
            <div class="name">
                更多
                <i class="iconfont icon-youbian">
                </i>
            </div>
        </a>
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
