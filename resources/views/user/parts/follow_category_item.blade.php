<li class="note-info">
    <a class="avatar-category" href="/category/{{ $category->id }}">
        <img alt="" src="{{ $category->logoUrl }}"/>
    </a>
    <follow 
    followed="{{ is_follow('categories', $category->id) }}" 
    id="{{ $category->id }}" 
    type="categories" 
    user-id="{{ user_id() }}"
    >
    </follow>
    <div class="title">
        <a class="name" href="/category/{{ $category->id }}">
            {{ $category->name }}
        </a>
    </div>
    <div class="info">
        <p>
            <a href="/category/{{ $category->id }}">
                {{ $category->name }}
            </a>
            收录了{{ $category->count }}篇作品，{{ $category->count_follows }}人关注
        </p>
    </div>
</li>