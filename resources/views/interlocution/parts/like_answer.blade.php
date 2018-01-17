{{-- 猜你喜欢 --}}
<div class="selected_note">
    <div class="litter_title title_line">
        <span class="title_active">
            猜你喜欢
        </span>
    </div>
    <ul class="guess_list">
        @foreach ([1,2,3] as $item)
        @php
            $has_img  = rand (0,3) > 0 ? 'have_img' : '';
        @endphp
        <li class="article_item selected {{ $has_img }}">
            @if ($has_img)
            <a class="wrap_img" href="/interlocution/question">
                <img src="/images/details_0{{ rand(1,6) }}.jpeg"/>
            </a>
            @endif
            <div class="content">
                <a class="headline paper_title" href="/interlocution/question">
                    <span>
                        斗鱼已经变成“死妈TV”了，会不会整个平台被55开害凉了？
                    </span>
                </a>
                <span class="meta">
                    243 回答
                </span>
            </div>
        </li>
        @endforeach
    </ul>
</div>