<template>
	<div>
        <!-- 详情页的评论 -->
        <form class="new_comment">
            <div v-if="isSignIn">
                <a class="avatar avatar_xs" href="#">
                    <img src="/images/photo_01.jpg"/>
                </a>
                <textarea placeholder="写下你的评论..." @click="showSend" class="text_container">
                </textarea>
                <div class="write_block">
                    <div class="emoji_wrap">
                        <a href="javascript:;" class="emoji">
                            <i class="iconfont icon-smile"></i>
                        </a>
                    </div>
                    <div class="hint">⌘+Return 发表</div>
                    <a href="javascript:;" class="btn_base btn_follow btn_followed_sm pull-right">发送</a>
                    <a href="javascript:;" class="cancel pull-right" @click="hideSend">取消</a>
                </div>
            </div>
            <div v-else>
                <a class="avatar avatar_xs" href="#">
                    <img src="/images/photo_user.png"/>
                </a>
                <div class="text_container sign_container">
                  <a href="/v2/sign_in" class="btn_base btn_sign">登录</a>
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

<style lang="scss" scoped>
.new_comment {
    position: relative;
    margin-left: 48px;
    .avatar {
        position: absolute;
        left: -48px;
    }
    .sign_container {
        text-align: center;
    }
    .text_container {
        width: 100%;
        height: 80px;
        border-radius: 4px;
        border: 1px solid #dcdcdc;
        font-size: 13px;
        background-color: hsla(0, 0%, 71%, .1);
        padding: 10px 15px;
    }
    textarea {
        vertical-align: top;
        resize: none;
    }
    .write_block {
        height: 50px;
        display: none;
        color: #969696;
        .emoji_wrap {
            position: relative;
            .emoji {
                float: left;
                margin-top: 14px;
                color: #969696;
                i {
                    font-size: 20px;
                }
                &:hover {
                    color: #333;
                }
            }
        }
        .hint {
            font-size: 13px;
            float: left;
            margin: 19px 0 0 20px;
        }
        .btn_followed_sm {
            margin: 10px 0;
        }
        .cancel {
            font-size: 16px;
            color: #969696;
            margin: 18px 30px 0 0;
        }
        @media screen and (max-width: 400px) {
            .btn_follow {
                width: 60px;
            }
            .cancel {
                margin-right: 5px;
            }
        }
    }
}
</style>