<div class="video-box">
    @foreach($posts as $post)
        @if($loop->last && $post->collections)
            <div class="box-top">
                <div class="top-left">
                    <img  class="cateory-logo" src="$post->collections[0]->logo??'/images/appicons/cover.jpg' }}">
                    <a href="/category/{{ $post->collections[0]->id }}">
                        <p class="title">{{ $post->collections[0]->name }}</p>
                    </a>
                </div>
                <div class="top-right">
                    <a href="/category/{{ $post->collections[0]->id }}" class="more-cateory"><p>更多<i class="iconfont icon-youbian"></i></p></a>
                </div>
            </div>
        @endif
    @endforeach
    <div class="box-body">
        <ul class="game-video-list">
            @foreach($posts as $post)
                <li class="game-video-item">
                    <a href="/video/{{ $post->video->id??'' }}" target="{{ isDeskTop()? '_blank':'_self' }}" class="video-info">
                        <img class="video-photo"  id="video-img" src="https://cos.diudie.com/images/1575/16754/5d5b6808100a4afbb1d17d4ee753d6de.jpeg">
                        <i class="hover-play"> </i>
                    </a>
                    <a href="/video/{{ $post->video->id??'' }}" target="{{ isDeskTop()? '_blank':'_self' }}"  class="video-title">{{ $post->content }}</a>
                    <div class="info">
                        <a class="user" href="/user/{{ $post->user_id }}">
                            <img src="{{ $post->user->avatarUrl }}" class="avatar">
                            <span> {{ $post->user->name }}</span>
                        </a>
                        <div class="num">
                            <i class="iconfont icon-liulan"> {{ $post->hits }}</i>
                            <i class="iconfont icon-svg37"> {{ $post->count_replies }}</i>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
