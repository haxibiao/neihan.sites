<template>
<span　v-if="favorited" class="iconfont icon-xin" style="font-size: 1.5em;"　@click="delfav"></span>
<span v-else class="iconfont icon-03xihuan" style="font-size: 1.5em;" @click="favorite"></span>
</template>

<script>
export default {

  name: 'Favorite',

  props: ['id', 'type'],

  mounted() {
  	var vm = this;
  	this.$http.get(this.get_api_url()).then(function(response){
  		vm.favorited = response.data
  	});
  },

  methods: {
  	get_api_url: function() {
  		var api_url = '/api/favorite/' + this.id + '/' + this.type;
  		api_url = window.tokenize(api_url);
  		return api_url;
  	},
  	favorite: function() {
  		var vm = this;
  		this.$http.post(this.get_api_url()).then(function(response) {
  			vm.favorited  = true;
  		});
  	},
  	delfav: function() {
  		var vm = this;
  		this.$http.delete(this.get_api_url()).then(function(response) {
  			vm.favorited  = false;
  		});  		
  	}
  },

  data () {
    return {
    	favorited: false,
    };
  }
};
</script>

<style lang="css" scoped>
</style>