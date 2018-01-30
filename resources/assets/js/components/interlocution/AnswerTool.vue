<template>
    <div class="tool_cabinet">
        <div class="answer_tool comment_wrap">
            <div class="tool_group">
                <a href="javascript:;" @click="likeAnswer" class="action_btn">
                    <i :class="['iconfont', answer.liked ? 'icon-dianzan like_active' : 'icon-fabulous like']">
                    </i>
                    <span>
                        {{ this.answer.count_likes }} 赞
                    </span>
                </a>
                <a href="javascript:;" @click="unlikeAnswer" class="action_btn">
                    <i :class="['iconfont',answer.unliked ? 'icon-zan2'  :'icon-dianzan1']">
                    </i>
                    <span>
                        {{ this.answer.count_unlikes }} 踩
                    </span>
                </a>
                <a href="javascript:;" @click="showComment" class="action_btn">
                    <i class="iconfont icon-xinxi">
                    </i>
                    <span>
                        {{ this.answer.count_comments }} 评论
                    </span>
                </a>
                <share placement="top" class="action_btn">
                    <span>分享</span>
                </share>

                <!-- 未采纳 -->
                <!-- <a href="javascript:;" class="action_btn adopt">
                    <span>
                        采纳
                    </span>
                </a> -->
                <a href="javascript:;" class="action_btn adopt btn_font_adopt">
                    <span>
                        <i class="iconfont icon-weibiaoti12">
                        </i>
                        <i class="iconfont icon-cha">
                        </i>
                    </span>
                </a>
                <a class="pull-right action_btn" href="#" v-if="this.isLogin && isSelf && answer.deleted" @click="deleteAnswer">
                    <i class="iconfont icon-lajitong"></i>
                    <span>
                        删除
                    </span>
                </a>
                <a v-else class="pull-right action_btn" @click="reportAnswer">
                    <i class="iconfont icon-jinggao"></i>
                    <span>举报({{ this.answer.count_reports }})</span>
                </a>
            </div>
        </div>
        <div v-if="isComent">
            <comments :id="this.answerId" :is-login="this.isLogin" type="answers"></comments>
        </div>
    </div>
</template>
<script>
    export default {

  name: 'AnswerTool',

  props:['answerId','isLogin'],

  created(){
     this.fetchData();
  },

  methods:{
      fetchData(){
        var vm=this;
        var api= "/api/answer/info/"+vm.answerId;
        window.axios.get(api).then(function(response){
            vm.answer=response.data;
        });
      },

      likeAnswer(){
        this.answer.liked = !this.answer.liked;
        this.answer.count_likes += this.answer.liked ? 1 : -1;
        window.axios.get(window.tokenize('/api/like-answer/' + this.answer.id));
      },

      unlikeAnswer(){
        this.answer.unliked = !this.answer.unliked;
        this.answer.count_unlikes += this.answer.unliked ? 1 : -1;
        window.axios.get(window.tokenize('/api/unlike-answer/' + this.answer.id));
      },

      deleteAnswer(){
        this.answer.deleted = !this.answer.deleted;

        window.axios.get(window.tokenize('/api/delete-answer/' + this.answer.id));
      },

      reportAnswer(){
        this.answer.reported = !this.answer.reported;
        this.answer.count_reports += this.answer.reported ? 1 : -1;
        this.answer.count_unlikes += this.answer.reported ? 1 : -1;

        window.axios.get(window.tokenize('/api/report-answer/' + this.answer.id));
      },

      isSelf(){
          return this.answer.user_id==window.current_user_id ? true  : false ;
      },

      showComment(){
        this.isComent=this.isComent? false:true ;
      }
  },

  data () {
    return {
         answer:[],
         isComent:false,
    }
  }
}
</script>
<style lang="css" scoped="">

</style>