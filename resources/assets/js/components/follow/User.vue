<template>
	<div v-if="!user" class="loading">
		正在加载....
	</div>
	<div v-else="user" class="users">
		<!-- 用户信息 -->
	  <div class="note-info info-lg">
	      <a class="avatar" :href="'/user/'+user.id"><img :src="user.avatar" alt=""></a>
	      <div class="btn-wrap">
		  <a class="btn-base btn-hollow btn-md" :href="'/user/'+user.id">个人主页<i class="iconfont icon-youbian"></i></a>
	      <a class="btn-base btn-hollow btn-md" :href="'/chat/with/'+user.id">发消息</a>
	      </div>
	      <div class="title">
	        <a class="name" :href="'/user/'+user.id">{{ user.name }}</a>
	        <i class="man iconfont icon-nansheng1"></i>
	      </div>
	      <div class="info">
	 					写了{{ user.count_words }}个字，获得了{{ user.count_likes }}个喜欢
	      </div>
		</div>
		<!-- 内容 -->
		<div class="content">
			<!-- Nav tabs -->
			 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
			   <li role="presentation" class="active">
			   	<a href="#article" aria-controls="article" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新发布</a>
			   </li>
			   <li role="presentation">
			   	<a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
			   </li>
			   <li role="presentation">
			   	<a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
			   </li>
			 </ul>
			 <!-- Tab panes -->
			 <div class="article_list tab-content">
			   <ul role="tabpanel" class="article-list fade in tab-pane active" id="article">
					<article-list :api="'/user/'+user.id+'?articles=1'" />
			   </ul>
			   <ul role="tabpanel" class="article-list fade tab-pane" id="comment">
 					<article-list :api="'/user/'+user.id+'?commented=1'" />
			   </ul>
			   <ul role="tabpanel" class="article-list fade tab-pane" id="hot">
			   		<article-list :api="'/user/'+user.id+'?hot=1'" />
			   </ul>
			 </div>
		</div>
	</div>
</template>

<script>
export default {

  name: 'User',

  created(){
  	this.fetchData();
  },

  watch: {
    // 如果路由有变化，会再次执行该方法
    '$route': 'fetchData'
  },

  methods: {
  	fetchData() {
        this.id = this.$route.params.id;
        if(this.id){
	  		var api_url = window.tokenize('/api/user/' + this.id);
	  		var vm = this;
	  		window.axios.get(api_url).then(function(response){
	  			vm.user = response.data.user;

	            //标记关注的最后查看时间
	            var api_touch = window.tokenize('/api/follow/' + vm.id + '/users');
	            window.axios.get(api_touch);
	  		});
	  	}
  	}

  },

  data () {
    return {
        id: null,
    	user: null
    }
  }
}
</script>

<style lang="scss">

</style>