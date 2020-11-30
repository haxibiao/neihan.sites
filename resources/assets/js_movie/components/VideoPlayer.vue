<template>
  <div class="responsive-video">
    <div id="dplayer"></div>
  </div>
</template>

<script>
import DPlayer from "dplayer";

export default {
  props: ["source"],
  mounted() {
    console.log("DPlayer 加载成功 " + this.source);
    this.player = new DPlayer({
      container: document.getElementById("dplayer"),
      preload: true,
      autoplay: true,
      screenshot: true,
      video: {
        url: this.source,
        type: "hls",
      },
      pluginOptions: {
        hls: {},
      },
      //   subtitle: {
      //     url: 'webvtt.vtt'
      //   },
      //   danmaku: {
      //     id: 'demo',
      //     api: 'https://api.prprpr.me/dplayer/'
      //   }
    });
  },
  updated() {
    console.log("DPlayer 更新 " + this.source);
  },
  beforeDestroy() {
    if (this.player) {
      this.player.destroy();
    }
  },
  watch: {
    source(newV, oldV) {
      console.log("dplay source newV = " + newV);
      if (this.player) {
        this.player.switchVideo({
          url: newV,
        });
        this.player.play();
      }
    },
  },
  data() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
.responsive-video {
  position: relative;
  display: block;
  overflow: hidden;
  padding: 0;
  height: 0;
  padding-bottom: 56.25%;
  background-color: #000;
}
#dplayer {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
</style>
