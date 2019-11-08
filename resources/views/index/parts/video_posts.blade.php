@foreach($videoPosts as $article)  
   @php  
      $video = $article->video;
   @endphp
   <div class="col-xs-6 col-md-3 video">
     <div class="video-item vt">
       <div class="thumb">
         <a href="/video/{{$video->id}}" target="_blank">
          @if(!empty($article->cover))
           <img src="{{ $article->cover }}" alt="{{ $article->subject ?: $article->summary }}">  
          @endif
           <i class="duration"> 
             @sectominute($video->duration)
           </i>
           <i class="hover-play"> </i>
         </a>
       </div>
       <ul class="info-list">
         <li class="video-title">
           <a target="_blank" href="/video/{{$video->id}}">{{ $article->subject ?: $article->summary }}</a>
         </li>
         <li>
           <p class="subtitle single-line">{{ $article->hits }}次播放</p>
         </li>
       </ul>
     </div>
   </div>
@endforeach