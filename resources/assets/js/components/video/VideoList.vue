<template>
	<div class="video-box">
    <div class="box-top">
        <div class="top-left">
            <i class="iconfont icon-fabulous"></i>
            <a href="/">
                <p class="title">更多推荐</p>
            </a>
        </div>
    </div>
    <div class="box-body">
       <ul class="game-video-list">
    		  <li v-for="article in articles" class="game-video-item">           
              <a href="/video/287" class="video-info">   
                  <img class="video-photo" src="https://www.ainicheng.com/storage/video/287.jpg">
                  <i class="hover-play"> </i>
              </a>
              <a href="">绝地求生官方宣传视频</a>
              <div class="info">
                  <a class="user" href="/user/270">
                      <img src="https://ainicheng.com/storage/avatar/270.jpg" class="avatar">
                      <span>风清歌</span>                          
                  </a>
                  <div class="num">
                      <i class="iconfont icon-liulan"> 11</i>
                      <i class="iconfont icon-svg37"> 20</i>
                  </div>
              </div>
      	   </li>
        </ul>
    </div>
		<!-- 查看更多视频 -->
        <a class="btn-base btn-more" href="javascript:;">
            {{ page >= lastPage ? '已经到底了' : '正在加载更多' }}
        </a>
	</div>
</template>

<script>
export default {

  name: 'ArticleList',

  props: ['api','startPage'],

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
      var api_url = api.indexOf('?') !== -1 ? api + '&page=' + page: api + '?page=' + page;
      return api_url;
    }
  },

  mounted() {
    this.listenScrollBotton();
    this.fetchData();
  },

  methods: {
    clear() {
      this.articles = [];
    },
    listenScrollBotton() {
      var m = this;
      // console.log($(this).scrollTop(), $("body").height(true),$(window).height());
      $(window).on("scroll", function() {
        var aheadMount = 5;
        var is_scroll_to_botton = $(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
        console.log( $("body").height()-$(window).innerHeight())
        console.log($(this).scrollTop());
        console.log($(window).height());
        console.log(is_scroll_to_botton);
        if (is_scroll_to_botton) {
          m.fetchMore();
        }
      });
    },
    fetchMore() {
      ++this.page;
      if (this.lastPage > 0 && this.page > this.lastPage) {
        console.log('已经到底了');
        return;
      }
      this.fetchData();
    },
    fetchData() {
      var vm = this;
      window.axios.get(this.apiUrl).then(function(response) {
        vm.articles = vm.articles.concat(response.data.data);
        vm.lastPage = response.data.lastPage;
      })
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