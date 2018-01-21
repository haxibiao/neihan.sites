{{-- 猜你喜欢 --}}
<div class="selected_note">
    <div class="litter_title title_line">
        <span class="title_active">
            猜你喜欢
        </span>
    </div>
    <ul class="guess_list">
        @foreach ($data['hot'] as $question)
        <li class="article_item selected {{ $question->has_img() }}">
            <a class="wrap_img" href="/interlocution/question">
                <img src="{{ $question->relateImage() }}"/>
            </a>
            <div class="content">
                <a class="headline paper_title" href="/question/{{ $question->id }}">
                    <span>
                        {{ $question->title }}
                    </span>
                </a>
                <span class="meta">
                    {{ $question->count_answer }} 回答
                </span>
            </div>
        </li>
        @endforeach
    </ul>
</div>