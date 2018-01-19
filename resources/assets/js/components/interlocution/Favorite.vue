<template>
    <i :class="['iconfont',favorited?'icon-shoucang1':'icon-shoucang']" @click="toggle"></i>
</template>

<script>
export default {

  name: 'Favorite',

  props:['questionId'],

  mounted(){
      this.get();
  },

  methods:{
  	api(){
  		return window.tokenize('/api/favorite/' + this.questionId + '/questions');
  	},
    toggle(){
       	  var vm=this;
       	  window.axios.post(this.api()).then(function(response){
       	  	   vm.favorited=response.data;
       	  });
       },
     get(){
          var vm=this;
          window.axios.get(this.api()).then(function(response){
          	 vm.favorited=response.data;
          });
     }
  },

  data () {
    return {
        favorited:false
    }
  }
}
</script>

<style lang="css" scoped>
</style>