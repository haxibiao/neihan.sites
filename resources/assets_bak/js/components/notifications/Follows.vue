<template>
    <!-- 收到的关注 -->
	<div id="follows">
		<div class="notification_menu">全部关注</div>
		<ul v-if="notifications.length" class="follows_list">
            <li v-for="notification in notifications">
                <div class="author">
                    <follow type="users" :id="notification.user_id" :user-id="current_user_id" :followed="notification.is_followed"></follow>

                    <a class="avatar avatar_xs" :href="'/user/'+notification.user_id">
                        <img :src="notification.user_avatar"/>
                    </a>
                    <div class="info_meta">
                      <div class="info">
                          <a :href="'/user/'+notification.user_id" class="title">{{ notification.user_name }}</a>
                           关注了你
                      </div>
                      <div class="time">
                      	{{ notification.time }}
                      </div>
                    </div>
                </div>
			</li>
		</ul>
        <!-- 空白页面 -->
        <div v-else class="blank_content">
            <blank-content></blank-content>
        </div>
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

<style lang="scss" scoped>
    #follows {
        .follows_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                font-size: 15px;
                .author {
                    .avatar {
                        float: left;
                    }
                    .btn_base {
                        float: right;
                    }
                    .info_meta {
                        padding: 0 0 0 50px;
                    }
                }
            }
        }
    }
</style>