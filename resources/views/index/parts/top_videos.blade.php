@foreach($videos as $video)
  @if($video->article)
   <div class="col-xs-6 col-md-3 video">
     <div class="video-item vt">
       <div class="thumb">
         <a href="/video/{{$video->id}}" target="{{ \Agent::isDeskTop()? '_blank':'_self' }}">
           <img src="{{ $video->article->cover() }}" alt="{{ $video->article->title }}">
           <i class="duration"> 
             @sectominute($video->duration)
           </i>
           <i class="hover-play"> </i>
         </a>
       </div>
       <ul class="info-list">
         <li class="video-title">
           <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/video/{{$video->id}}">{{ $video->article->title }}</a>
         </li>
         <li>
           <p class="subtitle single-line">{{ $video->article->hits }}次播放</p>
         </li>
       </ul>
     </div>
   </div>
   @endif
@endforeach