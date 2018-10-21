<template>
        <div @click="CloseModule">取消</div>
</template>

<script>
export default {

  name: 'ClosePage',

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

</style>