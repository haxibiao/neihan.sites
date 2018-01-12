{{-- 精选文章摘要 --}}
<div class="selected_interlocution">
    <div class="litter_title title_line">
        <span class="title_active">
            精选回答
        </span>
        <a href="/category/2" target="_blank">
            更多
        </a>
    </div>
    <div class="article_list">
        @foreach ([1,2,3] as $item)
        @php
            $has_img  = rand (0,3) > 0 ? 'have_img' : '';
        @endphp
        <li class="article_item selected {{ $has_img }}">
            <a class="headline paper_title" href="/detail" target="_blank">
                <span>为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？</span>
            </a>
            <div class="author">
                <a class="avatar" href="/user" target="_blank">
                    <img src="/images/photo_02.jpg"/>
                </a>
                <div class="answer_like">
                    <span>58赞</span>
                </div>
                <div class="info_meta">
                    <a href="/user" target="_blank" class="nickname">
                        空评
                    </a>
                    <a href="/detail" target="_blank">
                        <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs"/>
                    </a>
                    <a href="/user" class="meta">
                        <em>|</em>
                        <span>知识分子</span>
                    </a>
                </div>
            </div>
            @if ($has_img)
            <a class="wrap_img" href="/detail" target="_blank">
                <img src="/images/details_0{{ rand(1,6) }}.jpeg"/>
            </a>
            @endif
            <div class="content">
                <p class="abstract">
                    5月17日下午，腾讯控股公布了2017年第一季度财报。财报显示，腾讯一季度营收495.52亿元，同比增长55%；网络游戏收入增长34%至228.11亿元。其中，就智能手机游戏而言，腾讯实现129亿元收入，同比增长57%，此乃受现有及新的游戏如（《王者荣耀》、《穿越火线：枪战王者》及《龙之谷》）所推动。
                </p>
            </div>
        </li>
        @endforeach
    </div>
</div>