@php
  $have_img = !empty($question->relateImage()) ? 'have-img' : '';
@endphp
<li class="question-item simple {{ $have_img }}">
    <div class="question-warp">
    	<a href="/question/{{ $question->id }}" target="_blank" class="wrap-img"><img src="{{ $question->relateImage() }}" alt=""></a>
        <div class="content">
           <a target="_blank" href="/question/{{ $question->id }}" class="title"><span>{{ $question->title }}</span></a>
           <div class="meta">
             <span>{{ $question->count_answers }}回答</span>
           </div>
        </div>
    </div>
</li>
