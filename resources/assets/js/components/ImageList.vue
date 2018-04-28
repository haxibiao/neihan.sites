<template>
  <div class="row image-list">
    <div v-for="image in images_first" class="col-xs-4"><img :src="image.path" alt="" simditor></div>   
  </div>
</template>

<script>
export default {
  name: "ImageList",

  props: ["images"],

  mounted() {
    this.fetchData();
  },

  watch: {
    images() {
      this.images_first = this.images;
    }
  },

  methods: {
    fetchData() {
      var _this = this;
      window.axios.get("/api/image").then(function(response) {
        _this.images_first = response.data.data;
      });
    }
  },

  data() {
    return {
      images_first: []
    };
  }
};
</script>

<style lang="scss" scoped>
.image-list {
  margin-top: 12px;
  img {
    display: block;
    max-width: 100%;
    padding: 15px 0;
  }
}
</style>
