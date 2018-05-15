<template>
	<div class="search-recent" v-show="histories.length>0">
		<div class="plate-title" v-if="!hideTitle">
		  最近搜索
		  <a target="_blank" href="javascript:;" class="right" @click="empty">清空</a>
		</div>
		<ul class="search-recent-item-wrap" v-if="histories.length">
			<li v-for="history in histories">
				<a :href="'/search?q='+history.name" target="_blank">
					<i class="iconfont icon-unie646"></i>
					<span>{{ history.query }}</span>
					<i class="iconfont icon-cha" :data-id="history.id" @click="deleteHistory"></i>
				</a>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Recently',

  props:['hideTitle'],

  mounted() {
  	this.fetchData();
  },

  methods: {
  	empty() {
  		var _this = this;
  		axios.delete(tokenize('/api/search/clear-querylogs')).then(function(response){
  			_this.histories = [];
  		},function(error){
  			_this.histories = [];
  		})
  	},
  	fetchData() {
  		var _this = this;
  		axios.get(tokenize('/api/search/latest-querylogs')).then(function(response){
  			_this.histories = response.data
  		})
  	},
  	deleteHistory(event) {
  		event.preventDefault();
  		var history_id = parseInt($(event.target).attr('data-id'));
  		var _this = this;
  		axios.delete(tokenize('/api/search/remove-querylog-'+history_id)).then(function(response){
  			_this.histories = _this.histories.filter(ele => ele.id !== history_id);
  		},function(error){
  			_this.histories = _this.histories.filter(ele => ele.id !== history_id);
  		});
  	}
  },

  data () {
    return {
    	histories:[],
    }
  }
}
</script>

<style lang="scss">
	.search-recent {
		padding: 15px 15px 10px;
		.search-recent-item-wrap {
			li {
				line-height: 20px;
				a {
					display: block;
				    height: 40px;
				    line-height: 20px;
				    padding: 10px 15px 10px 0;
				    font-size: 14px;
				    color: #252525;
				    position: relative;
					i.icon-unie646 {
						float: left;
						margin-right: 10px;
						font-size: 18px;
						color: #787878;
					}
					i.icon-cha {
						position: absolute;
					    right: 15px;
					    top: 11px;
					    font-size: 14px;
					    color: #a0a0a0;
					    display: none;
					    &:hover {
					    	color: #515151;
					    }
					}
					span {
						vertical-align: middle;
					    overflow: hidden;
					    text-overflow: ellipsis;
					    white-space: nowrap;
					    display: block;
					    padding-right: 30px;
					    text-align: left;
					}
					&:hover {
						background-color: #f0f0f0;
						border-radius: 4px;
						i.icon-cha {
							 display: block;
						}
					}
				}
			}
		}
	}
</style>