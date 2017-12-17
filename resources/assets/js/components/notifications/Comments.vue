<template>
	<!-- 收到的评论 -->
	<div id="comments">
		<div class="menu">收到的评论</div>
		<ul class="comment_list">
			<li v-for="notification in notifications">
				<div class="comment_head">
					<a :href="notification.user_id" class="avatar">
						<img :src="notification.user_avatar" />
					</a>
					<div class="title">
						<a href="javascript: ;">{{ notification.user_name }}</a>
						<span>评论了你的文章</span>
						<a href="javascript: ;" class="headline">{{ notification.article_title }}</a>
					</div>
					<div class="info">{{ notification.time }}</div>
				</div>
				<p>{{ notification.comment }}</p>
				<div class="tool_group">
					<a :href="'/article/'+notification.article_id+'#'+notification.lou">
						<i class="iconfont icon-xinxi2"></i>
						<span>回复</span>
					</a>
					<a href="'/article/'+notification.article_id+'#1'">
						<i class="iconfont icon-zhuanfa2"></i>
						<span>查看对话</span>
					</a>
					<a href="javascript: ;" class="report">
						<span>举报</span>
					</a>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Comments',

  computed:{
  	  current_user_id(){
  	  	  return window.current_user_id;
  	  }
  },

  created(){
  	   var api_url=window.tokenize('/api/notifications/comment');
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