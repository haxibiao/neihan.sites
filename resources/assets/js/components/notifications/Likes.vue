<template>
	<!-- 收到的喜欢和赞 -->
	<div id="likes">
		<div class="notification_menu">收到的喜欢和赞</div>
		<ul class="likes_list">
			<li v-for="notification in notifications">
				<div class="author">
					<a :href="'/user/'+notification.user_id" class="avatar avatar_xs">
						<img :src="notification.user_avatar" />
					</a>
					 <div class="info_meta">
						<div class="info">
							<a href="javascript:;" class="user">{{ notification.user_name }}</a>
							<span>喜欢了你的文章</span>
							<a :href="'/article/'+notification.article_id" class="title">{{  notification.article_title}}</a>
						</div>
						<div class="time">{{ notification.time }}</div>
					</div>
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

<style lang="scss" scoped>
	#likes {
        .likes_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                .author {
                	line-height: 1.7;
                    font-size: 15px;
                	.avatar {
                        float: left;
                    }
                    .info_meta {
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
                @media screen and (max-width: 540px) {
                    padding: 20px 5px;
                }
            }
        }
    }
</style>