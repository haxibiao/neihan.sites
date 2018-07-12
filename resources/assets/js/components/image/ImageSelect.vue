<template>
  <div class="form-group">
      <label for="article_images">选配图</label>
      <div class="row" id="article_images">
            <div class="col-xs-3"　v-for="img in imgs">
                <p class="text-center">
                    <img :src="img" alt="" class="img img-responsive">                    
                    <label class="radio text-center">
                      <input type="radio" name="primary_image" :value="img">
                      设为主图
                    </label>                    
                </p>
            </div>
      </div>
  </div>
</template>

<script>
export default {
  name: "ImageSelect",

  props: ["imgUrls"],

  methods: {
    updateNewImgs() {
      let imgUrls = this.imgUrls ? this.imgUrls : [];
      this.imgs = window.new_imgs ? imgUrls.concat(window.new_imgs) : imgUrls;
    }
  },

  created() {
    var _this = this;
    window.$bus.$on("imageuploaded", function() {
      _this.updateNewImgs();
    });
  },

  mounted() {
    let imgUrls = this.imgUrls ? this.imgUrls : [];
    console.log(imgUrls);
    this.imgs = imgUrls;
  },

  data() {
    return {
      imgs: []
    };
  }
};
</script>

<style lang="css">
#article_images {
  img {
    max-width: 200px;
  }
}
</style>
