{{-- 关注用户的标签页 --}}
<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li class="active" role="presentation">
            <a aria-controls="users" data-toggle="tab" href="#users" role="tab">
                关注用户 {{ $data['follows']->count() }}
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="fans" data-toggle="tab" href="#fans" role="tab">
                粉丝 {{ $data['followers']->count() }}
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="users" role="tabpanel">
            <ul class="user_list">
              @foreach($data['follows'] as $follow)
               @php
                    $user=$follow->followed;
               @endphp
                <li>
                    <div class="author">
                        <a class="avatar avatar_in" href="/user/{{ $user->id }}">
                            <img src="{{ $user->avatar }}"/>
                        </a>
                       @if(!$user->isSelf())
                          @if(Auth::check())
                        <follow followed="{{ Auth::user()->isFollow('users', $user->id)}}" id="{{ $user->id }}" type="users" user-id="{{ Auth::user()->id }}">
                        </follow>
                        @endif
                       @endif
                        <div class="info_meta">
                            <a class="headline nickname" href="/user/{{ $user->id }}">
                                {{ $user->name }}
                            </a>
                            <div class="meta">
                                <span>
                                    关注 {{ $user->count_followings }}
                                </span>
                                <span>
                                    粉丝 {{ $user->count_follows }}
                                </span>
                                <span>
                                    文章 {{ $user->count_articles }}
                                </span>
                            </div>
                            <p class="meta">
                                写了 {{ $user->count_words }} 字，获得了 {{ $user->count_likes }} 个喜欢
                            </p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-pane fade" id="fans" role="tabpanel">
            <ul class="user_list">
                @foreach($data['followers'] as $follow)
                @php
                    $user=$follow->user;
                @endphp
                <li>
                    <div class="author">
                        <a class="avatar avatar_in" href="/user/{{ $user->id }}">
                            <img src="{{ $user->avatar }}"/>
                        </a>
                       @if(!$user->isSelf())
                          @if(Auth::check())
                        <follow followed="{{ Auth::user()->isFollow('users', $user->id)}}" id="{{ $user->id }}" type="users" user-id="{{ Auth::user()->id }}">
                        </follow>
                        @endif
                       @endif
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                {{ $user->name }}
                            </a>
                            <div class="meta">
                                <span>
                                    关注 {{ $user->count_followings }}
                                </span>
                                <span>
                                    粉丝 {{ $user->count_follows }}
                                </span>
                                <span>
                                    文章 {{ $user->count_articles }}
                                </span>
                            </div>
                            <p class="meta">
                                 写了 {{ $user->count_words }} 字，获得了 {{ $user->count_likes }} 个喜欢
                            </p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>