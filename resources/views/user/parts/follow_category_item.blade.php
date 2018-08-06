<li class="note-info">
    <a class="avatar-category" href="/{{ $category->name_en }}">
        <img alt="" src="{{ $category->logo() }}"/>
    </a>
    <follow 
    followed="{{ is_follow('categories', $category->id) }}" 
    id="{{ $category->id }}" 
    type="categories" 
    user-id="{{ user_id() }}"
    >
    </follow>
    <div class="title">
        <a class="name" href="/{{ $category->name_en }}">
            {{ $category->name }}
        </a>
    </div>
    <div class="info">
        <p>
            <a href="/{{ $category->name_en }}">
                {{ $category->name }}
            </a>
            收录了{{ $category->count }}篇作品，{{ $category->count_follows }}人关注
        </p>
    </div>
</li>