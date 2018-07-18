
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
                    <a href="/video/{{ $article->video_id }}" class="video-info">   
                        <img class="video-photo" src=" {{ $article->image_url }}">
                        <i class="hover-play"> </i>
                    </a>
                    <div class="info">
                        <a class="user" href="/user/{{ $article->user_id }}">
                            <img src="{{ $article->user->avatar() }}" class="avatar">
                            <span> {{ $article->user->name }}</span>                          
                        </a>
                        <div class="num">
                            <i class="iconfont icon-liulan"> {{ $article->hits }}</i>
                            <i class="iconfont icon-svg37"> {{ empty($article->count_likes) ? 0 : $article->count_likes }}</i>
                        </div>
                    </div>          
                </li>
            @endforeach
            {{-- <li class="game-video-item">
                <a href="/video/333" class="video-info">   
                    <img class="video-photo" src="https://www.ainicheng.com/storage/video/333.jpg">
                     <i class="hover-play"> </i>
                </a>
                <a href="">在绝地求生和F4一起来看“流星雨”</a>
                <div class="info">
                    <a class="user" href="/user/270">
                        <img src="https://ainicheng.com/storage/avatar/270.jpg" class="avatar">
                        <span>风清歌</span>                          
                    </a>
                    <div class="num">
                        <i class="iconfont icon-liulan"> 11</i>
                        <i class="iconfont icon-svg37"> 20</i>
                    </div>
                </div>
            </li>
            <li class="game-video-item">
                <a href="/video/291" class="video-info">   
                    <img  class="video-photo" src="https://www.ainicheng.com/storage/video/291.jpg">
                    <i class="hover-play"> </i>
                </a>
                 <a href="">绝地求生大逃杀新手教学</a>
                <div class="info">
                    <a class="user" href="/user/270">
                        <img src="https://ainicheng.com/storage/avatar/270.jpg" class="avatar">
                        <span>风清歌</span>                          
                    </a>
                    <div class="num">
                        <i class="iconfont icon-liulan"> 11</i>
                        <i class="iconfont icon-svg37"> 20</i>
                    </div>
                </div>
            </li> --}}
        </ul>
    </div>
</div>  
