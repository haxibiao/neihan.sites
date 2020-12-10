<template>
  <div class="movie-player row">
    <div class="col-lg-9 player-main">
      <div class="player-container player-fixed">
        <!-- <a class="player-fixed-off" href="javascript:;" style="display: none;"><i class="iconfont icon-close"></i></a> -->
        <div class="embed-responsive">
          <div class="embed-responsive video-player">
            <div class="fluid_video_wrapper">
              <template v-if="source">
                <video-player 
                :source="source" 
                :notice="noticeInfo"
                />
              </template>
            </div>
          </div>
        </div>
      </div>
      <ul class="player__operate">
        <li class="fl hide-xs">
          <a
            :class="['favorite', movie.favorited && 'highlight']"
            href="javascript:void(0);"
            v-on:click="favoriteHandler"
          >
            <i class="iconfont icon-collection-fill"></i>
            <span>{{movie.favorited ? '已收藏' : '收藏'}}</span>
          </a>
        </li>
        <li class="fl">
          <a
            class="digg_link"
            data-id="82651"
            data-mid="1"
            data-type="up"
            href="javascript:;"
          >
            <i class="iconfont icon-good-fill"></i>&nbsp;
            <span class="digg_num">164</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="col-lg-3 player-side">
      <div class="side-panel">
        <div class="movie-info col-pd">
          <div class="video_desc">
            <h3 class="title">
              {{ movie.name }}&nbsp;
              <small class="text-ep">第{{ episode }}集</small>
            </h3>
          </div>
          <div class="video_desc">
            <div class="type">
              9.0分&nbsp;/&nbsp;
              <a href="/">{{ movie.region }}</a
              >&nbsp;/&nbsp;
              <a href="/">{{ movie.type }}</a>
              &nbsp;/&nbsp;
              <a href="/">{{ movie.style }}</a>
            </div>
          </div>
        </div>
        <div class="video_playlist" id="playlist">
          <ul class="panel-content__list">
            <li
              class="col-xs-2"
              v-for="(media, index) in series"
              :key="media.id"
            >
              <a
                href="javascript:void(0)"
                :class="['btn-episode', episode == index + 1 && 'active']"
                v-on:click="clickEpisode(media, index + 1)"
                >{{ index + 1 }}</a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // TODO: 应该只接受一个movie对象，从对象里拿id和各类信息
  props: ["movie", "count", "token"],

  created() {
    // var routeParams = window.location.search

    this.episode = 1;
    this.fetchData();
  },

  methods: {
    clickEpisode(item, index) {
      this.episode = index;
      this.source = item.url;
      console.log(index + " source = ", this.source);
    },
    fetchData() {
      const that = this;
      // 获取电视剧集数，设置当前播放集数
      window.axios
        .get(`/api/movie/${that.movie.id}/series`)
        .then(function (response) {
          if (response && response.data) {
            let series = response.data;
            that.series = series;
            //默认播放第一集
            that.source = series[0].url;
            console.log("source", that.source);
          }
        })
        .catch((e) => {});
    },

    toggleFavorite() {
      this.movie.favorited = !this.movie.favorited;
    },
    // 收藏处理
    favoriteHandler() {
      if (!this.token) {
        $('#login-modal').modal('toggle');
        alert("请登陆");
        return;
      }
      const that = this;
      this.toggleFavorite();
      window.axios
        .post(
          `/api/movie/toggle-fan`,
          {
            movie_id: that.movie.id,
            type: 'movies'
          },
          {
            headers: {
              token: that.token
            }
          }
        )
        .then(function(response) {
          if (response && response.data) {
            that.noticeInfo = that.movie.favorited ? '视频已放到收藏夹' : '';
          } else {
            that.toggleFavorite();
          }
        })
        .catch(e => {
          that.toggleFavorite();
        });
    },
  },

  data() {
    return {
      episode: 1,
      source: null,
      series: [],
      noticeInfo: ''
    };
  },
};
</script>

<style lang="scss" scoped>
</style>
