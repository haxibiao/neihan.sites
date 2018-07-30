<div class="classify" style="max-width: 150px; text-align: center;">
    @if($category)
        <div>
            <a href="/{{ $category->name_en }}" class="avatar">
                <img src="{{ $category->logo() }}" alt="{{ $category->name }}">
            </a>
            <div class="classify-info">
                <div>
                    {{-- 分类 --}}
                    <a href="/{{ $category->name_en }}">{{$category->name}}</a>
                </div> 
                {{-- 关注数 --}}
                <span>{{$category->count_follows}}人关注</span> 
                <span>- {{$category->count_videos}}个视频</span> 
            </div>
        </div>
            
        <div class="button-vd clearfix">
            <follow  
                type="categories" 
                id="{{ $category->id }}"   
                user-id="{{ user_id() }}" 
                followed="{{ is_follow('categories', $category->id) }}"
                size-class="btn-md"
                >
            </follow>
        </div>
    @endif
</div> 