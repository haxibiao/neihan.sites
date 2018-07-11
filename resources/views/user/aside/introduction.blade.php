<div class="plate-title">
	个人介绍
	@if($user->isSelf())
		<a class="right write" href="javascript:void(0)" style="height:auto"><i class="iconfont icon-bi1"></i>编辑</a>
	@endif
</div>
<div class="description distance">
	<p class="cont" id="introduction_text">
		{{ $user->introduction() }}
	</p>

	@if($user->isSelf())
		<form class="profile-edit intro-form" action="#" method="post">
		    <textarea name="introduction" id="user_intro">{{ $user->introduction() }}</textarea>
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
					$.post(window.tokenize('/api/user'),{
						introduction: $('#user_intro').val()
					});	
					
					//乐观更新ui..
					$('#introduction_text').html($('#user_intro').val());
					$('.intro-form').hide();
					$('.description .cont').show();
				});
				
			</script>
		@endpush
	@endif

	<a href="javascript:;" class="social">
		<img src="/images/weibo.svg" alt="">
	</a>
</div>