<div class="plate-title">
    专题公告
    @if($category->canAdmin())
        <a class="right write" href="javascript:void(0)"><i class="iconfont icon-bi1"></i>编辑</a>
    @endif
</div>
<div class="description distance">
    <p class="cont" id="description_text">
        {{ $category->description }}
    </p> 

    @if($category->canAdmin())
    <form class="profile-edit intro-form" action="#" method="post">
        <textarea name="description" id="catgory_description">{{ $category->description }}</textarea>
        <input type="button" name="commit" value="保存" class="btn-base btn-hollow btn-md">
        <a href="javascript:void(null);">取消</a>
    </form>
    @push('scripts')
        <script>
            $('.plate-title .write').on('click',function(){
                $('.intro-form').show();
                $('.description .cont').hide();
            })
            $('form a').on('click',function(){
                $('.intro-form').hide();
                $('.description .cont').show();
            })

            //jquery ajax post to update 
            $('.profile-edit input[type=button]').click(function(){
                $.post(window.tokenize('/api/category/{{ $category->id }}'),{
                    description: $('#catgory_description').val()
                }); 
                
                //乐观更新ui..
                $('#description_text').html($('#catgory_description').val());
                $('.intro-form').hide();
                $('.description .cont').show();
            });
            
        </script>
    @endpush
    @endif
</div>