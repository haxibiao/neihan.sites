<div class="video-box">
    @foreach($movies as $movie)
        @if($loop->last)
            <div class="box-top">
                <div class="top-left">
                    <img  class="cateory-logo" src="/images/category.logo.small.jpg">
                    <a href="/category/1">
                        <p class="title">{{ $category }}</p>
                    </a>
                </div>
                <div class="top-right">
                    <a href="/category/1" class="more-cateory"><p>更多<i class="iconfont icon-youbian"></i></p></a>
                </div>
            </div>
        @endif
    @endforeach
    <div class="box-body">
        <ul class="game-video-list">
            @foreach($movies as $movie)
                <li class="game-video-item">
                    <a href="/movie/{{ $movie->id }}" target="{{ isDeskTop()? '_blank':'_self' }}" class="video-info">   
                        <img class="video-photo"  id="video-img" src=" {{ $movie->cover_url }}">
                        <i class="hover-play"> </i>
                    </a>
                    <a href="/movie/{{ $movie->id }}" target="{{ isDeskTop()? '_blank':'_self' }}"  class="video-title">{{ $movie->name }}</a>
                    <div class="info">
                        <a class="user" href="/user/1">
                            <img src="/images/avatar.jpg" class="avatar">
                            <span> {{ $movie->producer }}</span>                          
                        </a>
                        <div class="num">
                            <i class="iconfont icon-liulan"> {{ rand(100,1000) }}</i>
                            <i class="iconfont icon-svg37"> {{ rand(1,10) }}</i>
                        </div> 
                    </div>          
                </li>
            @endforeach
        </ul>
    </div>
</div>  
