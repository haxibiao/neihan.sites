<template>
	<transition name="slide">
		<div class="release-after" v-if="publishShow">
			<div class="release-info">
				<div class="after-info">
					<a :href="'/article/'+article.id" class="title">{{article.title}}</a>
					<br>
					<a :href="'/article/'+article.id" class="note">发布成功,点击查看文章</a>
				</div>
				<ul class="release-share">
					<li class="btn-base weixin-bg">
						<i class="iconfont icon-weixin1"></i>
						微信
					</li>
					<li class="btn-base weibo-bg">
						<i class="iconfont icon-sina"></i>
						微博
					</li>
					<li class="btn-base qqzone-bg">
						<i class="iconfont icon-qq1"></i>
						空间
					</li>
					<li class="btn-base qq-bg">
						<i class="iconfont icon-remen1"></i>
						复制链接
					</li>
				</ul>
			</div>
			<div class="close" @click="closeBtn">x</div>
			<div class="arc"></div>
			<div class="contribute">
				<div class="header">
					<div class="search-wrapper">
						<input type="text" placeholder="搜索专题" class="input-style" v-model="q" @keyup.enter="search">
						<i class="iconfont icon-sousuo"></i>
					</div>
					<div class="describe">向专题投稿，让文章被更多人发现</div>
				</div>
				<div class="categore-container" v-if="!q">
					<div class="categories">
						<h3>我管理的专题<a href="/category/create">新建</a></h3>
						<ul class="category-list clearfix">
							<li v-for="category in categoryList" class="col-xs-4">
								<img class="avatar-category" alt="png" :src="category.logo">
								<div class="info">
									<span>{{category.name}}</span>
								</div>
								<a :class="['action-btn',getBtnClass(category.submit_status)]" @click="add(category)">{{category.submit_status}}</a>
							</li>
						</ul>
					</div>
					<div class="categories">
						<!-- <h3>最近投稿</h3>
						<ul class="category-list clearfix">
							<li v-for="category in recentlyCategoryList" class="col-xs-4">
								<img class="avatar-category" alt="png" :src="category.logo">
								<div class="info">
									<span>{{category.name}}</span>
								</div>
								<a @click="submit(article)">{{category.submit_status}}</a>
							</li>
						</ul> -->
					</div>
					<div class="categories recommend">
						<h3>推荐专题</h3>
						<ul class="category-list clearfix">
							<li v-for="category in recommendCategoryList" class="col-xs-6">
								<img class="avatar-category" alt="png" :src="category.logo">
								<div class="info">
									<span>{{category.name}}</span>
									<em>{{ category.count }}篇文章·{{ category.follow }}人关注</em>
								</div>
								<a :class="['action-btn',getBtn2Class(category.submit_status)]" @click="submit(category)">{{category.submit_status}}</a>
							</li>
						</ul>
						<!-- <div class="end">没有更多了</div> -->
						<!-- 
						 -->
						   <a class="btn-base btn-more" href="javascript:;" @click="fetchMore">{{ page2 >= lastPage ? '已经到底了':'点击加载更多' }}...</a>
					</div>
				</div>
				<div class="search-categore" v-else>
					<div class="categories">
						<ul class="category-list clearfix">
							<li v-for="category in searchCategoryList" class="col-xs-4">
								<img class="avatar-category" alt="png" :src="category.logo">
								<div class="info">
									<span>{{category.name}}</span>
								</div>
								<a :class="['action-btn',getBtn2Class(category.submit_status)]" @click="submit(category)">{{category.submit_status}}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</transition>
</template>

<script>
export default {
	name: "PublishSuccessful",

	props: ["publishShow"],

	mounted() {
		this.fetchData();
	},

	computed: {
		article() {
			return this.$store.state.currentArticle;
		}
	},

	methods: {
		getBtnClass(status) {
			switch (status) {
				case "收录":
					return "btn-base btn-hollow btn-sm";
			}
			return "btn-base btn-hollow theme-tag btn-sm";
		},
		getBtn2Class(status) {
			switch (status) {
				case "投稿":
					return "btn-base btn-hollow btn-sm";
				case "再次投稿":
					return "btn-base btn-hollow btn-sm";
			}
			return "btn-base btn-hollow theme-tag btn-sm";
		},
		closeBtn() {
			this.$store.commit("PUBLISH_STATUS");
		},
		apiAdmin() {
			var api = "/api/categories/admin-check-article-" + this.articleId;
			if (this.q) {
				api = api + "?q=" + this.q;
			}
			return window.tokenize(api);
		},
		apiRecommend() {
			var page2 = this.page2;
			var api = "/api/categories/recommend-check-article-" + this.articleId + "?page=" + page2;
			return window.tokenize(api);
		},
		apiRecent() {
			var api = "/api/categories/recently";
			return window.tokenize(api);
		},
		add(category) {
			var api = window.tokenize("/api/categories/" + this.article.id + "/add-category-" + category.id);
			axios.get(api).then(response => {
				category.submit_status = response.data.submit_status;
			});
		},
		submit(category) {
			var api = window.tokenize("/api/categories/" + this.article.id + "/submit-category-" + category.id);
			axios.get(api).then(response => {
				category.submit_status = response.data.submit_status;
			});
		},
		fetchManage() {
			var _this = this;
			window.axios.get(this.apiAdmin()).then(function(response) {
				if (_this.page == 1) {
					_this.categoryList = response.data.data;
				} else {
					_this.categoryList = _this.categoryList.concat(response.data.data);
					_this.page = response.data.currentPage;
					_this.page_total = response.data.lastPage;
				}
			});
		},

		fetchMore() {
			++this.page2;
			if (this.lastPage > 0 && this.page2 > this.lastPage) {
				//TODO: ui 提示  ...
				return;
			}
			this.fetchRecomand();
		},
		fetchRecomand() {
			var _this = this;
			window.axios.get(this.apiRecommend()).then(function(response) {
				_this.recommendCategoryList = _this.recommendCategoryList.concat(response.data.data);
				_this.page2 = response.data.currentPage;
				_this.page2_total = response.data.lastPage;
				_this.lastPage = response.data.last_page;
			});
		},
		fetchData() {
			this.fetchManage();
			this.fetchRecomand();
		},
		search() {
			this.page = 1;
			var _this = this;
			var api = "/api/categories/search-submit-for-article-" + this.article.id + "?q=" + this.q;
			window.axios.get(api).then(function(response) {
				_this.searchCategoryList = response.data.data;
			});
		}
	},

	data() {
		return {
			currentPage: 1,
			searchResult: [],
			q: null,
			lastPage: -1,
			page: 1,
			page_total: 1,
			page2: 1,
			page2_total: 1,
			categoryList: [],
			recommendCategoryList: [],
			categoryList: [],
			searchCategoryList: []
		};
	}
};
</script>
