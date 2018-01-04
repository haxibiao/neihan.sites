   {{-- 专题右侧 --}}
<div class="aside col-sm-4">
    <p class="litter_title">
        专题公告
    </p>
    <div class="description">
        {{ $category->description }}
    </div>
        <div class="share">
            <span>
                分享到
            </span>
            <a href="#">
                <i class="iconfont icon-weixin1">
                </i>
            </a>
            <a href="#">
                <i class="iconfont icon-sina">
                </i>
            </a>
            <a href="#">
                <i class="iconfont icon-gengduo">
                </i>
            </a>
        </div>
        <div class="intendant">
            <div class="supporter">
                <p class="litter_title">
                    管理员
                </p>
                <ul class="collection_editor">
                    @foreach($category->topAdmins() as $admin)
                    <li>
                        <a class="avatar" href="/user/{{ $admin->id }}">
                            <img src="{{ $admin->avatar }}"/>
                        </a>
                        <a class="name" href="/v1/user">
                            {{ $admin->name }}
                        </a>
                        @if($admin->isCreator)
                        <span class="tag">
                            创建者
                        </span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="supporter">
                <div class="title">
                    推荐作者({{ $category->topAuthors()->count() }})
                    <i class="iconfont icon-tuijian1">
                    </i>
                </div>
                <ul class="collection_follower">
                    @foreach($category->topAuthors() as $user)
                    <li>
                        <a class="avatar" href="/user/{{ $user->id }}">
                            <img src="{{ $user->avatar }}"/>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="supporter">
                <div class="title">
                    关注的人
                </div>
                <ul class="collection_follower">
                    @foreach($category->topFollowers() as $user)
                    <li>
                        <a class="avatar" href="/user/{{ $user->id }}">
                            <img src="{{ $user->avatar }}"/>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
</div>