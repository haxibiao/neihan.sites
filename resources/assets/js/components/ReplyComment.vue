<template>
		<transition name="slide">
			<div v-if="show">
				<form class="new-comment">
					<div>
						<textarea placeholder="写下你的评论..." @keyup.enter="send" v-model="message"></textarea>
						<div class="write-block">
							<div class="emoji-wrap">
								<a class="emoji"><i class="iconfont icon-smile"></i></a>
							</div>
							<div class="hint">⌘+Return 发表</div>
							<a class="btn-base btn-handle btn-md" :class="{'disable':message?false:true}" @click="send">发送</a>
							<a class="cancel" @click="toggle">取消</a>
						</div>
					</div>
				</form>
			</div>
		</transition>
</template>

<script>
export default {

  name: 'ReplyComment',

  props:['isShow','body'],

  computed: {
  	show() {
      this.message = null;
  		return this.isShow;
  	},
  },

  methods: {
  	send() {
      if(!this.message) {
        return null
      };
  		this.$emit('sendReply', this.message);
  		this.message = null;
  	},
  	toggle() {
  		this.$emit('toggle-replycomment');
  	}
  },

  data () {
    return {
    	message: '',
    }
  }
}
</script>

<style lang="scss" scoped>
	.slide-enter-active {
	  transition: all .2s linear;
	}
	.slide-leave-active {
	  transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0);
	}
	.slide-enter, .slide-leave-to {
	  transform: translateY(-10px);
	  opacity: 0;
	}
</style>