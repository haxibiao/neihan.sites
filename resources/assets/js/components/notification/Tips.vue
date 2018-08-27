<template>
	<div>
		<div class="menu">收到的赞赏</div>
		<ul class="money-list" v-if="notifications.length">
			<li v-for="notification in notifications">
				<div class="user-info info-xs">
					<a :href="'/user/'+notification.user_id" class="avatar">
						<img :src="notification.user_avatar" alt=""></a>
					<div class="title">
						<a :href="'/user/'+notification.user_id" class="nickname">{{ notification.user_name }}</a>
						<span>向你的作品</span>
						<a :href="'/article/' + notification.article_id " class="headline">{{ notification.article_title }}</a>
						<span>赞赏 <i class="money">¥ {{ notification.amount }}.00</i></span>
					</div>
					<div class="info">{{ notification.time }}</div>
				</div>
				<p>{{ notification.message }}</p>
				<a class="btn-base btn-hollow btn-sm" :href="'/chat/with/'+notification.user_id">消息回复</a>
			</li>
		</ul>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>
</template>

<script>
export default {
	name: "Tips",

	created() {
		var api_url = window.tokenize("/api/notifications/tip");
		var vm = this;
		window.axios.get(api_url).then(function(response) {
			vm.notifications = response.data;
		});
	},

	data() {
		return {
			notifications: []
		};
	}
};
</script>

<style lang="scss" scoped>
.money-list {
	li {
		.user-info {
			margin-bottom: 10px;
			line-height: 1.7;
			.nickname {
				font-size: 14px;
				vertical-align: unset;
			}
			.money {
				color: #d96a5f;
			}
			.info {
				padding-left: 48px;
			}
		}
		p {
			font-size: 15px;
		}
		.btn-base {
			margin-top: 10px;
		}
	}
}
</style>
