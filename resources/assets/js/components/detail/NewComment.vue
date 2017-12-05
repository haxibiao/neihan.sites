<template>
	<div>
        <form class="new_comment">
            <div v-if="isSignIn">
                <a class="avatar" href="#">
                    <img src="/images/photo_01.jpg"/>
                </a>
                <textarea placeholder="写下你的评论..." @click="showSend">
                </textarea>
                <div class="write_block">
                    <div class="emoji_wrap">
                        <a href="javascript:;" class="emoji">
                            <i class="iconfont icon-smile"></i>
                        </a>
                    </div>
                    <div class="hint">⌘+Return 发表</div>
                    <a href="javascript:;" class="btn_send">发送</a>
                    <a href="javascript:;" class="cancel" @click="hideSend">取消</a>
                </div>
            </div>
            <div v-else>
                <a class="avatar" href="#">
                    <img src="/images/photo_user.png"/>
                </a>
                <div class="sign_container">
                	<a href="/v1/sign_in" class="btn_sign_in">登录</a>
                	<span>后发表评论</span>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {

  name: 'NewComment',

  data () {
    return {
        isSignIn: true,
        sendBox: false
    }
  },

  methods: {
  	showSend: function() {
  		$('.new_comment .write_block').fadeIn();
  	},
  	hideSend: function() {
  		$('.new_comment .write_block').fadeOut('fast');
  	}
  }

}
</script>

<style lang="css" scoped>
</style>