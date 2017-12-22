{{-- 个人页右侧 --}}
<div class="aside col-sm-4">
    <div class="title">
        个人介绍
    </div>
    <a class="function_btn" href="javascript:;">
        <i class="iconfont icon-xie">
        </i>
        编辑
    </a>
    <form action="/user/" class="intro_form" method="post">
        <textarea class="form-control" id="user_intro" name="user[intro]">{{ $user->introduction  }}</textarea>
        <input class="btn_hollow" name="commit" type="submit" value="保存"/>
        <a href="javascript:void(null);">
            取消
        </a>
    </form>
    <div class="description">
        <div class="intro">{{ $user->introduction }}</div>
    </div>
    <ul class="user_dynamic">
        <li>
            <a href="/follow#/timeline">
                <i class="iconfont icon-duoxuan">
                </i>
                <span>
                    我关注的专题
                </span>
            </a>
        </li>
        <li>
            <a href="/v1/home/liked_note">
                <i class="iconfont icon-xin">
                </i>
                <span>
                    我喜欢的文章
                </span>
            </a>
        </li>
    </ul>
    <div>
        <p class="title">
            我创建的专题
        </p>
        <ul class="list">
          @foreach($user->categories as $category)
            <li>
                <a href="/{{ $category->name_en }}" target="_blank">
                    <img src="{{ $category->logo }}"/>
                    <span>
                        {{ $category->name }}
                    </span>
                </a>
            </li>
          @endforeach
        </ul>
        <p class="title">
            我的文集
        </p>
        <ul class="list">
          @foreach($user->collections as $collection)
            <li>
                <a href="/collection/{{ $collection->id }}" target="_blank">
                    <i class="iconfont icon-wenji">
                    </i>
                    <span>
                        {{ $collection->name }}
                    </span>
                </a>
            </li>
          @endforeach
        </ul>
    </div>
</div>
@push('scripts')
<script>
    $('.function_btn').on('click',function(){
        $('.intro_form').show();
        $('.description .intro').hide();
    })
    $('form a').on('click',function(){
        $('.intro_form').hide();
        $('.description .intro').show();
    })
</script>
@endpush
