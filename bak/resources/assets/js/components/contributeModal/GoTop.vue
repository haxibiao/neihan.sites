<template>
	<ul>
        <li class="toup_rocket" data-container="body" data-title="回到顶部" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a href="javascript:;" class="function_button" @click="goTop">
                <i class="iconfont icon-xiangshangjiantou">
                </i>
            </a>
        </li>
    </ul>
</template>

<script>
export default {

  name: 'GoTop',

  mounted() {
		this.listen();
		var that = this;
	  $(window).on("scroll",function () {
	     that.listen();
	  });
  },

  methods: {
    goTop() {
      $("body,html").animate({"scrollTop": 0 }, 1000);
    },
  	listen() {
  		if($(window).scrollTop()>1000) {
  		    $(".toup_rocket").fadeIn(300);
  		}else {
  		    $(".toup_rocket").fadeOut(300);
  		}
  	}
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>