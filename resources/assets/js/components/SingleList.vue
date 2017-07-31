<template>
<div v-if="!weixin">
	<div v-if="!data">
		Loading ...
	</div>
	<div v-else :class="data.col">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{{ data.title }}</h3>
		</div>
		<div class="panel-body">
			<div :class="'col-xs-6 ' + get_items_col" v-for="item in data.items">
				<a :href="getUrl(item.id)"><img :src="item.image_url" class="img img-responsive"></a>
				<p class="strip_title">
					{{ item.title }}
				</p>
			</div>
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

  computed: {
  	weixin: function() {
  		if(this.getParameterByName('weixin') == 1) {
  			return true;
  		}
  		return false;
  	},
    get_items_col: function() {
      if(this.data.items.length >= 4) {
        return 'col-sm-4 col-md-3';
      }
      if(this.data.items.length == 3) {
        return 'col-sm-4';
      }
      return '';
    }
  },

  methods: {
  	getUrl: function(id) {
  		return '/article/' + id;
  	},
    getParameterByName: function(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
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