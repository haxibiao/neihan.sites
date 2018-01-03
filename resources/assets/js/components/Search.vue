<template>
	     <form class="navbar-form navbar-left" role="search" action="/search" method="get" id="searchForm">
	        <div class="form-group">
	            <div class="search_wrp search_box">
	                <input class="form-control" placeholder="搜索" type="text" name="q"/>
	                <i class="iconfont icon-sousuo" @click="search">
	                </i>
	                <div class="hot_search_wrp hidden-xs">
	                    <div class="hot_search">
	                        <div class="litter_title">
	                            热门搜索
	                            <a class="more" href="javascript:;" target="_blank" @click="change">
	                                <i class="iconfont icon-shuaxin">
	                                </i>
	                                换一批
	                            </a>
	                        </div>
	                        <ul>
	                            <li v-for="q in queries">
	                                <a :href="'/search?q='+q.full" :title="q.full" target="_blank">
	                                    {{ q.short }}
	                                </a>
	                            </li>
	                        </ul>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
</template>

<script>
export default {

  name: 'Search',

  mounted(){
  	 this.fetchData();
  },

  methods:{
  	 search(){
  	 	$('#searchForm').submit();
  	 },
  	 change(){
  	 	this.changes++;
  	 	this.fetchData();
  	 },
  	 fetchData(){
  	 	var api='/api/search/hot-queries?page='+this.changes;
  	 	var vm=this;
  	 	window.axios.get(api).then(function(response){
  	 		if(response.data.length){
  	 			vm.queries=response.data;
  	 		}
  	 	});
  	 }
  },

  data () {
    return {
         changes:1,
         queries:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>