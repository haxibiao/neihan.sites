<template>
<div>
	<div class="row top10">	
		<div :class="data.col">
			<div class="panel panel-default">
				<div class="panel-heading">
					<input type="text" class="form-control" placeholder="集合标题,例如: 相关草药 / 相关英雄..." v-model="data.title">
				</div>
				<div class="panel-body">
					<div v-if="!data.items">
						下方搜索文章，　点 ＋ 加入文章...
					</div>
					<div v-else class="col-xs-6 col-sm-4 col-md-3" v-for="item in data.items">
						<a :href="getUrl(item.id)"><img :src="item.image_url" class="img img-responsive"></a>
						<p class="strip_title">
							{{ item.title }}
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="top10">
		<button type="button" class="btn btn-default"　@click="setHalfScreen">半屏幕宽</button> 
		<button type="button" class="btn btn-danger" @click="setFullScreen">全屏幕宽</button>
		<button type="button" class="btn btn-primary" @click="saveCollection">保存集合</button>
	</div>

	<div class="panel panel-primary top10">
		<div class="panel-heading">
			<div class="input-group">
		        <input type="text" class="form-control" placeholder="搜索 相关草药 / 相关英雄 标题..." v-model="query">
		        <span class="input-group-btn">
		          <button class="btn btn-default" type="button" @click="loadData">搜索</button>
		        </span>
		      </div>
		</div>
		<div class="panel-body">
			<div v-if="!articles">
				未找到文章, 换个关键词搜索看...
			</div>
			<div v-else>
				<div class="col-xs-6 col-sm-4 col-md-3 top10" v-for="item in articles">
					<a :href="getUrl(item.id)"><img :src="item.image_url" class="img img-responsive"　style='max-height:120px'></a>
					<p class="strip_title">
						{{ item.title }}
					</p>
					<button type="button" class="btn btn-sm btn-success" @click="(event) => {addItem(event,item)}">＋加入文章</button>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
export default {

  name: 'SingleListCreate',

  props: ['articleId'],

  mounted: function() {
  	this.loadData();
  },

  methods: {
  	loadData: function() {
  		var api_url  = '/api/articles';
  		if(this.query) {
  			api_url = api_url + '?query=' + this.query;
  		}
  		var vm = this;
  		this.$http.get(api_url).then(function(response){
  			vm.articles = response.data.data;
  		});
  	},
  	setHalfScreen: function() {
  		this.data.col='col-md-6';
  	},
  	setFullScreen: function() {
  		this.data.col='col-md-12';
  	},
  	getUrl: function(id) {
  		return '/article/' + id;
  	},
  	addItem: function(event, item) {
  		$(event.target).parent().hide();
  		if(!this.data.aids) {
  			this.data.aids = new Array();
  		}
  		if(!this.data.items) {
  			this.data.items = new Array();
  		}
  		this.data.aids = this.data.aids.concat(item.id);
  		this.data.items = this.data.items.concat({
  			id: item.id,
  			title: item.title,
  			image_url: item.image_url,
  		});
  	},
  	saveCollection: function() {
  		if(!(this.data.title && this.data.aids.length)) {
  			return ;
  		}
  		var vm = this;
  		var postBody = {
  			col: this.data.col,
  			title: this.data.title,
  			aids: this.data.aids
  		};
  		console.log(postBody);
  		this.$http.post('/api/article/' + this.articleId + '/json', postBody).then(function(response) {
  			console.log(response.data);
  			$('#list_select_link')[0].click();
  			window.app.$refs.list_select.loadData();
  		});
  	}
  },

  data () {
    return {
    	data: {
    		col: 'col-md-6',
    		title: null,
    		aids: null,
    		items: null
    	},
    	query: null,
    	articles: null,
    };
  }
};
</script>

<style lang="css" scoped>
</style>