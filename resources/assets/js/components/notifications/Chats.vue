<template>
	<!-- 收到的聊天消息列表 -->
	<div id="chats">
		<div class="notification_menu">全部消息</div>
		<ul class="chats_list">
			 <li v-for="chat in chats">
			  <div class="pull-right">
				<span class="time">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						{{ chat.time }} <i class="iconfont icon-xia"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="javascript:;" class="link"><i class="iconfont icon-lajitong"></i><span>删除会话</span></a></li>
						<li><a href="javascript:;" class="link"><i class="iconfont icon-heimingdan1"></i><span>加入黑名单</span></a></li>
						<li><a href="javascript:;" class="report link"><i class="iconfont icon-iconset03100"></i><span>举报用户</span></a></li>
					</ul>
				</span>
				<div class="chats_item">
					<a :href="'/user/'+chat.with_id" class="avatar avatar_sm"><img :src="chat.with_avatar" alt="">
						<span v-if="chat.unreads" class="badge">{{ chat.unreads }}</span>
					</a>
					<div class="title">
						<a :href="'/user/'+chat.with_id" class="name">{{ chat.with_name }}</a>
					</div>
					<router-link :to="'/chat/'+chat.id">
						<div class="info"><p>{{ chat.last_message }}</p></div>
					</router-link>
				</div>
			   </div>
			</li>
		</ul>
	</div>

 
</template>

<script>
export default {

  name: 'Chats',

  created(){
  	var  api=window.tokenize('/api/notification/chats')
  	var vm=this;
  	window.axios.get(api).then(function(response){
  		vm.chats=response.data.data;
  		vm.last_page=response.data.last_page;
  	});
  },

  data () {
    return {
           chats:[],
           page:1,
           lastPage:null
    }
  }
}
</script>

<style lang="scss" scoped>
	#chats {
        .chats_list {
            li {
                border-top: 1px solid #f0f0f0;
                position: relative;
                .pull-right {
                    font-size: 13px;
                    margin: 20px 20px 0 0;
                    .time {
                        color: #969696;
                    }
                    .operation {
                        .icon-xia {
                            color: #969696;
                            font-size: 14px;
                        }
                        .dropdown-menu {
                            right: 15px;
                            li {
                                border: none !important;
                            }
                        }
                    }
                }
                .avatar {
                    float: left;
                    margin: 20px 10px 20px 20px;
                    position: relative;
                    .badge {
                        position: absolute;
                        top: -1px;
                        right: -5px;
                    }
                }
                .name {
                    position: absolute;
                    top: 25px;
                    font-size: 15px;
                }
                .wrap {
                    display: block;
                    padding: 20px 20px 20px 0;
                    min-height: 88px;
                    .new_abstract {
                        margin: 28px 0 0;
                    }
                }
            }
        }
    }
</style>