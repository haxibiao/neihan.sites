<template>
	<div id="chats">
		<div class="menu">全部消息</div>
		<ul class="chats_list">
			<li>
				<div class="dropdown time">
					<a href="javascript: ;" class="dropdown-toggle" data-toggle="dropdown">
						10:08
						<i class="iconfont icon-xia"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">
								<i class="iconfont icon-lajitong"></i>
								<span>删除会话</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-heimingdan1"></i>
								<span>加入黑名单</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-iconset03100"></i>
								<span>删举报用户</span>
							</a>
						</li>
					</ul>
				</div>
				<router-link to="/chat_messages">
					<div class="chats_item">
						<a href="" class="avatar">
							<img src="/images/photo_01.jpg" />
						</a>
						<div class="title">
							<a href="#" class="nickname">喵星菇凉</a>
						</div>
						<div class="info">
							<p>呵呵哒</p>
						</div>
					</div>
				</router-link>
			</li>
			<li>
				<div class="dropdown time">
					<a href="javascript: ;" class="dropdown-toggle" data-toggle="dropdown">
						11.15 13:56
						<i class="iconfont icon-xia"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">
								<i class="iconfont icon-lajitong"></i>
								<span>删除会话</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-heimingdan1"></i>
								<span>加入黑名单</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-iconset03100"></i>
								<span>删举报用户</span>
							</a>
						</li>
					</ul>
				</div>
				<router-link to="/chat_messages">
					<div class="chats_item">
						<a href="" class="avatar">
							<img src="/images/photo_02.jpg" />
						</a>
						<div class="title">
							<a href="#" class="nickname">中南工大留级生</a>
						</div>
						<div class="info">
							<p>我还想继续赞赏你。</p>
						</div>
					</div>
				</router-link>
			</li>
			<li>
				<div class="dropdown time">
					<a href="javascript: ;" class="dropdown-toggle" data-toggle="dropdown">
						11.12 12:30
						<i class="iconfont icon-xia"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">
								<i class="iconfont icon-lajitong"></i>
								<span>删除会话</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-heimingdan1"></i>
								<span>加入黑名单</span>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="iconfont icon-iconset03100"></i>
								<span>删举报用户</span>
							</a>
						</li>
					</ul>
				</div>
				<router-link to="/chat_messages">
					<div class="chats_item">
						<a href="" class="avatar">
							<img src="/images/photo_03.jpg" />
						</a>
						<div class="title">
							<a href="#" class="nickname">小食神</a>
						</div>
						<div class="info">
							<p>欢迎加入丰富多彩的原创内容社区——懂美味。有任何疑问（如何投稿，专题主编，签约作者）可以随时联系我哟。</p>
						</div>
					</div>
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'NotificationsChats',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>