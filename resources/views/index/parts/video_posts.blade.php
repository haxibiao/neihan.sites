@foreach($videoPosts as $video)
   <div class="col-xs-6 col-md-3 video">
     <div class="video-item vt">
       <div class="thumb">
         <a href="/video/{{$video->id}}" target="_blank">
          @if(!empty($video->coverUrl))
           <img src="{{ $video->coverUrl }}" alt="{{ $video->title }}">
          @endif
           <i class="duration">
             @sectominute($video->duration)
           </i>
           <i class="hover-play"> </i>
         </a>
       </div>
       <ul class="info-list">
         <li class="video-title">
           <a target="_blank" href="/video/{{$video->id}}">{{ $video->title }}</a>
         </li>
         <li>
           <p class="subtitle single-line">{{ random_int(1000,9999) }}次播放</p>
         </li>
       </ul>
     </div>
   </div>
@endforeach
