<template>
	<div class="article_list">
    <li v-for="article in articles" :class="article.has_image ? 'article_item have_img' : 'note_item'">

        <a v-if="article.has_image" class="wrap_img" href="javascript:;" target="_blank">
            <img :src="article.primary_image" :alt="article.title">
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" :href=" '/user/' + article.user.id" target="_blank">
                    <img :src="article.user.avatar"/>
                </a>
                <div class="info">
                    <a :href="'/user/' + article.user.id" target="_blank">
                        {{ article.user.name }}
                    </a>
                    <a :href="'/user/' + article.user.id" target="_blank">
                        <img src="/images/vip1.png"/>
                    </a>
                    <span class="time">
                        {{ article.timeAgo }}
                    </span>
                </div>
            </div>
            <a class="title" :href="'/article/' + article.id" target="_blank">
                {{ article.title }}
            </a>
            <p class="abstract">
                {{ article.description }}
            </p>
            <div class="meta">
                <a class="category_tag" :href=" '/' + article.category.name_en" target="_blank">
                    {{ article.category.name }}
                </a>
                <a href="#" target="_blank">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ article.hits }}
                </a>
                <a href="#" target="_blank">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ article.count_replies }}
                </a>
                <span>
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ article.count_favorites }}
                </span>
            </div>
        </div>
    </li>

      <a class="button btn_load_more" href="javascript:;">{{ page >= lastPage ? '已经到底了':'正在加载更多' }}...</a>

	  </div>
</template>

<script>
export default {

  name: 'ArticleList',

  props: ['api','startPage'],

  computed: {
  	apiUrl() {
  		var page = this.page;
  		var api = this.api ? this.api : this.apiDefault;
  		var api_url = api.indexOf('?') !== -1 ? api + '&page='+page : api + '?page='+page;
  		return api_url;
  	}
  },

  mounted() {
  	this.fetchData();
  	this.listenScrollBottom();
  },

  methods: {
  	listenScrollBottom() {
  		var m = this;
  		$(window).on("scroll", function() {
        var aheadMount =5;
  			var is_scroll_to_bottom=$(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
  			if(is_scroll_to_bottom){
  				m.fetchMore();
  			}
  		});
  	},

  	fetchMore() {
  		++this.page;
	  	if(this.lastPage > 0 && this.page > this.lastPage) {
	  		console.log('我是有底线的');
	  		//TODO: ui 提示  ...
	  		return;
	  	}
      this.fetchData();
  	},

    fetchData(){
      var m = this;
      //TODO:: loading ....
      window.axios.get(this.apiUrl).then(function(response){
        m.articles = m.articles.concat(response.data.data);
        m.lastPage = response.data.last_page;

        //TODO:: loading done !!!
      });
    }
  },

  data () {
    return {
    	apiDefault: '',
    	page: this.startPage ? this.startPage : 1,
    	lastPage: -1,
    	articles: [],
    }
  }
}
</script>

<style lang="css" scoped>
</style>