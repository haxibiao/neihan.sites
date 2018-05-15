<template>
	<!-- 收到的喜欢和赞 -->
	<div id="likes">
		<div class="notification_menu">收到的喜欢和赞</div>
		<ul class="likes_list">
			<li>
				<div class="author">
					<a href="" class="avatar avatar_xs">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="info_meta">
						<div class="info">
							<a href="javascript: ;" class="user">中南工大留级生</a>
							<span>喜欢了你的文章</span>
							<a href="javascript: ;" class="title">《就是想撸猫》</a>
						</div>
						<div class="time">2017.11.15  08:09</div>
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