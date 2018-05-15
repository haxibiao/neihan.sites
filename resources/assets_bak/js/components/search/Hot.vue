<template>
	<div class="search_trending">
        <div class="litter_title">
            热门搜索
            <a href="javascript:;" @click="change" class="rotation">
                <i class="iconfont icon-shuaxin" ref="fresh">
                </i>
                换一批
            </a>
        </div>
        <ul class="search_trending_tag_wrap">
            <li v-for="q in queries">
                <a :href="'/new_search?q='+q.full" :title="q.full" target="_blank" class="search_lab">
                    {{ q.short }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'Hot',

  mounted(){
  	 this.fetchData();
  },

  methods:{
  	 change(){
  	 	this.changes++;
  	 	this.fetchData();
  	 },
  	 fetchData(){
  	 	$(this.$refs.fresh).css('transform',`rotate(${360*this.changes}deg)`);

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

<style lang="scss" scoped>
.search_trending {
    .litter_title {
        line-height: 2;
        margin-bottom: 10px;
    }
    .search_trending_tag_wrap {
        li {
            margin-right: 10px;
            display: inline-block;
            line-height: 28px;
        }
    }
}
</style>