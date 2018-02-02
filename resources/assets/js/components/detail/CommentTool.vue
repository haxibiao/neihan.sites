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
                <a href="javascript:;" class="action_btn" @click="comment.liked? like(comment) : unlike(comment)">
                    <i v-if="comment.liked" class="iconfont icon-dianzan like_active">
                    </i>
                    <i v-else class="iconfont icon-fabulous like">
                    </i>
                     
                    <span>
                       {{ comment.likes }} 赞
                    </span>
                </a>
<!--                 <a href="javascript:;" class="action_btn">
                    <i class="iconfont icon-dianzan like_active">
                    </i>
                    <span>
                        1 赞
                    </span>
                </a> -->
                <a href="javascript:;" @click="showComment" class="action_btn">
                    <i class="iconfont icon-xinxi">
                    </i>
                    <span>
                        {{ comment.count_comments }} 评论
                    </span>
                </a>
<!--                 <a href="javascript:;" class="action_btn">
                    <i class="simditor-icon simditor-icon-quote-left">
                    </i>
                    <span>
                        引用
                    </span>
                </a> -->
                <share placement="top" class="action_btn">
                    <span>分享</span>
                </share>
                <a class="report action_btn" href="javascript:;" @click="reportComment">
                    <span>
                        举报({{ comment.reports }})
                    </span>
                </a>
            </div>
        </div>
        <div v-if="isComent" class="again_comment">
             <comments type="comments" :id="this.id" is-login="this.isLogin">
             </comments>
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
      },

      apiUrl(){
          return window.tokenize('/api/comment/'+this.id+'/like');
      },

      like(comment){
        this.comment.liked =1;
        var vm=this;
        window.axios.get(this.apiUrl()).then(function(response){
            comment.likes=response.data.likes;
        });
      },

      unlike(comment){
         this.comment.liked=0;
         window.axios.get(this.apiUrl()).then(function(response){
            comment.likes=response.data.likes;
         });
      },

      get(){
         var api=window.tokenize('/api/comment/'+this.id+'/like?get_comment=1');
         var vm=this;
         window.axios.get(api).then(function(response){
            vm.comment=response.data;
         });
      },
      reportComment(){
        this.comment.reports++;
        var api=window.tokenize('/api/comment/'+ this.id+'/report');
        var vm=this;
        window.axios.get(api).then(function(response){
           comment.reports=response.data.reports;
        });
      },
  },

  data () {
    return {
        isComent:false,
        comment:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>