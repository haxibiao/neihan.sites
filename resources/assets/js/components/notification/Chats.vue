<template>
	<div>
		<div class="menu">全部消息</div>
		<ul class="chats-list" v-if="chats.length">
			<li v-for="chat in chats">
				<div class="dropdown time">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						{{ chat.time }} <i class="iconfont icon-xia"></i>
					</a>
					<ul class="dropdown-menu">
						<li><a href="javascript:;" class="link"><i class="iconfont icon-lajitong"></i><span>删除会话</span></a></li>
						<li><a href="javascript:;" class="link"><i class="iconfont icon-heimingdan1"></i><span>加入黑名单</span></a></li>
						<li><a href="javascript:;" class="report link"><i class="iconfont icon-iconset03100"></i><span>举报用户</span></a></li>
					</ul>
				</div>
				<div class="user-info">
					<a :href="'/user/'+chat.with_id" class="avatar"><img :src="chat.with_avatar" alt="">
						<span v-if="chat.unreads" class="badge">{{ chat.unreads }}</span>
					</a>
					<div class="title">
						<a :href="'/user/'+chat.with_id" class="nickname">{{ chat.with_name }}</a>
					</div>
					<router-link :to="'/chat/'+chat.id">
						<div class="info"><p>{{ chat.last_message }}</p></div>
					</router-link>
				</div>
			</li>
		</ul>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Chats',

  created() {
  	var api = window.tokenize('/api/notification/chats');
  	var vm = this;
  	window.axios.get(api).then(function(response){
  		vm.chats = response.data.data;
  		vm.last_page = response.data.last_page;
  	});

  },

  data () {
    return {
    	chats: [],
    	page: 1,
    	lastPage: null
    }
  }
}
</script>

<style lang="scss" scoped>
	.chats-list {
		li {
			.time {
				position: relative;
				font-size: 13px;
				float: right;
				i {
					font-size: 14px;
					margin-left: 3px;
				}
				.dropdown-menu {
					right: 5px;
				}
			}
			.user-info{
				.avatar {
					position: relative;
					.badge {
						top: 0;
						right: -5px;
					}
				}
				.title {
					float: left;
					padding-left: 0;
				}
				.info {
					height: 48px;
					padding-top: 28px;
				}
			}
		}
	}
</style>