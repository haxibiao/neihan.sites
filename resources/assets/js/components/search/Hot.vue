<template>
	<div>
		<div class="hot-search">
			<div class="plate-title">
			  热门搜索
			  <a target="_blank" href="javascript:;" class="right" @click="change"><i class="iconfont icon-shuaxin" ref="fresh"></i>换一批</a>
			</div>
			<ul>
				<li v-for="query in queries">
					<a target="_blank" :href="'/search?q='+query.query" :title="query.query">{{ query.q }}</a>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Hot',

  mounted() {
		this.fetchData();
  },

  methods: {
	change() {
		this.page ++ ;
		this.fetchData();
	},
	fetchData() {
		if(this.lastPage && this.lastPage < this.page) {
			this.page = 1;
		}

		$(this.$refs.fresh).css('transform',`rotate(${360*this.page}deg)`);

		var api = '/api/search/hot-queries?page='+this.page;
		var _this = this;
		window.axios.get(api).then(function(response){
			_this.queries = response.data.data;
			_this.lastPage = response.data.last_page;
		});
	}
  },

  data () {
    return {
    	page: 1,
    	lastPage: null,
    	queries:[]
    }
  }
}
</script>

<style lang="scss">
.hot-search {
	padding: 15px 15px 10px;
	background-color: #FFF;
	ul {
		text-align: left;
		li {
			margin-right: 10px;
			display: inline-block;
			line-height: 28px;
			a {
				padding: 2px 6px;
				font-size: 12px;
				color: #717171;
				border: 1px solid #c4c4c4;
				border-radius: 3px;
				&:hover {
					color: #252525;
					border-color: #717171;
				}
			}
		}
	}
}
</style>