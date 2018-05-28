<template>
	<div class="pending-submissions">
		<div class="push-top">
			<router-link to="/requests" class="back-list active"><i class="iconfont icon-zuobian"></i> 返回投稿请求</router-link>
		</div>
		<ul class="article-list">
			<li　v-for="article in articles" class="article-item have-img">
			  <a class="wrap-img" :href="'/article/'+article.id" target="_blank">
			      <img :src="article.image_url" alt="">
			  </a>
			  <div class="content">
			    <div class="author">
			      <a class="avatar" target="_blank" :href="'/user/'+article.user.id">
			        <img :src="article.user.avatar" alt="">
			      </a> 
			      <div class="info">
			        <a class="nickname" target="_blank" :href="'/user/'+article.user_id">{{ article.user.name }}</a>
			        <span class="time">{{ article.created_at }}</span>
			      </div>
			    </div>
			    <a class="title" target="_blank" :href="'/article/'+article.id">
			        <span>{{ article.title }}</span>
			    </a>
			    <p class="abstract">
			      {{ article.description }}
			    </p>
			    <div class="meta">
			      <a target="_blank" :href="'/article/'+article.id">
			        <i class="iconfont icon-liulan"></i> {{ article.hits }}
			      </a>        
			      <a target="_blank" :href="'/article/'+article.id">
			        <i class="iconfont icon-svg37"></i> {{ article.count_replies }}
			      </a>      
			      <span><i class="iconfont icon-03xihuan"></i> {{ article.count_likes }}</span>
			      <span　v-if="article.count_tips"><i class="iconfont icon-qianqianqian"></i> {{ article.count_tips }}</span>
			    </div>
			  </div>
			  <div class="push-action" v-if="article.pivot.submit=='已收录'">
			      <span class="push-status">已收入<a class="push-remove" @click="remove(article)">移除</a></span>
			      <span class="push-time">{{ article.pivot.updated_at }} 投稿</span>
			  </div>
			  <div class="push-action" v-if="article.pivot.submit=='已拒绝'">
			      <span class="push-status">已拒绝</span>
			      <span class="push-time">{{ article.pivot.updated_at }} 投稿</span>
			  </div>
			  <div class="push-action" v-if="article.pivot.submit=='已撤回'">
			      <span class="push-time">{{ article.pivot.updated_at }} 已撤回</span>
			  </div>
			  <div class="push-action" v-if="article.pivot.submit=='待审核' || !article.pivot.submit">
				  	<a class="btn-base btn-hollow btn-xs" @click="approve(article)">接受</a>
				  	<a class="btn-base btn-gray btn-xs" @click="deny(article)">拒绝</a>
			      <span class="push-time">{{ article.pivot.updated_at }} 投稿</span>
			  </div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: "PendingSubmissions",

	created() {
		var api_url = window.tokenize("/api/categories/pending-articles");
		var _this = this;
		window.axios.get(api_url).then(function(response) {
			_this.articles = response.data;
		});
	},

	methods: {
		requestApi(article, api) {
			var _this = this;
			window.axios.get(api).then(function(response) {
				article.pivot = response.data.pivot;
			});
		},
		approve(article) {
			var api = window.tokenize(
				"/api/categories/approve-category-" +
					article.pivot.category_id +
					"-" +
					article.id
			);
			this.requestApi(article, api);
		},
		deny(article) {
			var api = window.tokenize(
				"/api/categories/approve-category-" +
					article.pivot.category_id +
					"-" +
					article.id +
					"?deny=1"
			);
			this.requestApi(article, api);
		},
		remove(article) {
			var api = window.tokenize(
				"/api/categories/approve-category-" +
					article.pivot.category_id +
					"-" +
					article.id +
					"?remove=1"
			);
			this.requestApi(article, api);
		}
	},

	data() {
		return {
			articles: []
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
						color: #d96a5f;
						vertical-align: middle;
					}
				}
			}
		}
	}
}
</style>
