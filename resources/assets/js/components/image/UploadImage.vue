<template>
  <div class="upload-image">
    <input type="text" class="form-control" :id="textValue" :name="textName" :value="image_url" />
    <img :src="dataImage" :id="imageValue" :alt="textName" />
    <input type="file" @change="loadImg" class="file-select" />
  </div>
</template>

<script>
export default {
  name: "UploadImge",

  props: ["textValue", "imageValue", "textName", "dataImage"],

  mounted() {},

  methods: {
    loadImg(event) {
      //获取文件
      var input = event.target;
      var _this = this;
      if (input.files && input.files[0]) {
        var fileObj = input.files[0];
        console.log("参数", fileObj);
        var reader = new FileReader();
        reader.onload = function(event) {
          $("#" + _this.imageValue).attr("src", event.target.result);
          // _this.setState({ logo: event.target.result });
          // $("#"+_this.textValue).attr("value", event.target.result);
        };
        reader.readAsDataURL(fileObj);
        this._upload(fileObj);
      }
    },
    _upload(fileObj) {
      var api = window.tokenize("/api/image");
      var _this = this;
      let formdata = new FormData();
      formdata.append("from", "post");
      formdata.append("photo", fileObj);
      let config = {
        headers: {
          "Content-Type": "multipart/form-data"
        }
      };
      window.axios.post(api, formdata, config).then(function(res) {
        var image = res.data;
        _this.image_url = image.url;
        console.log("inmag", image.url);
      });
    }
  },

  data() {
    return {
      image_url: this.dataImage
    };
  }
};
</script>
<style lang="scss">
.upload-image {
  display: flex;
  align-items: center;
  width: auto !important;
  img {
    display: block;
    width: 60px;
    height: 60px;
    margin-left: 20px;
  }
  .form-control {
    width: 201px !important;
  }
  .file-select {
    opacity: 0;
    width: 60px;
    height: 60px;
    position: relative;
    right: 60px;
  }
}
</style>
