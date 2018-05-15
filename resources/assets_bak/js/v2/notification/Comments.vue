<template>
	<!-- 收到的评论 -->
	<div id="comments">
		<div class="notification_menu">收到的评论</div>
		<ul class="comment_list">
			<li>
				<div class="author">
					<a href="notification.user_id" class="avatar avatar_xs">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="info_meta">
						<div class="info">
							<a href="javascript: ;" class="user">中南工大留级生</a>
							<span>评论了你的文章</span>
							<a href="javascript: ;" class="title">《就是想撸猫》</a>
						</div>
						<div class="time">2017.11.15  08:09</div>
					</div>
				</div>
				<div class="comment_wrap">
					<p>这位少年，我觉得你非常有想法，跟我来打游戏吧！</p>
					<div class="tool_group">
						<a href="'/article/'+notification.article_id+'#'+notification.lou" class="action_btn">
							<i class="iconfont icon-xinxi2"></i>
							<span>回复</span>
						</a>
						<a href="'/article/'+notification.article_id+'#1'" class="action_btn">
							<i class="iconfont icon-zhuanfa2"></i>
							<span>查看对话</span>
						</a>
						<a href="javascript: ;" class="report action_btn">
							<span>举报</span>
						</a>
					</div>
				</div>
			</li>
			<li>
				<div class="author">
					<a href="notification.user_id" class="avatar avatar_xs">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="info_meta">
						<div class="info">
							<a href="javascript: ;" class="user">中南工大留级生</a>
							<span>在文章</span>
							<a href="javascript: ;" class="title">《简书撩到男友已弱爆，我们结婚了！》</a>
							<span>的评论中提到了你</span>
						</div>
						<div class="time">2017.11.15  09:09</div>
					</div>
				</div>
				<div class="comment_wrap">
					<p>
						<a href="/v2/user" class="moleskine_author">@累活人生百态</a>
						<a href="/v2/user" class="moleskine_author">@惋惜</a>
						<a href="/v2/user" class="moleskine_author">@一纸</a>
						<span>听说写文章能找到男朋友,听说写文章能找到男朋友</span>
					</p>
					<div class="tool_group">
						<a href="'/article/'+notification.article_id+'#'+notification.lou" class="action_btn">
							<i class="iconfont icon-xinxi2"></i>
							<span>回复</span>
						</a>
						<a href="'/article/'+notification.article_id+'#1'" class="action_btn">
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