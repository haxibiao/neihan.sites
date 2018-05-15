<li class="col-sm-4 recommend-card">
  <div>
    <a target="_blank" href="/{{ $category->name_en }}">
      <img class="avatar-category" src="{{ $category->logo() }}" alt="">
      <h4 class="name single-line">{{ $category->name }}</h4>
      <p class="category-description">{{ $category->description }}</p>
    </a>    

      <follow 
        type="categories" 
        id="{{ $category->id }}" 
        user-id="{{ user_id() }}" 
        followed="{{ is_follow('categories', $category->id) }}">
      </follow>
      
    <hr>
    <div class="count"><a target="_blank" href="/{{ $category->name_en }}">{{ $category->count }}篇文章</a> · {{ $category->count_follows }}人关注</div>
  </div>
</li>