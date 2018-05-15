<div class="note-info info-lg">
    <a class="avatar-category" href="javascript:;">
      <img src="/images/collection.png" alt="">
		</a>        
			{{-- 用户自己的文集没有关注按钮,别人的有 --}}
    @if(!$collection->isSelf())
      <div class="btn-wrap">
        <follow 
            type="collections" 
            id="{{ $collection->id }}" 
            user-id="{{ user_id() }}" 
            followed="{{ is_follow('collections', $collection->id) }}"
            >
        </follow>
      </div>
    @endif
    <div class="title">
      <a class="name" href="javascript:;">{{ $collection->name }}</a>
    </div>
    <div class="info">
  			{{ $collection->count }}篇文章 · {{ $collection->count_words }}字 · {{ $collection->count_follows }}人关注
    </div>
</div>