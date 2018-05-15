<template>
	<div>
		<div class="menu">收到的喜欢和赞</div>
		<ul class="likes-list" v-if="notifications.length">
			<li v-for="notification in notifications">
				<div class="user-info info-xs">
					<a :href="'/user/'+notification.user_id" class="avatar">
						<img :src="notification.user_avatar" alt=""></a>
					<div class="title">
						<a :href="'/user/'+notification.user_id" class="nickname">{{ notification.user_name }}</a>
						<span>喜欢了你的文章</span>
						<a :href="'/article/' + notification.article_id " class="headline">《{{ notification.article_title }}》</a>
					</div>
					<div class="info">{{ notification.time }}</div>
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

  name: 'Likes',

  created() {
  	var api_url = window.tokenize('/api/notifications/like');
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

<style lang="css" scoped>
</style>