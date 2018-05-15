<template>
	<div class="pending-submissions">
		<div class="push-top">
			<router-link to="/requests" class="back-list active"><i class="iconfont icon-zuobian"></i> 返回投稿请求</router-link>
		</div>
		<ul class="article-list">
			<li　v-for="notification in notifications" class="article-item have-img">
			  <a class="wrap-img" :href="'/article/'+notification.article_id" target="_blank">
			      <img :src="notification.article_image_url" alt="">
			  </a>
			  <div class="content">
			    <div class="author">
			      <a class="avatar" target="_blank" :href="'/user/'+notification.user_id">
			        <img :src="notification.user_avatar" alt="">
			      </a> 
			      <div class="info">
			        <a class="nickname" target="_blank" :href="'/user/'+notification.user_id">{{ notification.user_name }}</a>
			        <span class="time">{{ notification.created_at }}</span>
			      </div>
			    </div>
			    <a class="title" target="_blank" :href="'/article/'+notification.article_id">
			        <span>{{ notification.article_title }}</span>
			    </a>
			    <p class="abstract">
			      {{ notification.article_description }}
			    </p>
			    <div class="meta">
			      <a target="_blank" :href="'/article/'+notification.article_id">
			        <i class="iconfont icon-liulan"></i> {{ notification.article_hits }}
			      </a>        
			      <a target="_blank" :href="'/article/'+notification.article_id">
			        <i class="iconfont icon-svg37"></i> {{ notification.article_count_replies }}
			      </a>      
			      <span><i class="iconfont icon-03xihuan"></i> {{ notification.article_count_likes }}</span>
			      <span　v-if="notification.article_count_tips"><i class="iconfont icon-qianqianqian"></i> {{ notification.article_count_tips }}</span>
			    </div>
			  </div>
			  <div class="push-action" v-if="notification.submited_status=='已收录'">
			      <span class="push-status">已收入<a class="push-remove" @click="remove(notification)">移除</a></span>
			      <span class="push-time">{{ notification.time }} 投稿</span>
			  </div>
			  <div class="push-action" v-if="notification.submited_status=='已拒绝'">
			      <span class="push-status">已拒绝</span>
			      <span class="push-time">{{ notification.time }} 投稿</span>
			  </div>
			  <div class="push-action" v-if="notification.submited_status=='已撤回'">
			      <span class="push-time">{{ notification.time }} 已撤回</span>
			  </div>
			  <div class="push-action" v-if="notification.submited_status=='待审核' || !notification.submited_status">
				  	<a class="btn-base btn-hollow btn-xs" @click="approve(notification)">接受</a>
				  	<a class="btn-base btn-gray btn-xs" @click="deny(notification)">拒绝</a>
			      <span class="push-time">{{ notification.time }} 投稿</span>
			  </div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: "PendingSubmissions",

	created() {
		var api_url = window.tokenize("/api/notifications/category_request");
		var _this = this;
		window.axios.get(api_url).then(function(response) {
			_this.notifications = response.data;
		});
	},

	methods: {
		requestApi(notification, api) {
			var _this = this;
			window.axios.get(api).then(function(response) {
				notification.submited_status = response.data.submited_status;
			});
		},
		approve(notification) {
			var api = window.tokenize("/api/categories/" + notification.article_id + "/approve-category-" + notification.category_id);
			this.requestApi(notification, api);
		},
		deny(notification) {
			var api = window.tokenize("/api/categories/" + notification.article_id + "/approve-category-" + notification.category_id + "?deny=1");
			this.requestApi(notification, api);
		},
		remove(notification) {
			var api = window.tokenize("/api/categories/" + notification.article_id + "/approve-category-" + notification.category_id + "?remove=1");
			this.requestApi(notification, api);
		}
	},

	data() {
		return {
			notifications: []
		};
	}
};
</script>


<style lang="scss" scoped>
.pending-submissions {
	.article-list {
		padding-top: 50px !important;
		.article-item {
			border-top: none !important;
			padding: 0 0 15px 0 !important;
			.push-action {
				margin-top: 10px;
				font-size: 12px;
				color: #969696;
				line-height: 20px;
				.btn-base {
					vertical-align: baseline;
					margin-right: 5px;
				}
				.push-time {
					font-size: 12px;
					color: #969696;
				}
				.push-status {
					margin-right: 5px;
					font-weight: 700;
					.push-remove {
						margin-left: 5px;
						font-weight: 400;
						color: #ff9d23;
						vertical-align: middle;
					}
				}
			}
		}
	}
}
</style>
