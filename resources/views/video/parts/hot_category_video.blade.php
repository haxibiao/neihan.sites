<div class="video-box">
    @foreach($articles as $article)
        @if($loop->last)
            <div class="box-top">
                <div class="top-left">
                    <img  class="cateory-logo" src="{{ $article->category->logo() }}">
                    <a href="/{{ $article->category->name_en }}">
                        <p class="title">{{ $article->category->name }}</p>
                    </a>
                </div>
                <div class="top-right">
                    <a href="/{{ $article->category->name_en }}" class="more-cateory"><p>更多<i class="iconfont icon-youbian"></i></p></a>
                </div>
            </div>
        @endif
    @endforeach
    <div class="box-body">
        <ul class="game-video-list">
            @foreach($articles as $article)
                <li class="game-video-item">
                    <a href="/video/{{ $article->video_id }}" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" class="video-info">   
                        <img class="video-photo"  id="video-img" src=" {{ $article->image_url }}">
                        <i class="hover-play"> </i>
                    </a>
                    <a href="/video/{{ $article->video_id }}" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}"  class="video-title">{{$article->title}}</a>
                    <div class="info">
                        <a class="user" href="/user/{{ $article->user_id }}">
                            <img src="{{ $article->user->avatar() }}" class="avatar">
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
