<template>
    <div class="comment-content">
        <!-- 写评论 -->
        <div v-if="isLogin">
            <form class="new-comment">
                <a class="avatar"><img :src="currentUser.avatar"></a>
                <div v-if="isSignIn" >
                    <textarea placeholder="写下你的评论..." @click="showSend" v-model="newComment.body"></textarea>
                    <div class="write-block">
                        <div class="emoji-wrap">
                            <a class="emoji"><i class="iconfont icon-smile"></i></a>
                        </div>
                        <div class="hint">⌘+Return 发表</div>
                        <a class="btn-base btn-handle btn-md"  @click="postComment">发送</a>
                        <a class="cancel" @click="hideSend">取消</a>
                    </div>
                </div>
                <div v-else>
                    <div class="sign-container">
                    <a href="/login" class="btn-base btn-handle btn-md">登录</a>
                    <span>后发表评论</span>
                    </div>
                </div>
            </form>
        </div>
        <!-- 默认评论 -->
        <div class="normal-comment">
            <div>
                <!-- 顶部信息 -->
                <div class="top">
                    <span>{{ comments.length }}条评论</span>
                    <a class="btn-base btn-light btn-sm">只看作者</a>
                    <div class="pull-right">
                        <a class="active">按喜欢排序</a>
                        <a>按时间正序</a>
                        <a>按时间倒序</a>
                    </div>
                </div>
                <!-- 评论 -->
                <div class="comment-item" v-for="comment in comments">
                    <div>
                        <div class="user-info info-xs">
                            <a href="user" target="_blank" class="avatar"><img :src="comment.user.avatar"></a>
                            <div class="title">
                                <a :href="'/user/'+comment.user.id" target="_blank" class="nickname">{{ comment.user.name }}</a>
                            </div>
                            <div class="info">
                                <a :name="comment.lou"><span>{{ comment.lou }}楼 · {{ comment.time }}</span></a>
                            </div>
                        </div>
                        <div class="comment-dtl">
                            <p>
                                {{ comment.body }}
                            </p>
                            <div class="tool-group">
                                <a class="like"><i class="iconfont icon-fabulous"></i> <span>赞</span></a>
                                <a class="reply"><i class="iconfont icon-xinxi"></i> <span>回复</span></a>
                                <a href="javascript:;" class="report"><span>举报</span></a>
                            </div>
                        </div>
                        <blockquote class="sub-comment-list">
                            <div class="sub-comment" v-for="reply in comment.reply_comments">
                                <p>
                                    <a href="/v1/user" target="_blank">{{ reply.user.name }}</a>：
                                    <span>{{ reply.body }}</span>
                                </p>
                                <div class="sub-tool-group">
                                    <span>{{ reply.created_at }}</span>
                                    <a class="reply"><i class="iconfont icon-xinxi"></i> <span>回复</span></a>
                                    <a href="javascript:;" class="report"><span>举报</span></a>
                                </div>
                            </div>
                            <div class="more-comment">
                              <a class="add-comment-btn">
                                <i class="iconfont icon-xie"></i>
                                <span>添加新评论</span>
                              </a>
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
        <!-- 分页 -->
        <ul class="paging" v-if="lastPage>1">
            <li v-for="page in lastPage" @click="goPage(page)"><a :class="page==currentPage?'active':''">{{ page }}</a></li>
            <li @click="nextPage"><a>下一页</a></li>
        </ul>
    </div>
</template>
<script>
export default {

  name: 'Comments',

  props: ['id', 'type', 'isLogin'],

  mounted() {
    this.loadComments();
  },

  computed: {
    currentUser() {
      return window.user;
    }
  },

  methods: {
    showSend: function(){
        $('.new-comment .write-block').fadeIn();
    },
    hideSend: function(){
        $('.new-comment .write-block').fadeOut('fast');
    },
    get_lou: function(lou) {
      if(lou == 1)
        return '沙发';
      if(lou == 2)
        return '板凳';
      return lou + '楼';
    },
    get_post_url: function() {
        return window.tokenize('/api/comment');
    },
    get_get_url: function() {
      var api_url = '/api/comment/'+ this.id + '/' + this.type;
      if(this.currentPage > 1) {
        api_url += '&page='+ this.currentPage;
      }
      return api_url;
    },
    get_action_url: function(id, action) {
        return window.tokenize('/api/comment/'+ id + '/' + action);
    },
    likeComment: function(comment) {
      comment.liked = 1;
      window.axios.get(this.get_action_url(comment.id, 'like')).then(function(response) {
        comment.likes = response.data.likes;
      });
    },
    unlikeComment: function(comment) {
      comment.liked = 0;
      window.axios.get(this.get_action_url(comment.id, 'like')).then(function(response) {
        comment.likes = response.data.likes;
      });
    },
    reportComment: function(comment) {
      comment.reported = 1;
      window.axios.get(this.get_action_url(comment.id, 'report')).then(function(response) {
        comment.reports = response.data.reports;
      });
    },
    unreportComment: function(comment) {
      comment.reported = 0;
      window.axios.get(this.get_action_url(comment.id, 'report')).then(function(response) {
        comment.reports = response.data.reports;
      });
    },
    loadComments: function() {
      var _this = this;
      window.axios.get(this.get_get_url()).then(function(response) {
        _this.comments = response.data.data;
        _this.lastPage = response.data.last_page;
        _this.total = response.data.total;
      });
    },
    loadMoreComments: function() {      
      if(this.lastPage > this.currentPage) {
        this.currentPage ++;
        this.loadComments();
      }
    },
    postComment: function() {

      if(!this.newComment.is_replay_comment){
        //乐观更新
        this.newComment.lou = this.total + 1;
        var newComment = Object.assign({}, this.newComment);
        newComment.user = this.currentUser;
        this.comments = this.comments.concat(newComment);
      }

      var _this = this;      
      window.axios.post(this.get_post_url(), this.newComment).then(function(response) { 
        //更新被回复的楼中comments...
        if(_this.newComment.is_replay_comment) {
          var commented = response.data;
          _this.commented = commented;
        } else {        
            
            _this.comments.pop();
            _this.comments = _this.comments.concat(response.data);

            //发布一次，清空...
            _this.newComment.body = '';
            _this.newComment.comment_id = null;
            _this.newComment.comment = null;
        }
      });
    },
    replyComment: function(comment) {
      this.newComment.body = '@' + comment.user.name + ': ' ;
      this.newComment.comment_id = comment.id;
      this.newComment.comment = comment;
      this.newComment.is_replay_comment = true;
      this.commented = comment;
    },
    nextPage: function() {
        if(this.currentPage<this.lastPage) {
          this.currentPage++;
          this.loadComments();
        }
    },
    goPage(page) {
      this.currentPage = page;
      this.loadComments();
    }
  },

  data () {
    return {
      currentPage: 1,
      lastPage: null,
      total:0,
      comments: [],
      commented: null,
      newComment : {
        user: this.currentUser,
        is_new: true,
        time: '刚刚',
            body: null,
            commentable_id: this.id,
            commentable_type: this.type,
            comment_id: null,
        comment: null,
        likes: 0,
        reports: 0,
        is_replay_comment: false,
      },
      isSignIn:true,
      sendBox:false
    }
  }
}
</script>
<style lang="scss" scoped>
  .comment-content {
    padding-top: 20px;
    .normal-comment {
      margin-top: 30px;
      .top {
        padding-bottom: 20px;
        font-size: 17px;
        font-weight: 700;
        border-bottom: 1px solid #f0f0f0;
        .btn-base {
          margin: -2px 0 0 10px;
        }
        .pull-right {
          a {
            margin-left: 10px;
            font-size: 12px;
            font-weight: 400;
            color: #969696;
            display: inline-block;
            cursor: pointer;
            &.active {
              color: #252525;
            }
            &:hover {
              color: #252525;
            }
          }
        }
      }
      // 评论列表项
      .comment-item {
        padding: 20px 0 30px;
        border-bottom: 1px solid #f0f0f0;
        .comment-dtl {
          p {
            font-size: 16px;
            margin: 10px 0;
            word-break: break-all;
          }
        } 
      }
      // 回复楼层
      .sub-comment-list {
        margin-bottom: 0;
        color: #969696;
        border-left: 2px solid #c4c4c4;
        .sub-comment {
          margin-bottom: 15px;
          padding-bottom: 15px;
          border-bottom: 1px dashed #f0f0f0;
          color: #252525;
          p {
            margin: 0 0 5px;
            font-size: 14px;
            line-height: 1.5;
            a {
              color:#2B89CA;
            }
          }
        }
        .more-comment {
          font-size: 14px;
          color: #969696;
          i {
            margin-right: 5px;
          }
          .add-comment-btn {
            cursor: pointer;
            &:hover {
              color: #252525;
            }
          }
        }
      } 
    }
    @media screen and (max-width:600px) {
      .normal-comment {
        .top {
          font-size: 15px;
          .btn-base {
            margin-left: 0;
            padding: 0;
            border: none;
          }
          .pull-right {
            a {
              margin-left: 0;
            }
          }
        }
      }
    }
  }
  .write-block {
    display: none;
  }
</style>