<div class="administrator distance">
	<p class="plate-title">{{ $user->ta() }}管理的专题</p>

	<ul class="admin-category">
		@foreach($user->adminCategories()->orderBy('id','desc')->get() as $category)

		<li class="single-media"><a href="/{{ $category->name_en }}" class="avatar-category"><img src="{{ $category->logo() }}" alt="{{ $category->name }}"></a><a href="/{{ $category->name_en }}" class="info">{{ $category->name }}</a></li>	
		@endforeach	
	</ul>
	<div class="unfold"><a class="check-more">展开更多</a><i class="iconfont icon-xia"></i><i class="iconfont icon-shangjiantou"></i> <!----></div>
</div>

@push('scripts')

    <script>
        $(function(){
            if($(".admin-category").height()<=200) {
                $(".unfold").hide();
                $(".admin-category").removeClass('fold');
            }else{
            	$(".admin-category").addClass("fold"); 
    		}
    		$(".unfold .icon-shangjiantou").hide()
    		$('.check-more').on('click',function(){
    			$(".admin-category").toggleClass('fold');
    			if($(".admin-category").hasClass('fold')){
    				$(".check-more").text("展开更多")
    				$(".unfold .icon-shangjiantou").hide()
    				$(".unfold .icon-xia").show()
    			}else{
    				$(".check-more").text("收起")
    				$(".unfold .icon-xia").hide()
    				$(".unfold .icon-shangjiantou").show()
    			}
            })
    	})
    </script>
@endpush