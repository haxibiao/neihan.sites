<div class="recommend-question hidden-xs">
 
  @foreach($data->questions as $question)
  @if(!empty($question->relateImage()))
  <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/question/{{ $question->id }}" class="question-label">
      <img src="{{ $question->relateImage() }}" alt="{{ $question->title }}">
      <span class="name">{{ str_limit($question->title,10)}}</span>
    </a>
  @else
      <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/question/{{ $question->id }}" class="question-label">
      <i class="iconfont icon-changjianwenti board-right"></i>
      <span class="name">{{ $question->title }}</span>
    </a>
  @endif
   
  @endforeach

  <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/question" class="question-label more">
    <span class="name">更多问答 <i class="iconfont icon-youbian"></i></span>
  </a>
</div> 