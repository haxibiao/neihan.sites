<template>
    <!-- 收到的关注 -->
	<div id="follows">
		<div class="notification_menu">全部关注</div>
		<ul class="follows_list">
            <li>
                <div class="author">
                    <a class="avatar avatar_xs" href="#">
                        <img src="/images/photo_03.jpg"/>
                    </a>
                    <a class="btn_base btn_followed" href="javascript:;">
                        <span>
                            <i class="iconfont icon-weibiaoti12">
                            </i>
                            <i class="iconfont icon-cha">
                            </i>
                        </span>
                    </a>
                    <div class="info_meta">
                        <div class="info">
                            <a class="title" href="#">
                                小云淮 关注了你
                            </a>
                        </div>
                        <div class="time">2017.11.15  08:09</div>
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