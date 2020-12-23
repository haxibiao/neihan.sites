<div class="video-box">
    @foreach($articles as $article)
        @if($loop->last)
            <div class="box-top">
                <div class="top-left">
                    <img  class="cateory-logo" src="{{ $article->category->logoUrl }}">
                    <a href="/category/{{ $article->category->id }}">
                        <p class="title">{{ $article->category->name }}</p>
                    </a>
                </div>
                <div class="top-right">
                    <a href="/category/{{ $article->category->id }}" class="more-cateory"><p>更多<i class="iconfont icon-youbian"></i></p></a>
                </div>
            </div>
        @endif
    @endforeach
    <div class="box-body">
        <ul class="game-video-list">
            @foreach($articles as $article)
                <li class="game-video-item">
                    <a href="/article/{{ $article->id }}"   class="video-info" >
                        <img class="video-photo"  id="video-img" src=" {{ $article->cover }}">
                        <!-- <i class="hover-play"> </i> -->
                    </a>
                    <a href="/article/{{ $article->id }}"    class="video-title" >{{ $article->subject ?: $article->summary }}</a>
                    <div class="info">
                        <a class="user" href="/user/{{ $article->user_id }}">
                            <img src="{{ $article->user->avatarUrl }}" class="avatar">
                            <span> {{ $article->user->name }}</span>
                        </a>
                        <div class="num">
                            <i class="iconfont icon-liulan"> {{ $article->hits }}</i>
                            <i class="iconfont icon-svg37"> {{ $article->count_replies }}</i>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
