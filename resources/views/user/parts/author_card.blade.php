<li class="col-sm-4 recommend-card">
  <div>
    <a target="_blank" href="/user/{{ $user->id }}">
	    <img class="avatar" src="{{ $user->avatar() }}" alt="">
      <h4 class="name single-line">{{ $user->name }}</h4>
      <p class="author-description">{{ $user->introduction }}</p>
		</a>    
		
    @if(!$user->isSelf())
      <follow 
          type="users" 
          id="{{ $user->id }}" 
          user-id="{{ user_id() }}" 
          followed="{{ is_follow('users', $user->id) }}">          
      </follow>
    @endif

    <hr>
    <div class="meta">最近更新</div>
    <div class="recent-update">
       @foreach($user->articles()->orderBy('id','desc')->take(3)->get() as $article)
        <a class="new single-line" target="_blank" href="/article/{{ $article->id }}">{{ $article->title }}</a>
        @endforeach
    </div>
  </div>
</li>