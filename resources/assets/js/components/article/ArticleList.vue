<template>
	<div>	
		<li v-for="article in articles" :class="article.has_image ? 'content-item have-img' : 'content-item'">
		  <a v-if="article.has_image" class="wrap-img" :href="article.url" :target="isDesktop? '_blank' : '_self'">
		      <img :src="article.primary_image" :alt="article.title">
		      <span v-if="article.type =='video'" class="rotate-play">
		        <i class="iconfont icon-shipin"></i>
		      </span>
		      <i  v-if="article.type =='video'" class="duration">{{ videotime }}</i>  <!--当为视频时,取出视频的时长 -->
		  </a>
		  <div class="content">
	    	<div v-if="article.type !=='article'" class="author">
	    	  <a class="avatar" :target="isDesktop? '_blank' : '_self'" :href="'/user/'+article.user.id">
	    	    <img :src="article.user.avatar" alt="">
	    	  </a> 
	    	  <div class="info">
	    	    <a class="nickname" :target="isDesktop? '_blank' : '_self'" :href="'/user/'+article.user.id">{{ article.user.name }}</a>
	    	    <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" alt="">
	    	    <span class="time">{{ article.time_ago }}</span>
	    	  </div>
	    	</div>
		    <a v-if="article.type =='article'" class="title" :target="isDesktop? '_blank' : '_self'" :href="article.url">
		        <span>{{ article.title }}</span>
		    </a>
		    <p class="abstract">{{ article.description?article.description:article.title }}</p>
		    <div class="meta">
		      <a v-if="article.category" class="category" :target="isDesktop? '_blank' : '_self'" :href="'/' + article.category.name_en">
        		<i class="iconfont icon-zhuanti1"></i>
		      	{{ article.category.name }}
		  	  </a>
		      <a v-if="article.type =='article'" class="nickname" :target="isDesktop? '_blank' : '_self'" :href="'/user/'+article.user.id">{{ article.user.name }}</a>
		      <a  class="hidden-xs" target="_blank" :href="article.url">
		        <i class="iconfont icon-liulan"></i> {{ article.hits }}
		      </a>        
		      <a :target="isDesktop? '_blank' : '_self'" :href="article.url+'/#comments'" class="comment_meta">
		        <i class="iconfont icon-svg37"></i> {{ article.count_replies }}
		      </a>
		      <a :target="isDesktop? '_blank' : '_self'" :href="article.url"><i class="iconfont icon-03xihuan"></i> {{ article.count_likes }}</a>
		      <a v-if="article.count_tips" :target="isDesktop? '_blank' : '_self'" :href="article.url"><i class="iconfont icon-qianqianqian"></i> {{ article.count_tips }}</a>
		    </div>
		  </div>
		</li> 
		<loading-more  v-if="articles.length || notEmpty" :end="end"></loading-more>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>	
</template>

<script>
export default {
	name: "ArticleList",

	props: ["api", "startPage", "notEmpty", "isDesktop"],

	watch: {
		api(val) {
			this.clear();
			this.fetchData();
		}
	},
	computed: {
		apiUrl() {
			var page = this.page;
			var api = this.api ? this.api : this.apiDefault;
			var api_url = api.indexOf("?") !== -1 ? api + "&page=" + page : api + "?page=" + page;
			return api_url;
		}
	},

	mounted() {
		this.listenScrollBottom();
		this.fetchData();
	},
	methods: {
		clear() {
			this.articles = [];
		},
		listenScrollBottom() {
			var m = this;
			$(window).on("scroll", function() {
				var aheadMount = 5; //sometimes need ahead a little ...
				var is_scroll_to_bottom = $(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
				if (is_scroll_to_bottom) {
					m.fetchMore();
				}
			});
		},

		fetchMore() {
			++this.page;
			if (this.lastPage > 0 && this.page > this.lastPage) {
				//TODO: ui 提示  ...
				return;
			}
			this.fetchData();
		},

		fetchData() {
			var m = this;
			//TODO:: ui show loading ....
			window.axios.get(this.apiUrl).then(function(response) {
				m.articles = m.articles.concat(response.data.data);
				m.lastPage = response.data.last_page;
				$('[data-toggle="tooltip"]').tooltip();
				if (m.page >= m.lastPage) {
					m.end = true;
				}
				//TODO:: ui show loading done !!!
			});
		}
	},

	data() {
		return {
			apiDefault: "",
			page: this.startPage ? this.startPage : 1,
			lastPage: -1,
			articles: [],
			end: false,
			videotime: "1:30" //TODO:: 视频长度应该从Api为每个item获取，不是在这里
		};
	}
};
</script>

<style lang="css" scoped>
</style>
