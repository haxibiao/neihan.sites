<template>
  <div class="row img-list">
    <div v-for="image in images" class="img-item">
        <img :src="image.path" alt="" simditor>
    </div>   
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
.img-list {
  margin: 12px 0 8px;
}
</style>
