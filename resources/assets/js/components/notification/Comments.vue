<template>
	<div>
		<div class="menu">收到的评论</div>
		<ul class="comment-list" v-if="notifications.length">
			<li v-for="notification in notifications">
				<div class="user-info info-xs">
					<a :href="'/user/'+notification.user_id" class="avatar"><img :src="notification.user_avatar" alt=""></a>
					<div class="title">
						<a :href="'/user/'+notification.user_id">{{ notification.user_name }}</a>
						<span>评论了你的文章</span>
						<a :href="'/article/'+notification.article_id">《{{ notification.article_title }}》</a>
					</div>
					<div class="info">{{ notification.time }}</div>
				</div>
				<p>{{ notification.comment }}</p>
				<div class="tool-group">
					<a :href="'/article/'+notification.article_id+'#'+notification.lou"><i class="iconfont icon-xinxi2"></i><span>回复</span></a>
					<a :href="'/article/'+notification.article_id+'#1'"><i class="iconfont icon-zhuanfa2"></i><span>查看对话</span></a>
					<a href="javascript:;" class="report"><span>举报</span></a>
				</div>
			</li>
		</ul>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Comments',

  created() {
  	var api_url = window.tokenize('/api/notifications/comment');
  	var vm = this;
  	window.axios.get(api_url).then(function(response) {
  		vm.notifications = response.data;
  	});

  },

  data () {
    return {
    	notifications: []
    }
  }
}
</script>

<style lang="scss" scoped>
.comment-list {
	li {
		font-size: 15px;
		p {
			margin: 10px 0;
		}
		.user-info {
			.title {
				font-weight: normal;;
			}
		}
		.tool-group {
			a {
				&:nth-child(2) {
					margin-left: 10px;
				}
			}
			i {
				font-size: 16px;
			}
			span {
				font-size: 13px;
			}
		}
	}
}
</style>