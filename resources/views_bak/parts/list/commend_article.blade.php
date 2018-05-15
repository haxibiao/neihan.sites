<ul class="article_list">
    @foreach($data['related'] as $article)
     <li class="article_item {{ $article->hasImage()?'have_img':'' }}">
        @if($article->hasImage())
            <a class="wrap_img" href="/user/{{ $article->user->id }}" target="_blank">
                <img src="{{ $article->image_url }}"/>
            </a>
        @endif
        <div class="content">
            <a class="headline paper_title" href="/article/{{ $article->id }}" target="_blank">
                <span>
                    {{ $article->title }}
                </span>
            </a>
            <p class="abstract">
                {{ $article->description() }}
            </p>
            <div class="author">
                <a class="avatar" href="/article/{{ $article->id }}" target="_blank">
                    <img src="{{ $article->user->avatar }}"/>
                </a>
              
                <div class="info_meta">
                    <a class="nickname" href="/user/{{ $article->user->id }}" target="_blank">
                       {{ $article->user->name }}
                    </a>
                </div>
            </div>
        </div>
    </li>
    @endforeach
</ul>
