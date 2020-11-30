@foreach($videoPosts as $post)
   <div class="col-xs-6 col-md-3 video">
     <div class="video-item vt">
       <div class="thumb">
         <a href="/video/{{$post->video->id}}" target="_blank">
          @if(!empty($post->cover))
           <img src="{{ $post->cover}}" alt="{{ $post->content }}">
          @endif
           <i class="duration">
             @sectominute($post->video->duration)
           </i>
           <i class="hover-play"> </i>
         </a>
       </div>
       <ul class="info-list">
         <li class="video-title">
           <a target="_blank" href="/video/{{$post->video->id}}">{{ $post->content}}</a>
         </li>
         <li>
           <p class="subtitle single-line">{{ random_int(1000,9999) }}次播放</p>
         </li>
       </ul>
     </div>
   </div>
@endforeach
