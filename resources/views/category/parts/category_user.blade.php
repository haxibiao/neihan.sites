{{-- name_en页面左侧头部 --}}
<div class="main_top">
    <a class="avatar avatar_lg avatar_collection" href="/{{ $category->name_en }}">
        <img src="{{ $category->logo }}"/>
    </a>
    <follow followed="{{ Auth::check() ? Auth::user()->isFollow('categories', $category->id) : false }}" id="{{ $category->id }}" type="categories" user-id="{{ Auth::check() ? Auth::user()->id : false }}">
    </follow>
    <a class="btn_base btn_hollow btn_hollow_sm" data-target="#categoryModal_user" data-toggle="modal" href="#">
        <span>
            投稿
        </span>
    </a>
    <div class="info_meta">
        <a class="headline name_title" href="/{{ $category->name_en }}">
            <span>
                {{ $category->name }}
            </span>
        </a>
    </div>
    <p class="info_count">
        收录了{{ $category->articles->count() }}篇文章 · {{ $category->count_follows }}人关注
    </p>
</div>
@push('modals')
   <modal-contribute category-id='{{ $category->id }}'></modal-contribute> 
@endpush
