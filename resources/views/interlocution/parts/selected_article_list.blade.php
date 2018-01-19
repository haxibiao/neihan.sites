{{-- 精选文章摘要 --}}
<div class="selected_interlocution">
    <div class="litter_title title_line">
        <span class="title_active">
            精选回答
        </span>
{{--         <a href="/category/2" target="_blank">
            更多
        </a> --}}
    </div>
    <div class="article_list">
        @foreach ($data['hot'] as $item)
        <li class="article_item selected have_img">
            <a class="headline paper_title" href="/question/{{ $item->id }}" target="_blank">
                <span>{{ $item->title }}</span>
            </a>
            <div class="author">
                <a class="avatar" href="/user" target="_blank">
                    <img src="{{ $item->user->avatar }}"/>
                </a>
                <div class="answer_like">
                    <span>{{ $item->count_favorites }}赞</span>
                </div>
                <div class="info_meta">
                    <a href="/user" target="_blank" class="nickname">
                        {{ $item->user->name }}
                    </a>
                    <a href="/detail" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                    <a href="/user" class="meta">
                        <em>|</em>
                        <span>知识分子</span>
                    </a>
                </div>
            </div>
            <div class="question_warp">

                <a class="wrap_img" href="/detail" target="_blank">
                    <img src="{{ $item->relateImage() }}"/>
                </a>

                <div class="content">
                    <p class="abstract">
                      {{ $item->background }}
                    </p>
                </div>
            </div>
        </li>
        @endforeach
    </div>
</div>