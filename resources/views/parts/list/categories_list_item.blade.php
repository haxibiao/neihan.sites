@foreach($categories as $category)
        <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
            <div class="collection_wrap">
                <a href="/{{ $category->name_en }}" target="_blank">
                    <img class="avatar_collection" src="{{ $category->logo }}"/>
                    <h4 class="name">
                        {{ $category->name }}
                    </h4>
                    <p class="collection_description">
                        欢迎来到爱你城的推荐专题
                        专题公告与特色:
                        {{ $category->description }}
                    </p>
                </a>
                   <follow
                    type="categories"
                    id="{{ $category->id }}"
                    user-id="{{ Auth::check() ? Auth::user()->id : false }}"
                    followed="{{ Auth::check() ? Auth::user()->isFollow('categories', $category->id) : false }}">
                  </follow>
                <hr/>
                <div class="count">
                    <a href="/v1/category/2" target="_blank">
                       {{ $category->count }}篇文章
                    </a>
                   {{ $category->count_follows }}人关注
                </div>
            </div>
        </div>
@endforeach