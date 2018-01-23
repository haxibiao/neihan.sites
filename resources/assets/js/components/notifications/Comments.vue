<template>
	<!-- 收到的评论 -->
	<div id="comments">
		<div class="notification_menu">收到的评论</div>
		<ul class="comment_list">
			<li v-for="notification in notifications">
				<div class="author">
					<a :href="notification.user_id" class="avatar">
						<img :src="notification.user_avatar" />
					</a>
				  <div class="info_meta">	
					<div class="info">
						<a href="javascript: ;" class="user">{{ notification.user_name }}</a>
						<span>评论了你的文章</span>
						<a href="javascript: ;" class="title">{{ notification.article_title }}</a>
					</div>
					<div class="time">{{ notification.time }}</div>
				  </div>
				</div>
				<div class="comment_wrap">
					<div class="tool_group">
						<p>{{ notification.comment }}</p>
						<a :href="'/article/'+notification.article_id+'#'+notification.lou" class="action_btn">
							<i class="iconfont icon-xinxi2"></i>
							<span>回复</span>
						</a>
						<a :href="'/article/'+notification.article_id+'#1'" class="action_btn">
							<i class="iconfont icon-zhuanfa2"></i>
							<span>查看对话</span>
						</a>
						<a href="javascript: ;" class="report action_btn">
							<span>举报</span>
						</a>
					</div>
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

<style lang="scss" scoped>
	#comments {
        .comment_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                font-size: 15px;
                .author {
                    .info_meta {
                        line-height: 1.7;
                        padding: 0 0 0 50px;
                        .info {
                            .user {
                                margin-right: 5px;
                            }
                            .title {
                                color: #2B89CA;
                            }
                        }
                    }
                }
                .comment_wrap {
                    p {
                        font-size: 15px;
                        .moleskine_author {
                        	margin-right: 5px;
                        }
                    }
                    .tool_group {
                    	.action_btn {
	                        font-size: 13px;
	                        i {
	                            font-size: 15px;
	                        }
	                    }
                    }
                }
                @media screen and (max-width: 540px) {
                    padding: 20px 5px;
                }
            }
        }
    }
</style>