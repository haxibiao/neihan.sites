{{-- 猜你喜欢 --}}
<div class="selected_note">
    <div class="litter_title title_line">
        <span class="title_active">
            猜你喜欢
        </span>
    </div>
    <div class="topic">
        @foreach ([1,2,3] as $item)
        <a class="hot_note" href="/interlocution/question" target="_blank">
            <div class="wrap_img">
                <img src="/images/details_0{{ rand(1,6) }}.jpeg"/>
            </div>
            <div class="headline paper_title">
                <span>斗鱼已经变成“死妈TV”了，会不会整个平台被55开害凉了？</span>
            </div>
            <span class="answer_read">243 回答</span>
        </a>
        @endforeach
    </div>
</div>