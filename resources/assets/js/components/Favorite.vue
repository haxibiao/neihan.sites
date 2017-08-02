<template>
<span　v-if="favorited" class="iconfont icon-aixin" style="font-size: 2.5em;"></span>
<span v-else class="iconfont icon-aixin1" style="font-size: 2.5em;" @click="favorite"></span>
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
  		api_url += '?api_token=' + window.api_token;
  		return api_url;
  	},
  	favorite: function() {
  		var vm = this;
  		this.$http.post(this.get_api_url()).then(function(response) {
  			vm.favorited  = true;
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