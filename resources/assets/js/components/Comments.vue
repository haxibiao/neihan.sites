<template>
    <div>
        <div class="comment_list">
            <!-- 写评论框 -->
            <div class="comment_box">
                <form class="new_comment">
                    <div v-if="isLogin">
                        <a class="avatar avatar_xs">
                            <img :src="currentUser.avatar"/>
                        </a>
                        <div class="textarea_box">
                            <textarea @click="showSend" placeholder="写下你的评论..." v-model="newComment.body"></textarea>
                        </div>
                        <transition name="fade">
                            <div v-if="showButton" class="write_block">
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
                        </transition>
                    </div>
                    <div v-else>
                      <a class="avatar avatar_xs" href="#">
                          <img src="/images/photo_user.png"/>
                      </a>
                        <div class="textarea_box sign_container">
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
            <!-- 全部评论 -->
            <div class="normal_comment_list">
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
                <!-- 评论 -->
                <div class="comment" v-for="comment in comments">
                    <div>
                        <div class="author">
                            <a :href="'/user'+comment.user.id" class="avatar avatar_xs">
                                <img :src="comment.user.avatar"/>
                            </a>
                            <div class="info_meta">
                                <a :href="'/user/'+comment.user.id" class="nickname">
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
                                <a href="javascript:;" class="action_btn">
                                    <i class="iconfont icon-fabulous like">
                                    </i>
                                    <span @click="likeComment(comment)" v-if="!comment.liked">
                                        赞
                                    </span>
                                </a>
                                <a href="javascript:;" class="action_btn" @click="replyingComment(comment)">
                                    <i class="iconfont icon-xinxi">
                                    </i>
                                    <span>
                                        回复
                                    </span>
                                </a>
                                <a class="report action_btn" href="javascript:;">
                                    <span>
                                        举报
                                    </span>
                                </a>
                            </div>
                        </div>
                        <!-- 子评论 -->
                        <div class="sub_comment_list">
                            <div class="comment_wrap" v-for="reply in comment.reply_comments">
                                <p>
                                    <a class="moleskine_author" href="/user" target="_blank">
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
                                    <a href="#" class="action_btn">
                                        <i class="iconfont icon-xinxi">
                                        </i>
                                        <span>
                                            回复
                                        </span>
                                    </a>
                                    <slot></slot>
                                    <a class="report action_btn" href="#">
                                        举报
                                    </a>
                                </div>
                            </div>
                            <div class="comment_wrap more_comment">
                                <a class="add_comment_btn" @click="toggle(comment)">
                                    <i class="iconfont icon-xie">
                                    </i>
                                    <span>
                                        添加新评论
                                    </span>
                                </a>
                                <!-- <span class="line_warp">
                                    <span>还有67条评论，</span>
                                    <a href="#">
                                        展开查看
                                    </a>
                                    <a href="#">
                                        收起
                                    </a>
                                </span> -->
                            </div>
                            <reply-comment :is-show="comment.replying" :body="replyComment.body" @sendReply="sendReply" @toggle-replycomment="toggle(comment)"></reply-comment>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 分页 -->
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
          this.showButton = true;
      },
      hideSend(){
          this.showButton = false;
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

      replyComments(comment){
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
      },

      toggle(comment){
      if(this.checkLogin()) {
        this.replyComment.body = '';
        comment.replying = !comment.replying;
        if(comment.replying) {
          this.commented = comment;
          this.replyComment.comment_id = comment.id;
          this.replyComment.user = this.user;
        }
      }
    },

    checkLogin() {      
      if(!this.isLogin) {
        window.location.href = '/login';
        return false;
      }
      return true;
    },

    //回复评论
    sendReply(body) {
      console.log(body);
      this.replyComment.body = body;

      //乐观更新
      this.commented.reply_comments.push(this.replyComment);

      var _this = this;      
      window.axios.post(this.postApiUrl(), this.replyComment).then(function(response) { 
        //更新被回复的楼中comments...

        //这里服务器返回数据..
        _this.commented.reply_comments.pop();
        _this.commented.reply_comments.push(response.data);

        //发布成功，清空
        _this.replyComment.body = null;
      });
    },

    replyingComment: function(comment) {
      if(this.checkLogin()) {
        this.replyComment.body = '';
        comment.replying = !comment.replying;
        this.commented = comment;
        this.replyComment.comment_id = comment.id;
        this.replyComment.user = this.user;
      }
    },

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
        replyComment: {
            user: {},
            is_reply: 1,
            time: '刚刚',
            body: null,
            comment_id: null, //回复的评论id
            commentable_id: this.id,
            commentable_type: this.type,
            likes: 0,
            reports: 0
        },
        isSingIn:true,
        sendBox:false,
        showButton: false
    }
  }
}
</script>
<style lang="scss" scoped="">
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