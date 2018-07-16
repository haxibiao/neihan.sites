<template>
  <div class="form-group">
      <label for="article_images">选配图</label>
      <div class="row" id="article_images">
            <div class="col-xs-6 col-sm-6 col-lg-3"　v-for="img in imgs">
                <div class="text-center img_item">
                  <div class="img_box">
                    <img :src="img" alt="" class="img img-responsive">
                  </div>                 
                    <label class="radio text-center">
                      <input type="radio" name="primary_image" :value="img">
                      设为主图
                    </label>                    
                </div>
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

<style lang="scss">
#article_images {
  .img_item {
    width: 100%;
    height: 200px;
    background: #000;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    margin-bottom: 15px;
    .img_box {
      height: 175px;
      overflow: hidden;
      img {
        max-width: 100%;
        height: auto;
      }
    }
    .radio {
      color: #fff;
    }
  }
}
</style>
