<template>
<span v-if="!liked" class="icon iconfont icon-dianzan3" style="font-size: 1.5em;" @click="like"><span style="font-size: 0.5em;">点赞人数: {{ likes }}</span></span>
<span v-else class="icon iconfont icon-dianzan" style="font-size: 1.5em;" @click="unlike"><span style="font-size: 0.5em;">点赞人数: {{ likes }}</span></span>
</template>

<script>
export default {

  name: 'Like',

  props: ['id', 'type'],

  mounted() {
  	var vm = this;
  	this.$http.get(this.get_api_url()).then(function(response){
  		vm.liked = response.data.is_liked;
  		vm.likes = response.data.likes;
  	});
  },

  methods: {
  	get_api_url: function() {
  		var api_url = '/api/like/' + this.id + '/' + this.type;
  		api_url = window.tokenize(api_url);
  		return api_url;
  	},
  	like: function() {
  		var vm = this;
  		this.$http.post(this.get_api_url()).then(function(response) {
  			vm.liked = response.data.is_liked;
  			vm.likes = response.data.likes;
  		});
  	},
  	unlike: function() {
  		var vm = this;
  		this.$http.delete(this.get_api_url()).then(function(response) {
  			vm.liked = response.data.is_liked;
  			vm.likes = response.data.likes;
  		});  		
  	}
  },

  data () {
    return {
    	liked: false,
    	likes:0,
    };
  }
};
</script>

<style lang="css" scoped>
</style>