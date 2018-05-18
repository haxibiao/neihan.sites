<template>
	<div class="form-group">
	    <label for="article_images">选配图</label>
	    <div class="row" id="article_images">
            <div class="col-xs-3"　v-for="img in imgs">
                <p class="text-center">
                    <img :src="img" alt="" class="img img-responsive">                    
                    <label class="radio text-center">
                      <input type="radio" name="primary_image" :value="img">
                      设为主要图
                    </label>                    
                </p>
            </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'ImageSelect',

  methods: {
  	updateNewImgs() {
  		this.imgs = window.new_imgs ? window.new_imgs : [];
  	}
  },

  created() {
  	var _this = this;
  	window.$bus.$on('editor_value_changed', function(){
  		_this.updateNewImgs();
  	});
  },

  data () {
    return {
    	imgs: []
    }
  }
}
</script>

<style lang="css" scoped>
</style>