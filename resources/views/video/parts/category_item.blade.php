<div>
    @if($category)
          <a target="{{ isDeskTop()? '_blank':'_self' }}" href="/category/{{ $category->id }}" class="category-label">
      <img src="{{ $category->logoUrl }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
    @endif
</div> 
