<div class="upload-user">
        @if($video->user)
        <a href="/user/{{ $video->user_id }}" class="sub">
                <img src="{{ $video->user->avatarUrl }}" class="avatar" alt="{{ $video->user->name }}">
        </a>
        <div>
                <p class="name">{{ $video->user->name }}</p>  
                <p class="time">上传于 {{$video->createdAt()}}</p>   
        </div>
        @endif
</div>    