<template>
<div class="recommend-follower">
  <div class="plate-title">
    推荐作者
    <a target="_blank" href="javascript:;" class="right" @click="fetchData"><i class="iconfont icon-shuaxin" ref="fresh"></i>换一批</a>
  </div>
  <ul>
    <li v-for="(user,index) in users"  class="user-info">
      <a target="_blank" :href="'/user/'+user.id" class="avatar"><img :src="user.avatar" alt=""></a>
      
      <a v-if="!user.is_followed" href="javascript:;" class="follow" @click="toggleFollow(user,index)">＋ 关注</a>

      <a v-else href="javascript:;" class="toggle-followed" @click="toggleFollow(user,index)">
        <i class="gougou iconfont icon-weibiaoti12"></i>
        <i class="chacha iconfont icon-cha"></i>
      </a>
      
      <a target="_blank" :href="'/user/'+user.id" class="title">{{ user.name }}</a>
      <p class="info">写了{{ user.count_words }}字 · {{ user.count_likes }}喜欢</p>
    </li>
  </ul>
  <a href="/user" target="_blank" class="btn-base btn-gray">
      查看全部<i class="iconfont icon-youbian"></i>
  </a>
</div>
</template>

<script>
export default {

  name: 'RecommendAuthors',

  mounted() {
  	this.fetchData();
  },

  methods: {
  	fetchData() {
  		var vm = this;
      this.counter ++;
      $(this.$refs.fresh).css('transform',`rotate(${360*this.counter}deg)`);
		  $('.recommend_author ul').fadeOut();
		  var api = '/api/user/recommend';
  		window.axios.get(api).then(function(response){
  			vm.users = response.data;
  			$('.recommend_author ul').fadeIn();
  		});
  	},
  	toggleFollow(user,index) {
      var vm = this;
      var api_url = window.tokenize('/api/follow/'+ user.id + '/users');
      window.axios.post(api_url).then(function(response){
          user.is_followed = response.data;
          vm.$set(vm.users, index, user)
      });
  	}
  },

  data () {
    return {
    	users:[],
      counter:1
    }
  }
}
</script>

<style lang="scss">
</style>