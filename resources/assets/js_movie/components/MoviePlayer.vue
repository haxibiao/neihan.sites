<template>
  <div class="movie-player row">
    <div class="col-lg-9 player-main">
      <div class="player-container player-fixed">
        <!-- <a class="player-fixed-off" href="javascript:;" style="display: none;"><i class="iconfont icon-close"></i></a> -->
        <div class="embed-responsive">
          <div class="embed-responsive video-player">
            <div class="fluid_video_wrapper">
              <video-player
                url="http://cdn-youku-com.diudie.com/series/5/index.m3u8"
              />
            </div>
          </div>
        </div>
      </div>
      <ul class="player__operate">
        <li class="fl">
          <a
            class="favorite"
            href="javascript:void(0);"
            data-type="2"
            data-mid="1"
            data-id="82651"
          >
            <i class="iconfont icon-collection-fill"></i>&nbsp;收藏
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
      <div class="side-pannel">
        <div class="movie-info col-pd">
          <div class="video_desc">
            <h3 class="title">
              {{ title }}
              <small class="text-ep">第01集</small>
            </h3>
          </div>
          <div class="video_desc">
            <div class="type">
              9.0分
              <a href="/home">{{ type }}</a>
              <a href="/home">{{ region_name }}</a>
            </div>
          </div>
        </div>
        <div class="video_playlist" id="playlist">
          <ul class="pannel-content__list">
            <li
              class="col-xs-2"
              v-for="(v, i) in parseInt(count_series)"
              :key="i"
            >
              <a
                :class="['btn-episode', i == 0 && 'active']"
                href="javascript:void(0)"
                @click="load_series(i)"
                >{{ i + 1 }}</a
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
  props: ["title", "type", "region_name", "count_series"],
  methods: {
    load_series(i) {
      //加载剧集
      console.log("加载剧集");
    },
  },
};
</script>

<style lang="scss" scoped>
.movie-player {
  position: relative;
  background-color: #1c1d30;
  //   background-color: #0f0f1e;
}
.player-main {
  padding: 0 !important;
}
.embed-responsive {
  padding-bottom: 56.25%;
}
.fluid_video_wrapper {
  width: 100%;
  height: 100%;
}
.player__operate {
  background: #0f0f1e;
  display: flex;
  padding: 15px 0 15px 5px;
  & > li {
    width: 90px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-block;
    white-space: nowrap;
    position: relative;
    font-size: 1rem;
    &:not(:last-child) {
      margin-right: 8px;
    }
    a {
      color: #999;
    }
    i {
      font-size: 1.2rem;
    }
  }
}
.player-side {
  padding: 0;
}
.side-pannel {
  padding: 10px;
}
.video_desc {
  position: relative;
  margin-bottom: 10px;
  .title {
    display: inline-block;
    margin: 0;
    padding-right: 10px;
    line-height: 30px;
    font-size: 1.5rem;
    color: #f0f0f0;
    .text-ep {
      color: #ff5c38;
    }
  }
  .type {
    font-size: 0.9rem;
    color: #999;
  }
}
.video_playlist {
  overflow: auto;
  max-height: 160px;
  @media (min-width: 992px) {
    max-height: 400px;
  }
  &::-webkit-scrollbar {
    width: 4px;
    background-color: #0f0f1e;
  }
  &::-webkit-scrollbar-thumb {
    background-color: #434145;
  }
}
.pannel-content__list {
  display: flex;
  flex-wrap: wrap;
  [class*="col-"] {
    padding: 10px 5px;
  }
  @media (max-width: 991px) {
    [class*="col-"] {
      padding: 10px;
    }
  }
}
.btn-episode {
  display: block;
  padding: 8px 0;
  border-radius: 3px;
  width: 100%;
  font-size: 0.8rem;
  text-align: center;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #ddd;
  &.active {
    background-color: #0f0f1e;
    color: #ff5c38;
  }
  &:hover {
    background-color: #0f0f1e;
    color: #ff5c38;
  }
}
</style>
