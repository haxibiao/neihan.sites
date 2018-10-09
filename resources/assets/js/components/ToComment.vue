<template>
      <a class="function_button" @click="goTop">
<!--         <i class="iconfont icon-xiangshangjiantou">          
        </i> -->
          <i class="iconfont icon-xinxi2"></i>
          <p>{{commentReplies}}</p>
      </a>
</template>

<script>
export default {

  name: 'ToComment',

  props: ['commentReplies'],

  mounted() {

  },

  methods: {
    goTop() {
      $("body,html").animate({"scrollTop": 716 }, 1000);
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