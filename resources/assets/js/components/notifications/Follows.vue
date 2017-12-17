<template>
    <!-- 收到的关注 -->
	<div id="follows">
		<div class="menu">全部关注</div>
		<ul class="follows_list">
            <li v-for="notification in notifications">
                <div class="follow_item">

                    <follow type="users" :id="notification.user_id" :user-id="current_user_id" :followed="notification.is_followed"></follow>

                    <a class="avatar" :href="'/user/'+notification.user_id">
                        <img :src="notification.user_avatar"/>
                    </a>
<!--                     <a class="following" href="javascript:;">
                        <span>
                            <i class="iconfont icon-weibiaoti12">
                            </i>
                            <i class="iconfont icon-cha">
                            </i>
                        </span>
                    </a> -->
                    <a class="title" href="#">
                        <a :href="'/user/'+notification.user_id">{{ notification.user_name }}</a>
                         关注了你
                    </a>
                    <div class="info">
                    	{{ notification.time }}
                    </div>
                </div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Follows',

  computed:{
     current_user_id(){
        return window.current_user_id;
     }
  },

  created(){
    var api_url=window.tokenize('/api/notifications/follow');
    var vm=this;
    window.axios.get(api_url).then(function(response){
          vm.notifications=response.data;
    });
  },

  data () {
    return {
        notifications:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>