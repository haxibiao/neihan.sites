<template>
        <div @click="CloseModule" class="module-share-btn">
          <span class="module-share-cancel">取消</span>
        </div>
</template>

<script>
export default {

  name: 'CloseShare',

  props: [],

  mounted() {

  },

  methods: {
    CloseModule() {
      $("#pageLayout").css("display","none");
      $(".share-module ").animate({
      	// "display":"block",
      	// "transform":"translate3d(0,-195px,0)"
      	bottom:"-195px"
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
  .module-share-btn{
        padding: 15px 20px;
       .module-share-cancel{
          font-size: 16px;
          line-height: 43px;
          display: block;
          height: 43px;
          padding: 0 20px;
          color: #fff;
          border-radius: 7px;
          background: #625e6b;
       }
   }
</style>