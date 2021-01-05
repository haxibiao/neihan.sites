<template>
  <div class="movie-player row">
    <div class="col-lg-9 player-main">
      <div class="player-container player-fixed">
        <!-- <a class="player-fixed-off" href="javascript:;" style="display: none;"><i class="iconfont icon-close"></i></a> -->
        <div class="embed-responsive">
          <div class="embed-responsive video-player">
            <div class="fluid_video_wrapper">
              <template v-if="source">
                <video-player :source="source" />
              </template>
            </div>
          </div>
        </div>
      </div>
      <ul class="player__operate">
        <li class="fl">
          <a class="favorite" href="javascript:void(0);" data-type="2" data-mid="1" data-id="82651">
            <i class="iconfont icon-collection-fill"></i>&nbsp;收藏
          </a>
        </li>
        <li class="fl">
          <a class="digg_link" data-id="82651" data-mid="1" data-type="up" href="javascript:;">
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
              {{ title }}&nbsp;
              <small class="text-ep">第{{ episode }}集</small>
            </h3>
          </div>
          <div class="video_desc">
            <div class="type">
              9.0分&nbsp;/&nbsp;
              <a href="/">{{ type }}</a>&nbsp;/&nbsp;
              <a href="/">{{ reigon }}</a>
            </div>
          </div>
        </div>
        <div class="video_playlist" id="playlist">
          <ul class="panel-content__list">
            <li class="col-xs-2" v-for="(media, index) in series" :key="media.id">
              <a
                href="javascript:void(0)"
                :class="['btn-episode', episode == index + 1 && 'active']"
                v-on:click="clickEpisode(media, index + 1)"
              >{{ index + 1 }}</a>
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
  props: ['title', 'movie_id', 'type', 'reigon', 'currentSeries'],

  created() {
    // var routeParams = window.location.search
    //   TODO: 获取当前播放集数，默认播放第一集
    this.episode = Number(this.currentSeries) + 1
    this.fetchData()
  },

  methods: {
    clickEpisode(item, index) {
      this.episode = index
      this.source = item.url
    },
    fetchData() {
      const that = this
      // 获取电视剧集数，设置当前播放集数
      window.axios
        .get(
          `https://neihandianying.com/api/movie/${that.movie_id}/series`
        )
        .then(function(response) {
          if (response && response.data) {
            that.series = response.data
            that.source = that.series[that.currentSeries].url
          }
        })
        .catch(e => {})
    }
  },

  data() {
    return {
      episode: 1,
      source: null,
      series: []
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
