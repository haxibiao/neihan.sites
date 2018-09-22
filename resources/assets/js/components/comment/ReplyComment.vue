<template>
		<transition name="slide">
			<div v-if="show">
				<form class="new-comment">
					<div>
						<div placeholder="写下你的评论..." @keyup.ctrl.enter="send" @input="setComment" class="commenTable" contentEditable="true" ref="editContent" v-html="message" v-once>
						</div>
						<div class="write-block">
							<div class="emoji-wrap">
								<a class="emoji"><i class="iconfont icon-smile"></i></a>
							</div>
							<div class="hint">Ctrl+Enter 发表</div>
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
      	this.message = this.body;
  		return this.isShow;
  	},
  },

  methods: {
  	send() {
      if(!this.message) {
        return null
      };

      //防止XSS 数据格式化 替换成at标签
      var reg = /<span class="atwho-inserted" data-atwho-at-query="@(.*?)" contenteditable="false"><span data-id="(\d+)">@(.*?)<\/span><\/span>/g;
      this.message = this.message.replace(reg,'<at href="/user/$2" data-id="$2">@$3</at>');
      
  	  this.$emit('sendReply', this.message);
  	  //清空掉评论框内容
  	  this.$refs.editContent.innerHTML = null;
  	  this.message = null;
  	},
  	setComment() {
  		this.message = this.$refs.editContent.innerHTML;
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