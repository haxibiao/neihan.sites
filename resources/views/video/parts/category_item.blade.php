<div>
    @if($category)
          <a   href="/category/{{ $category->id }}" class="category-label">
      <img src="{{ $category->logoUrl }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
    @endif
</div> 
