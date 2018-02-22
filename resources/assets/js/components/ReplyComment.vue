<template>
	<!-- 写评论框 -->
    <transition name="slide">
        <div class="comment_box" v-if="show">
            <form class="new_comment">
                <div>
                    <a class="avatar avatar_xs">
                        <img src="/images/photo_04.png"/>
                    </a>
                    <div class="textarea_box">
                        <textarea placeholder="写下你的评论..." v-model="content"></textarea>
                    </div>
                    <div class="write_block">
                        <div class="emoji_wrap">
                            <a class="emoji" href="javascript:;">
                                <i class="iconfont icon-smile">
                                </i>
                            </a>
                        </div>
                        <div class="hint">
                            ⌘+Return 发表
                        </div>
                        <a class="btn_base btn_follow btn_followed_sm pull-right" @click="postComment">
                            发送
                        </a>
                        <a @click="toggle" class="cancel pull-right">
                            取消
                        </a>
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
    message: {
        get() {
            return this._body ? this._body : this.body;
        },
        set(value) {
            this._body = value;
        }
    },
    show() {
        return this.isShow;
    },
  },

  methods: {
    postComment() {
        this.$emit('sendReply', this.content);
        this.content=null;
    },

    toggle() {
        this.$emit('toggle-replycomment');
    }

  },

  data () {
    return {
        _body: null,
        content:null
    }
  }
}
</script>

<style lang="css" scoped>
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