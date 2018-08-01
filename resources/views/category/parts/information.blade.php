<div class="note-info info-lg">
    <a class="avatar-category" href="{{ $category->checkAdmin() ? "/category/$category->id/edit": "javascript:;" }}" title="{{ $category->id }}:{{ $category->name }}">
        <img src="{{ $category->logo() }}" alt="{{ $category->id }}:{{ $category->name }}">
    </a>
    <div class="btn-wrap">
        <follow 
            type="categories" 
            id="{{ $category->id }}" 
            user-id="{{ user_id() }}" 
            followed="{{ is_follow('categories', $category->id) }}"
            >
        </follow>
        @if(Auth::check())
            <a class="btn-base btn-hollow" 
                data-target=".modal-contribute" 
                data-toggle="modal" href="javascript:;">{{ $category->isAdmin(Auth::user()) ? '收录' : '投稿' }}</a>
        @endif
    </div>
    <div class="title">
        <a class="name" href="javascript:;">{{ $category->name }}</a>
    </div>
    <div class="info">
        收录了{{ $category->count }}篇文章 · {{ $category->count_follows }}人关注
    </div>

</div>

@push('modals')
    <!-- Modal -->
    <modal-contribute category-id='{{ $category->id }}'></modal-contribute>
@endpush