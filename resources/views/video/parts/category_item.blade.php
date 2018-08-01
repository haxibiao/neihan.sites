<div>
    @if($category)
          <a target="{{ \Agent::isDeskTop()? '_blank':'_self' }}" href="/{{ $category->name_en }}" class="category-label">
      <img src="{{ $category->logo() }}" alt="{{ $category->name }}">
      <span class="name">{{ $category->name }}</span>
    </a>
    @endif
</div> 
