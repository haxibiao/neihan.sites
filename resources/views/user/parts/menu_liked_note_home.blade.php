{{-- 个人页喜欢文章的标签页 --}}
<div>
    <!-- Nav tabs -->
    <ul class="trigger_menu" role="tablist">
        <li role="presentation">
            <a aria-controls="follow" data-toggle="tab" href="#follow" role="tab">
                关注的专题 {{ $data['followed_categories']->count() }}
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="follow" data-toggle="tab" href="#collection" role="tab">
                关注的文集 {{ $data['followed_collections']->count() }}
            </a>
        </li>
        <li class="active" role="presentation">
            <a aria-controls="likes" data-toggle="tab" href="#likes" role="tab">
                喜欢的文章 {{ $data['like_articles']->count() }}
            </a>
        </li>
        <li role="presentation">
            <a aria-controls="dongtai" data-toggle="tab" href="#dongtai" role="tab">
                <i class="iconfont icon-zhongyaogaojing">
                </i>
                动态
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade" id="follow" role="tabpanel">
            <ul class="user_list">
               @foreach($data['followed_categories'] as $follow )
                @php
                    $category=$follow->followed;
                @endphp
                <li>
                    <div class="author">
                        <a class="avatar avatar_in avatar_collection" href="/{{ $category->name_en }}">
                            <img src="{{ $category->logo }}"/>
                        </a>
{{--                         <a class="btn_base btn_followed" href="javascript:;">
                            <span>
                                <i class="iconfont icon-weibiaoti12">
                                </i>
                                <i class="iconfont icon-cha">
                                </i>
                            </span>
                        </a> --}}
                          <follow followed="{{ Auth::check() ? Auth::user()->isFollow('categories', $category->id) : false }}" id="{{ $category->id }}" type="categories" user-id="{{ Auth::check() ? Auth::user()->id : false }}">
                         </follow>
                        <div class="info_meta">
                            <a class="headline nickname" href="/{{ $category->name_en }}">
                               {{ $category->name }}
                            </a>
                            <div class="meta">
                                <a href="/{{ $category->name_en }}">
                                    {{ $category->name }}
                                </a>
                                收录了{{ $category->count }}篇文章，{{ $category->count_follows }}人关注
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-pane fade" id="collection" role="tabpanel">
            <ul class="user_list">

                @foreach($data['followed_collections'] as $follow)
                @php
                    $collection=$follow->followeb;
                @endphp
                <li>
                    <div>
                        <a class="avatar avatar_collection" href="#">
                            <img src="{{ $collection->has_logo() }}"/>
                        </a>
                        <div class="info">
                            <a class="name" href="#">
                                王者荣耀
                            </a>
                            <div class="meta">
                                收录了19篇文章，4人关注
                            </div>
                        </div>
                        <a class="following" href="javascript:;">
                            <span>
                                <i class="iconfont icon-weibiaoti12">
                                </i>
                                <i class="iconfont icon-cha">
                                </i>
                            </span>
                        </a>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-pane fade in active" id="likes" role="tabpanel">
           @foreach($data['like_articles'] as $like)
               @php
                   $article=$like->liked;
               @endphp
              @include('user.parts.article_list_like')
           @endforeach
        </div>

        <div class="tab-pane fade" id="dongtai" role="tabpanel">
            <ul class="article_list">

               @include('user.parts.user_acive_article',['articles'=>$data['actions_article']])
               @foreach($data['actions'] as $action)
                @include('user.parts.user_acive',['action'=>$action])
               @endforeach
                   <li class="article_item">
                        <div class="author">
                            <a class="avatar" href="/user/{{ $user->id }}" target="_blank">
                                <img src="{{ $user->avatar }}"/>
                            </a>
                            <div class="info_meta">
                                <a class="nickname" href="/user/{{ $user->id }}" target="_blank">
                                    {{ $user->name }}
                                </a>
                                <span class="time">
                                    加入了爱你城 · {{ $user->created_at }}
                                </span>
                            </div>
                        </div>
                    </li>
            </ul>
        </div>
    </div>
</div>