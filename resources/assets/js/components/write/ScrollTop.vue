<template>
  <div id="side-tool">
  	<ul>
  		<li id="goTop" data-placement="left" data-toggle="tooltip" data-container="body" data-title="回到文章頂部">
  	  	<a class="function_button" @click="goTop"><i class="iconfont icon-xiangshangjiantou"></i></a>
  	  </li>
  	</ul>
  </div>
</template>

<script>
export default {

  name: 'GoTop',

  props:['target'],

  mounted() {
		this.listen();
		var that = this;
	  $(this.target).on("scroll",function () {
	     that.listen();
	  });
  },

  methods: {
    goTop() {
      $(this.target).animate({"scrollTop": 0 }, 1000);
    },
  	listen() {
  		if($(this.target).scrollTop()>1000) {
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
#side-tool {
  z-index: 999;
}
</style>