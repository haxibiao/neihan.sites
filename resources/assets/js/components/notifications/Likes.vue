<template>
	<!-- 收到的喜欢和赞 -->
	<div id="likes">
		<div class="menu">收到的喜欢和赞</div>
		<ul class="likes_list">
			<li>
				<div class="like_item">
					<a href="v1/user" class="avatar">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="title">
						<a href="javascript:;">中南工大留级生</a>
						<span>喜欢了你的文章</span>
						<a href="javascript:;" class="headline">《就是想撸猫》</a>
					</div>
					<div class="info">2017.11.15  08:09</div>
				</div>
			</li>
			<li>
				<div class="like_item">
					<a href="v1/user" class="avatar">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="title">
						<a href="javascript:;">中南工大留级生</a>
						<span>喜欢了你的文章</span>
						<a href="javascript:;" class="headline">《在web项目中添加自定义的font图标》</a>
					</div>
					<div class="info">2017.11.15  08:30</div>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Likes',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>