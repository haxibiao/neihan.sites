<template>
	<!-- 收到的喜欢和赞 -->
	<div id="likes">
		<div class="menu">收到的喜欢和赞</div>
		<ul class="likes_list">
			<li v-for="notification in notifications">
				<div class="like_item">
					<a href="'/user/+notification.user_id'" class="avatar">
						<img :src="notification.user_avatar" />
					</a>
					<div class="title">
						<a href="javascript:;">{{ notification.user_name }}</a>
						<span>喜欢了你的文章</span>
						<a :href="'/article/'+notification.article_id" class="headline">{{  notification.article_title}}</a>
					</div>
					<div class="info">{{ notification.time }}</div>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Likes',
  
  created(){
  	 var api_url=window.tokenize('/api/notifications/like');
  	 var vm=this;
  	 window.axios.get(api_url).then(function(response){
  	 	 vm.notifications=response.data;
  	 });
  },

  data () {
    return {
        notifications:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>