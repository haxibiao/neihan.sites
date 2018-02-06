<template>
	     <form class="navbar-form navbar-left" role="search" action="/search" method="get" id="searchForm">
	        <div class="form-group">
	            <div class="search_wrp search_box">
	                <input class="form-control" placeholder="搜索" type="text" name="q"/>
	                <i class="iconfont icon-sousuo" @click="search">
	                </i>
	                <div class="hot_search_wrp hidden-xs">
	                    <div class="hot_search">
	                    	<hot-search></hot-search>
	                    	<div class="split_line"></div>
	                    	<recently-search hide-title="true"></recently-search>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>
</template>

<script>
export default {

  name: 'SearchBox',

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
// 搜索框
.navbar-form {
    border-color: transparent;
    border: 0;
    .form-group {
        .search_wrp {
            display: inline-block;
            margin: 0 auto;
            .form-control {
                width: 170px;
                height: 36px;
                transition: all 0.3s ease-in-out;
                &:focus {
                    width: 190px;
                    &+.icon-sousuo {
                        color: #f0f0f0;
                        background-color: #646464;
                    }
                }
            }
            .icon-sousuo {
                width: 28px;
                height: 28px;
                line-height: 28px;
            }
            // 搜索框下拉热门搜索
            .hot_search_wrp {
                margin-top: 9px;
                width: 190px;
                left: 0;
                top: 100%;
                visibility: hidden;
                opacity: 0;
                border-radius: 4px;
                z-index: 1030;
                &,
                &::before {
                    position: absolute;
                    background-color: #fff;
                    box-shadow: 0 0 8px rgba(0, 0, 0, .2);
                }
                &::before {
                    content: "";
                    width: 10px;
                    height: 10px;
                    left: 27px;
                    transform: rotate(45deg);
                    top: -5px;
                    z-index: -1;
                }
                .hot_search {
                    padding: 15px 15px 10px;
                    border-bottom: 1px solid #f0f0f0;
                    background-color: #FFF;
                    border-radius: 4px;
                    .search_trending {
                    	text-align: left;
                    }
                    .split_line {
                        margin: 15px -12px 7px;
                    }
                    .search_recent {
                    	text-align: left;
                        margin: 0 -10px;
                    }
                }
            }
        }
    }
}
</style>