@foreach($categories as $category)
        <div class="recommend_list col-xs-12 col-sm-4 col-lg-3">
            <div class="collection_wrap">
                <a href="/{{ $category->name_en }}" target="_blank">
                    <img class="avatar_lg" src="{{ $category->logo }}"/>
                    <h4 class="headline">
                        {{ $category->name }}
                    </h4>
                    <p class="abstract">
                        欢迎来到爱你城的推荐专题
                    
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
                    <a href="/category/{{ $category->name_en }}" target="_blank">
                       {{ $category->count }}篇文章
                    </a>
                   {{ $category->count_follows }}人关注
                </div>
            </div>
        </div>
@endforeach