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
                <a :href="'/video/'+article.video_id" class="video-info" :target="isDesktop? '_blank' : '_self'">     <img class="video-photo" :src="article.image_url">
                    <i class="hover-play"> </i>
                </a>
                <a :href="'/video/'+article.video_id"  class="video-title" :target="isDesktop? '_blank' : '_self'">{{ article.title }}</a>
                <div class="info">
                    <a class="user" :href="'/user/'+article.user_id">
                        <img :src="article.user.avatar" class="avatar">
                        <span>{{ article.user.name }}</span>                          
                    </a>
                    <div class="num">
                        <i class="iconfont icon-liulan"> {{ article.hits }}</i>
                        <i class="iconfont icon-svg37" v-if="article.count_comments > 0"> {{ article.count_replies }}</i>
                        <i class="iconfont icon-svg37" v-else> 0</i>
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
  name: "VideoList",

  props: ["api", "startPage","isDesktop"],

  watch: {
    api(val) {
      this.clear();
      this.fetchData();
    }
  },

  computed: {
    apiUrl: {
      get(){
        var page = this.page;
        var api = this.api ? this.api : this.apiDefault;
        var api_url =
          api.indexOf("?") !== -1 ? api + "&page=" + page : api + "?page=" + page;
        if(page == 1) api_url +='&stick=true';
        return api_url;
      }
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
      $(window).on("scroll", function() {
        var aheadMount = 5;
        var is_scroll_to_botton =
          $(this).scrollTop() >=
          $("body").height() - $(window).height() - aheadMount;
        if (is_scroll_to_botton) {
          m.fetchMore();
        }
      });
    },
    fetchMore() {
      ++this.page;
      if (this.lastPage > 0 && this.page > this.lastPage) {
        console.log("已经到底了");
        return;
      }
      this.fetchData();
    },
    fetchData() {
      var vm = this;
      console.log(this.apiUrl);
      window.axios.get(this.apiUrl).then(function(response) {
        vm.articles = vm.articles.concat(response.data.data);
        vm.lastPage = response.data.lastPage;
      });
    }
  },

  data() {
    return {
      apiDefault: "",
      page: this.startPage ? this.startPage : 1,
      lastPage: -1,
      articles: []
    };
  }
};
</script>

<style lang="css" scoped>
</style>
