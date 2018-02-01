<template>
	  <div class="tool_cabinet">
        <div class="comment_wrap">
            <div class="tool_group">
<!--                 <a href="javascript:;" class="action_btn">
                    <i class="iconfont icon-xin like">
                    </i>
                    <span>
                        喜欢
                    </span>
                </a>
                <a href="javascript:;" class="action_btn">
                    <i class="iconfont icon-03xihuan like_active">
                    </i>
                    <span>
                        1 喜欢
                    </span>
                </a> -->
                <a href="javascript:;" class="action_btn">
                    <i class="iconfont icon-fabulous like">
                    </i>
                    <span>
                        赞
                    </span>
                </a>
                <a href="javascript:;" class="action_btn">
                    <i class="iconfont icon-dianzan like_active">
                    </i>
                    <span>
                        1 赞
                    </span>
                </a>
                <a href="javascript:;" @click="showComment" class="action_btn">
                    <i class="iconfont icon-xinxi">
                    </i>
                    <span>
                        3 评论
                    </span>
                </a>
                <a href="javascript:;" class="action_btn">
                    <i class="simditor-icon simditor-icon-quote-left">
                    </i>
                    <span>
                        引用
                    </span>
                </a>
                <share placement="top" class="action_btn">
                    <span>分享</span>
                </share>
                <a class="report action_btn" href="javascript:;">
                    <span>
                        举报
                    </span>
                </a>
            </div>
        </div>
        <div v-if="isComent" class="again_comment">
             <new-comment type="comments" :id="this.id" is-login="this.isLogin">
             </new-comment>
        </div>
    </div>
</template>

<script>
export default {

  name: 'CommentTool',

  props:['id','isLogin'],

  mounted(){
     this.get();
  },

  methods:{
      showComment(){
        this.isComent=this.isComent? false:true ;
      }
  },

  data () {
    return {
        isComent:false
    }
  }
}
</script>

<style lang="css" scoped>
</style>