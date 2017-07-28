<template>
<div v-if="!data">
	Loading ...
</div>
<div v-else :class="data.col">
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{{ data.title }}</h3>
	</div>
	<div class="panel-body">
		<div class="col-xs-6 col-sm-4 col-md-3" v-for="item in data.items">
			<a :href="getUrl(item.id)"><img :src="item.image_url" class="img img-responsive"></a>
			<p class="strip_title">
				{{ item.title }}
			</p>
		</div>
	</div>
</div>
</div>
</template>

<script>
export default {

  name: 'SingleList',

  props: ['articleId','dataKey'],

  mounted: function() {
  	var vm = this;
  	this.$http.get('/api/article/' + this.articleId + '/' + this.dataKey)
  		.then(function(response) {
  			vm.data = response.data;
  		});
  },

  methods: {
  	getUrl: function(id) {
  		return '/article/' + id;
  	}
  },

  data () {
    return {
    	data: null,
    };
  }
};
</script>

<style lang="css" scoped>
</style>