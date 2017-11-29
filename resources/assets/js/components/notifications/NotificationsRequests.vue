<template>
	<div id="requests">
		<div class="menu">全部投稿请求</div>
		<ul class="requests_list">
			<li>
				<a href="javascript:;" class="all_request">
					<div class="avatar">
						<i class="iconfont icon-tougaoguanli"></i>
					</div>
					<div class="name">全部未处理请求</div>
				</a>
			</li>
			<li>
				<div class="request_item">
					<a href="javascript:;" class="avatar">
						<img src="/images/category_01.jpeg" />
					</a>
					<a href="javascript:;" class="name">王者荣耀</a>
				</div>
			</li>
			<li>
				<div class="request_item">
					<a href="javascript:;" class="avatar">
						<img src="/images/category_04.jpeg" />
					</a>
					<a href="javascript:;" class="name">热恋</a>
				</div>
			</li>
			<li>
				<div class="request_item">
					<a href="javascript:;" class="avatar">
						<img src="/images/category_02.jpg" />
					</a>
					<a href="javascript:;" class="name">暗恋</a>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'NotificationsRequests',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>