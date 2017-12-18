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
        <textarea class="form-control" id="user_intro" name="user[intro]">十分精力：四分读书，三分写书，三分教书。《正版语文》作者，21世纪最牛写作书 《风格感觉》（The Sense of Style）中文译者之一，“好中文的样子”创办人和主讲人。</textarea>
        <input class="btn_hollow" name="commit" type="submit" value="保存"/>
        <a href="javascript:void(null);">
            取消
        </a>
    </form>
    <div class="description">
        <div class="intro">十分精力：四分读书，三分写书，三分教书。《正版语文》作者，21世纪最牛写作书 《风格感觉》（The Sense of Style）中文译者之一，“好中文的样子”创办人和主讲人。</div>
    </div>
    <ul class="user_dynamic">
        <li>
            <a href="/v1/home/subscription">
                <i class="iconfont icon-duoxuan">
                </i>
                <span>
                    我关注的专题/文集
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
            <li>
                <a href="/v1/category/1" target="_blank">
                    <img src="/images/category_05.jpg"/>
                    <span>
                        文章
                    </span>
                </a>
            </li>
            <li>
                <a href="/v1/category/1" target="_blank">
                    <img src="/images/category_06.jpg"/>
                    <span>
                        好中文的样子
                    </span>
                </a>
            </li>
            <li>
                <a class="new_collection" href="/v1/collections/new" target="_blank">
                    <i class="iconfont icon-jia">
                    </i>
                    <span>
                        创建一个新专题
                    </span>
                </a>
            </li>
        </ul>
        <p class="title">
            我的文集
        </p>
        <ul class="list">
            <li>
                <a href="/v1/collection" target="_blank">
                    <i class="iconfont icon-wenji">
                    </i>
                    <span>
                        日记本
                    </span>
                </a>
            </li>
            <li>
                <a href="/v1/collection" target="_blank">
                    <i class="iconfont icon-wenji">
                    </i>
                    <span>
                        好中文的样子
                    </span>
                </a>
            </li>
            <li>
                <a href="/v1/collection" target="_blank">
                    <i class="iconfont icon-wenji">
                    </i>
                    <span>
                        个人试笔
                    </span>
                </a>
            </li>
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
