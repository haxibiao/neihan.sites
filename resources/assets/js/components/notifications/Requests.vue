<template>
	<!-- 收到的投稿请求 -->
	<div id="requests">
		<div class="menu">全部投稿请求</div>
		<ul class="requests_list">
			<li>
				<router-link to="/pending_submissions">
					<div class="avatar_collection all_push">
						<i class="iconfont icon-tougaoguanli"></i>
					</div>
					<div class="name">全部未处理请求</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/1/submissions" class="request_item">
					<div class="avatar_collection">
						<img src="/images/category_01.jpeg" />
						<span class="badge">5</span>
					</div>
					<div class="name">
						王者荣耀
						<span>有新投稿《起风了，谁是下一个王者荣耀？》</span>
					</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/2/submissions" class="request_item">
					<div class="avatar_collection">
						<img src="/images/category_04.jpeg" />
					</div>
					<div class="name">热恋</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/3/submissions" class="request_item">
					<div class="avatar_collection">
						<img src="/images/category_02.jpg" />
						<span class="badge">2</span>
					</div>
					<div class="name">
						暗恋
						<span>有新投稿《为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？》</span>
					</div>
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Requests',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>