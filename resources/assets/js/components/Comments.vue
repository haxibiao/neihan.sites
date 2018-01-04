<template>
    <div>
        <div class="comment_list">
            <!-- 写评论 -->
            <div>
                <!-- 详情页的评论 -->
                <form class="new_comment">
                    <div v-if="isLogin">
                        <a class="avatar avatar_xs">
                            <img :src="currentUser.avatar"/>
                        </a>
                        <textarea @click="showSend" class="text_container" placeholder="写下你的评论..." v-model="newComment.body">
                        </textarea>
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
                            <a @click="postComment" class="btn_base btn_follow btn_followed_sm pull-right">
                                发送
                            </a>
                            <a @click="hideSend" class="cancel pull-right">
                                取消
                            </a>
                        </div>
                    </div>
                    <div v-else="">
                        <div class="text_container sign_container">
                            <a class="btn_base btn_sign" href="/login">
                                登录
                            </a>
                            <span>
                                后发表评论
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="normal_comment_list">
                <div>
                    <div>
                        <div class="top_title">
                            <span>
                                {{ comments.length }}条评论
                            </span>
                            <a class="author_only" href="javascript:;">
                                只看作者
                            </a>
                            <div class="pull-right">
                                <a class="active" href="javascript:;">
                                    按喜欢排序
                                </a>
                                <a href="javascript:;">
                                    按时间正序
                                </a>
                                <a href="javascript:;">
                                    按时间倒序
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- 评论 -->
                    <div class="comment" v-for="comment in comments">
                        <div>
                            <div class="author">
                                <a :href="'/user'+comment.user.id" class="avatar">
                                    <img :src="comment.user.avatar"/>
                                </a>
                                <div class="info">
                                    <a :href="'/user/'+comment.user.id" class="name">
                                        {{ comment.user.name }}
                                    </a>
                                    <div class="meta">
                                        <span>
                                            {{ get_lou(comment.lou) }} ·{{ comment.time }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="comment_wrap">
                                <p>
                                    {{ comment.body }}
                                </p>
                                <div class="tool_group">
                                    <a href="javascript:;">
                                        <i class="iconfont icon-fabulous">
                                        </i>
                                        <span @click="likeComment(comment)" v-if="!comment.liked">
                                            赞
                                        </span>
                                    </a>
                                    <a href="javascript:;">
                                        <i class="iconfont icon-xinxi">
                                        </i>
                                        <span>
                                            回复
                                        </span>
                                    </a>
                                    <a class="report" href="javascript:;">
                                        <span>
                                            举报
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="sub_comment_list">
                                <div class="comment_wrap" v-for="reply in comment.reply_comments">
                                    <p>
                                        <a class="moleskine_author" href="/v1/user" target="_blank">
                                            {{ reply.user.name }}
                                        </a>
                                        ：
                                        <span>
                                            <!-- <a href="#" class="moleskine_author">
                                        @哈尼
                                    </a> -->
                                            {{ reply.body }}
                                        </span>
                                    </p>
                                    <div class="tool_group">
                                        <span class="comment_time">
                                            {{ reply.created_at }}
                                        </span>
                                        <a href="#">
                                            <i class="iconfont icon-xinxi">
                                            </i>
                                            <span>
                                                回复
                                            </span>
                                        </a>
                                        <a class="report" href="#">
                                            举报
                                        </a>
                                    </div>
                                </div>
                                <div class="comment_wrap more_comment">
                                    <a class="add_comment_btn">
                                        <i class="iconfont icon-xie">
                                        </i>
                                        <span>
                                            添加新评论
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="pagination" v-if="lastPage >1">
                <li @click="goPage(page)" v-for="page in lastPage">
                    <a :class="page==currentPage ?'active':''">
                        {{ page }}
                    </a>
                </li>
                <li @click="nextPage">
                    <a>
                        <span>
                            下一页
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {

  name: 'Comments',

  props: ['id','type','isLogin'],

  mounted(){
      this.loadComments();
  },

  computed:{
    currentUser(){
      return{
            id:window.current_user_id,
            name:window.current_user_name,
            avatar:window.current_user_avatar,
      };
    }
  },

  methods:{
      showSend(){
          $('.new_comment .write_block').fadeIn();
      },
      hideSend(){
          $('.new_comment .write_block').fadeOut('fast');
      },

      get_lou(lou){
        if(lou==1){
          return "沙发";
        }
        if(lou==2){
          return "板凳";
        }
        return lou+'楼';
      },

      get_post_url(){
        return window.tokenize('/api/comment');
      },

      get_get_url(){
          var api_url='/api/comment/'+this.id+'/'+this.type;
          if(this.currentPage >1 ){
              api_url += '&page=' +this.currentPage;
          }
          return api_url;
      },

      get_action_url(id,action){
          return window.tokenize('/api/comment'+ id +'/'+action);
      },

      likeComment(comment){
         comment.liked=1;
         window.axios.get(this.get_action_url(comment.id,'like')).then(function(response){
           comment.likes=response.data.likes;
         });
      },

      unlikeComment(comment){
         comment.liked=0;
         window.axios.get(this.get_action_url(comment.id,'like')).then(function(response){
            comment.likes=response.data.likes;
         });
      },

      reportComment(comment){
        comment.reported=1;
        window.axios.get(this.get_action_url(comment.id,'report')).then(function(response){
           comment.reports=response.data.reports;
        });
      },

      unreportComment(comment){
        comment.reported=0;
        window.axios.get(this.get_action_url(comment.id,'report')).then(function(response){
          comment.reports=response.data.reports;
        });
      },

      loadComments(){
         var vm=this;
         window.axios.get(this.get_get_url()).then(function(response){
           vm.comments=response.data.data;
           vm.lastPage=response.data.last_page;
           vm.total=response.data.total;
         });
      },

      loadMoreComments(){
        if(this.lastPage >this.currentPage){
          this.currentPage++;
          this.loadComments();
        }
      },
      
      //乐观更新函数
      postComment(){
         if(!this.newComment.is_replay_comment){

           this.newComment.lou=this.total+1;
           var newComment=Object.assign({},this.newComment);
           newComment.user=this.currentUser;
           this.comments=this.comments.concat(newComment);
         }

      var vm=this;
       window.axios.post(this.get_post_url(),this.newComment).then(function(response){
        //更新楼中的comments
        if(vm.newComment.is_replay_comment){
          var commented=response.data;
          vm.commented=commented;
        }else{
          vm.comments.pop();
          vm.comments=vm.comments.concat(response.data);

          //发布一次直接干掉
          vm.newComment.body='';
          vm.newComment.comment_id=null;
          vm.newComment.comment=null;
        }
       });
      },

      replyComment(comment){
        this.newComment.body='@'+comment.user.name+':';
        this.newComment.comment_id=comment.id;
        this.newComment.comment=coment;
        this.newComment.is_replay_comment=true;
        this.commented=comment;
      },

      nextPage(){
          if(this.currentPage < this.lastPage){
            this.currentPage++
            this.loadComments();
          }
      },

      goPage(page){
        this.currentPage=page;
        this.loadComments();
      }
  },

  data () {
    return {
        currentPage:1,
        lastPage:null,
        total:0,
        comments:[],
        commented:null,
        newComment:{
            user:this.currentUser,
            is_new:true,
            time:'刚刚',
               body:null,
               commentable_id: this.id,
               commentable_type:this.type,
               comment_id:null,
            comment:null,
            likes:0,
            reports:0,
            is_replay_comment:false,

        },
        isSingIn:true,
        sendBox:false
    }
  }
}
</script>
<style lang="scss" scoped="">
    .new_comment {
    position: relative;
    margin-left: 48px;
    .avatar {
        position: absolute;
        left: -48px;
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
    .sign_container {
        text-align: center;
    }
    textarea {
        resize: none;
        vertical-align: top;
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