<template>
	<div>
		<form class="new-comment">
			<a class="avatar"><img :src="isLogin?user.avatar:'/images/author_03.jpg'"></a>
			<div v-if="isLogin" >
				<textarea placeholder="写下你的评论..." @click="showSend" @keyup.ctrl.enter="send" v-model="newComment"></textarea>
				<transition name="fade">
					<div v-if="showButton" class="write-block">
						<div class="emoji-wrap">
							<a class="emoji"><i class="iconfont icon-smile"></i></a>
						</div>
						<div class="hint">Ctrl+Enter 发表</div>
						<a class="btn-base btn-handle btn-md" :class="{'disable':!newComment}" @click="send">发送</a>
						<a class="cancel" @click="hideSend">取消</a>
					</div>
				</transition>
			</div>
			<div v-else>
				<div class="sign-container">
			    <a href="/login" class="btn-base btn-handle btn-md">登录</a>
			    <span>后发表评论</span>
				</div>
			</div>
		</form>
	</div>
</template>

<script>
export default {

  name: 'NewComment',

  computed: {
  	user() {
  		return window.user;
  	},
  	isLogin() {
  		return window.user !== undefined;
  	}
  },

  methods: {
  	send() {
  		if(!this.newComment) {
  		  return null
  		};
  		this.$emit('sendComment', this.newComment);
  		this.newComment = null;
  	},
  	showSend: function(){
  		this.showButton = true
  	},
  	hideSend: function(){
  		this.showButton = false
  	}
  },

  data () {
    return {
    	newComment: null,
    	showButton:false,
    }
  },
}
</script>

<style lang="scss" scoped>
	.fade-enter-active {
	  transition: opacity .3s
	}
	.fade-leave-active {
		transition: opacity .5s
	}
	.fade-enter, .fade-leave-to {
	  opacity: 0
	}
</style>