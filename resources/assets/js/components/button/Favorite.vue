<template>
<span　v-if="favorited" class="iconfont icon-03xihuan" style="font-size: 1.5em;"　@click="toggle"></span>
<span v-else class="iconfont icon-xin" style="font-size: 1.5em;" @click="toggle"></span>
</template>

<script>
export default {

  name: 'Favorite',

  props: ['id', 'type'],

  created() {
  	var _this = this;
  	window.axios.get(window.tokenize('/api/favorite/' + this.id + '/' + this.type)).then(function(response){
  		_this.favorited = response.data
  	});
  },

  methods: {
  	toggle: function() {
  		var _this = this;
  		window.axios.post(window.tokenize('/api/favorite/' + this.id + '/' + this.type))
        .then(function(response) {
  			_this.favorited  = response.data;
  		});
  	},
  },

  data () {
    return {
    	favorited: null,
    };
  }
};
</script>

<style lang="css" scoped>
</style>