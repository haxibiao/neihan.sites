<template>
  <div class="responsive-video">
    <video
      id="video-player"
      muted
      class="video-js"
      autoplay
      controls
      preload="auto"
      crossorigin="anonymous"
    >
      <source :src="source" type="application/x-mpegURL" />
      <p class="no-vjs">
        To view this video please enable JavaScript,
        <br />and consider upgrading to a web browser that
        <a href="https://videojs.com/html5-video-support/" target="_blank"
          >supports HTML5 video</a
        >
      </p>
    </video>
  </div>
</template>

<script>
import videojs from "video.js/dist/video.js";
export default {
  props: ["source"],
  mounted() {
    this.player = videojs("video-player");
  },
  updated() {
    console.log("videojs player 更新 " + this.source);
  },
  watch: {
    source(newValue, oldValue) {
      console.log("videojs source newValue = " + newValue);
      this.player.play();
    },
  },
  data() {
    return {};
  },
};
</script>

<style src="video.js/dist/video-js.css"></style>
<style>
.responsive-video .video-js .vjs-big-play-button {
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
}
</style>
<style lang="scss" scoped>
.responsive-video {
  position: relative;
  display: block;
  overflow: hidden;
  padding: 0;
  height: 0;
  padding-bottom: 56.25%;
  background-color: #000;
  .video-js {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
  }
}
</style>
