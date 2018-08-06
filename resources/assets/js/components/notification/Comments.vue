<template>
	<div>
		<div class="menu">收到的评论</div>
		<ul class="comment-list" v-if="notifications.length">
			<li v-for="(notification,index) in notifications">
				
				<div class="user-info info-xs">
					<a :href="'/user/'+notification.user_id" class="avatar"><img :src="notification.user_avatar" alt=""></a>
					
					<div v-if="notification.title" class="title" v-html="notification.title">
					</div>
					<div v-else class="title">
						<a :href="'/user/'+notification.user_id">{{ notification.user_name }}</a>
						<span>评论了你的作品</span>
						<a :href="'/article/'+notification.article_id">《{{ notification.article_title }}》</a>
					</div>

					<div class="info">{{ notification.time }}</div>
				</div>

				<!-- 这里如果是评论下的评论 返回的comment会是一个object -->
				<p v-if="notification.comment.body" v-html="notification.comment.body"></p>
				<p v-else v-html="notification.comment"></p>
				
				<div class="tool-group">
					<a @click="toggleReplyComment(notification,index)"><i class="iconfont icon-xinxi2"></i><span>回复</span></a>
					<a :href="notification.url+'#1'"><i class="iconfont icon-zhuanfa2"></i><span>查看对话</span></a>
					<a href="javascript:;" class="report"><span>举报</span></a>
				</div>
				<reply-comment :is-show="notification.replying" style="margin-top: 18px" :body="'@'+notification.user_name+' '" @sendReply="(body)=>sendReply(body,notification,index)" @toggle-replycomment="toggleReplyComment(notification,index)"></reply-comment>
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

  methods: {
  	postApiUrl() {
  	  return window.tokenize("/api/comment");
  	},
  	toggleReplyComment(notification,index) {
  	   notification.replying = !notification.replying;
	   this.$set(this.notifications,index,notification);
  	},
  	//回复评论
  	sendReply(body,notification,index) {
  	  var _this = this;
  	  window.axios
  	    .post(this.postApiUrl(), _this.gotReplyComment(body,notification))
  	    .then(function(response) {
  	      _this.toggleReplyComment(notification,index);
  	    });
  	},
  	gotReplyComment(body,notification) {
	   let replyComment = {};
	   replyComment.comment_id = notification.comment_id;
	   replyComment.commentable_id = notification.article_id;
	   replyComment.body = body;
	   replyComment.commentable_type = "articles";
	   replyComment.is_reply = 1;
	   return replyComment;
	}
  },

  data () {
    return {
    	notifications: [],
    }
  }
}
</script>

<style lang="scss" >
.comment-list {
	li {
		font-size: 15px;
		p {
			margin: 10px 0;
			a {
				color: #2b89ca;
			}
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