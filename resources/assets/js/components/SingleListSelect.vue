<template>
<div class="row top10">
	<div v-if="!lists">
		您还有没有创建, 先去左边创建...
	</div>
	<div v-else v-for="(data, key) in lists" :class="data.col">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="pull-right">
					<button type="button" class="btn btn-success"　@click="addToBody(key)">添加到正文</button>
				</div>
				<h3 class="panel-title" style="line-height: 30px">{{ data.title }}</h3>
			</div>
			<div class="panel-body">
				<div :class="'col-xs-6 ' + get_items_col(data)" v-for="item in data.items">
					<a :href="getUrl(item.id)" :title="item.title"><img :src="item.image_url" class="img img-responsive"></a>
					<p class="strip_title">
						{{ item.title }}
					</p>
				</div>
			</div>
      <div class="panel-footer">
        <button type="button" class="btn btn-danger"　@click="deleteCollection(key)">删除</button>
      </div>
		</div>
	</div>
</div>
</template>

<script>
export default {

  name: 'SingleListSelect',

  props: ['articleId'],

  mounted: function() {
  	this.loadData();
  },

  computed: {
  },

  methods: {
    get_items_col: function(data) {
      if(data.items.length >= 4) {
        return 'col-sm-4 col-md-3';
      }
      if(data.items.length == 3) {
        return 'col-sm-4';
      }
      return '';
    },
  	getUrl: function(id) {
  		return '/article/' + id;
  	},
  	loadData: function() {
  		var vm = this;
  		this.$http.get('/api/article/' + this.articleId + '/lists').then(function(response){
  			vm.lists = response.data;
  			console.log(response.data);
  		});
  	},
  	addToBody: function(key) {
		//插入编辑器
      $('.editable').summernote('pasteHTML', '<single-list article-id="'+this.articleId+'" data-key="'+ key +'"/><h1 class="box-related">关联已插入这里!!!</h1>');
  	},
    deleteCollection: function(key) {
      var vm = this;
      this.$http.get('/api/article/' + this.articleId + '/del-' + key).then(function(response){
        vm.lists.splice(key,1);
      });
    }
  },

  data () {
    return {
    	lists: null
    };
  }
};
</script>

<style lang="css" scoped>
</style>