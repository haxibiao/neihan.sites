<template>
	<mt-loadmore :top-method="loadTop" :bottom-method="loadBottom" :bottom-all-loaded="allLoaded" ref="loadmore">
	  <ul>
	    <li v-for="item in list">{{ item.path }}</li>
	  </ul>
	</mt-loadmore>
</template>

<script>
export default {

  name: 'MyImageList',

  props: ['user_id'],

  mounted: function() {
  	this.loadMore();
  },

  methods: {
  	loadMore: function() {
  		var vm = this;
  		this.$http.get('/api/user/' + this.user_id + '/images?page='+this.currentPage)
  		.then(function(response) {
  			vm.list = vm.list.concat(response.data.data);
  			vm.currentPage ++;
  		});
  	},
  	loadTop() {
	  //...// 加载更多数据
	  this.$refs.loadmore.onTopLoaded();
	},
	loadBottom() {
	  //...// 加载更多数据
	  this.allLoaded = true;// 若数据已全部获取完毕
	  this.$refs.loadmore.onBottomLoaded();
	}
  },

  data () {
    return {
    	currentPage: 1,
    	allLoaded: false,
    	list: []
    };
  }
};
</script>

<style lang="css" scoped>
</style>