<template>
	<a @click="shareModule">
        <i class="iconfont icon-fenxiang"></i>                          
        <p>分享到</p>
    </a>
</template>

<script>
export default {

  name: 'ShareModule',

  props: [],

  mounted() {

  },

  methods: {
    shareModule() {
      $("#pageLayout").css("display","block");
      $(".share-module ").animate({
      	// "display":"block",
      	// "transform":"translate3d(0,-195px,0)"
      	bottom:"0px"
      })
    },
  },

  data () {
    return {
    	
    }
  }
}
</script>

<style lang="scss" scoped>

</style>