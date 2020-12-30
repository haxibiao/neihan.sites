 <div class="video-top">
 	  <div class="vd-head">
        <h3 class="vd-title">
          <span class="title-icon">
            <i class="iconfont icon-huo"></i>最新电影
          </span>
        </h3>
        <a href="/movie" class="more-video" title="全部电影">
          <p>
              全部
              <i class="iconfont icon-youbian"></i>
          </p>
      </a>
    </div>
    <ul class="category-video-list">
      @foreach($movies as $movie)
    	<li class="category-video-item">
    		<a href="/movie/{{$movie->id}}">
            	<img class="game-category" src="{{$movie->cover_url}}" alt="{{$movie->name}}">
            	<p>{{$movie->name}}</p>
        	</a>
    	</li>
      @endforeach
	  </ul>
</div>
