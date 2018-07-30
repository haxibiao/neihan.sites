
<div class="desc">

    <div class="pull-right">
        @if(canEdit($article))
        <a class="btn-base btn-light btn-sm" href="/video/{{ $video->id }}/edit">编辑视频动态</a>
        @endif
    </div>
    <p class="upload-time hidden-xs">上传于 {{$video->createdAt()}}</p> 
    <p class="upload-user">
        @if($video->user)
        <a href="/user/{{ $video->user_id }}" class="sub">
            <img src="{{ $video->user->avatar() }}" class="avatar" alt="{{ $video->user->name }}">
            <p>{{ $video->user->name }}</p>
        </a>
        @endif
        @if( $video->user && !$video->user->isSelf() )
            <span class="button-vd">
                <follow 
                    type="users" 
                    id="{{ $video->user->id }}" 
                    user-id="{{ user_id() }}" 
                    followed="{{ is_follow('users', $video->user->id) }}"
                    size-class="btn-md"
                    >
                </follow>
            </span>
        @endif
    </p>
</div>