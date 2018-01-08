<template>
	<div class="row image-list">
		<div v-for="image in images" class="col-xs-4"><img :src="image.path" alt="" simditor></div>		
	</div>
</template>

<script>
export default {

  name: 'ImageList',

  mounted() {
    this.fetchData();
  },

  methods: {
    fetchData() {
      var _this = this;
      window.axios.get('/api/image').then(function(response){
        _this.images = response.data.data;
      });
    }
  },

  data () {
    return {
      images: []
    }
  }
}
</script>

<style lang="scss" scoped>
.image-list {
    img {
        display: block;
        max-width: 100%;
        padding: 10px;  
    }
  } 
</style>