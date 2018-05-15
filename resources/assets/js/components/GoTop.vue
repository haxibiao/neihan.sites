<template>
	<ul>
		<li id="goTop" data-placement="left" data-toggle="tooltip" data-container="body" data-title="回到頂部">
	  	<a class="function_button" @click="goTop"><i class="iconfont icon-xiangshangjiantou"></i></a>
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
  		    $("#goTop").fadeIn(300);
  		}else {
  		    $("#goTop").fadeOut(300);
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