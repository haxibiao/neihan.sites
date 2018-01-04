
<template>

      <a v-if="isLogin && !is_small" :class="isFollowed ? 'btn_base btn_followed' : 'btn_base btn_follow'" @click="toggleFollow">
        	   <span v-if="!isSelf && !isFollowed">＋ 关注</span>
            <span v-if="!isSelf && isFollowed"><i class="iconfont icon-weibiaoti12"></i><i class="iconfont icon-cha"></i></span>
      </a>

      <a v-else :class="isFollowed ? 'btn_base btn_followed btn_followed_xs' : 'btn_base btn_follow btn_follow_xs'" @click="toggleFollow">
         <span v-if="!isSelf && !isFollowed">＋ 关注</span>
        <span v-if="!isSelf && isFollowed"><i class="iconfont icon-weibiaoti12"></i><i class="iconfont icon-cha"></i></span>
     </a>

      <a v-else="!isLogin" class="btn_base btn_follow" href="/login"><span>＋ 关注</span></a>

</template>

<script>
export default {

  name: 'Follow',

  props:['type','userId','id','followed','is_small'],
  
  computed:{
       isSelf() {
          return this.type=='user' && this.id==this.userId;
       },
       isLogin(){
          return this.userId >0;
       },
       isFollowed(){
          return this.followedResult === null? this.followed:this.followedResult;
       },
  },

  methods:{
  	  toggleFollow(){
          var vm=this;
          var api_url=window.tokenize('/api/follow/'+this.id+'/'+this.type);
          window.axios.post(api_url).then(function(response){
          	  vm.followedResult=response.data;
          });
  	  },
  },

  data () {
    return {
         followedResult:null,
         is_small:false
    }
  }
}
</script>

<style lang="css" scoped>
</style>