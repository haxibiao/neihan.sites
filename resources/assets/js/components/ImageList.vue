<template>
  <div class="row image-list">
    <div v-for="image in images" class="col-xs-4"><img :src="image.path" alt="" simditor></div>   
  </div>
</template>

<script>
export default {
  name: "ImageList",

  props: ["images"],

  data() {
    return {
    
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
