<template>
    <div class="recommended_authors">
            <div class="title">
                <span>
                    推荐作者
                </span>
                <a href="javascript:;" @click="fetchData">
                    <i class="glyphicon glyphicon-refresh">
                    </i>
                    换一批
                </a>
            </div>
            <ul class="list">
                <li v-for="user in users">
                    <a class="avatar" :href="'/user/'+user.id" target="_blank">
                        <img :src="user.avatar"/>
                    </a>
                    <a v-if="!user.is_followed"class="follow" href="javascript:;" @click="toggleFollow(user)">
                        ＋ 关注
                    </a>
                    
                    <a v-else href="javascript:;" class="button btn_followed" @click="toggleFollow(user)">
			        <i class="gougou iconfont icon-weibiaoti12"></i><i class="chacha iconfont icon-cha"></i>
		     	    </a>

                    <a class="name" :href="'/user/'+user.id" target="_blank">
                        {{ user.name }}
                    </a>
                    <p>
                        写了{{ user.count_words }}字 · {{ user.count_likes }}喜欢
                    </p>
                </li>
            </ul>
            <a class="find_more" href="/user" target="_blank">
                查看全部
                <i class="iconfont icon-youbian">
                </i>
            </a>
    </div>
</template>

<script>
export default {

  name: 'RecommendAuthors',

  mounted(){
    this.fetchData();
  },

  methods:{
  	  fetchData(){
         var vm=this;
         $('.recommended_authors ul').fadeOut();
          var api=window.tokenize('/api/user/recommend');
           window.axios.get(api).then(function(response){
             vm.users=response.data;
             $('.recommended_authors ul').fadeIn();
         });
  	  },

  	  toggleFollow(user){
  	  	var api_url=window.tokenize('/api/follow/'+user.id+'/users');
  	  	 window.axios.post(api_url).then(function(response){
  	  	 	user.is_followed =response.data;
  	  	 });
  	  }
  },

  data () {
    return {
         users:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>