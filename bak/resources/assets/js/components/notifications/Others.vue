<template>
	<!-- 收到的其他消息 -->
	<div id="others">
		<div class="notification_menu">其他消息</div>
		<!-- 有消息 -->
		<ul v-if="notifications.length" class="others_list">
			<li v-for="notification in notifications">
				<div class="info_meta">
					<div class="info">
						<i class="iconfont icon-paihang"></i>
						<span v-html="notification.message"></span>
					</div>
					<div class="time">{{ notification.time }}</div>
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

  name: 'Others',

  created(){
  	 this.fechData();
  },

  methods:{
  	  fechData(){
  	  	 var api =window.tokenize('/api/notifications/others');
  	  	 var vm =this;
  	  	 window.axios.get(api).then(function(response){
  	  	 	  vm.notifications =response.data;
  	  	 });
  	  }
  },

  data () {
    return {
          notifications:[]
    }
  }
}
</script>

<style lang="scss" scoped>
	#others {
        .others_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                .info_meta {
                    line-height: 1.7;
                    font-size: 15px;
                    .info {
                        i {
                            margin-right: 5px;
                            color: #d96a5f;
                            font-size: 20px;
                        }
                        .title {
                            color: #2B89CA;
                        }
                    }
                }
                @media screen and (max-width: 540px) {
	                padding: 20px 5px;
	            }
            }
        }
    }
</style>