<template>
<div>
	
	<!-- 评论列表 -->
	<div class="panel panel-default" v-for="comment in comments">
    <div class="panel-heading">
      <div class="pull-right" v-if="!comment.is_new">
        <button type="button" class="btn　btn-sm btn-default" @click="replyComment(comment)">回复</button>

        <span v-if="!comment.liked" class="icon iconfont icon-dianzan3" @click="likeComment(comment)"><span>{{ comment.likes }}</span></span>
        <span v-else class="icon iconfont icon-dianzan" @click="unlikeComment(comment)"><span>{{ comment.likes }}</span></span>　
        
        <span v-if="!comment.reported" class="icon iconfont icon-dianzan1" @click="reportComment(comment)"><span>{{ comment.reports }}</span></span>
        <span v-else class="icon iconfont icon-zan2" @click="unreportComment(comment)"><span>{{ comment.reports }}</span></span>
      </div>
      {{　get_lou(comment.lou) }} 
      <a :href="'/user/' + comment.user.id">
      <img :src="comment.user.picture" class="img img-circle" style="max-width:30px" />
      </a>
      <a :href="'/user/' + comment.user.id">
      {{ comment.user.name }}</a>  
      <span class="small"> / {{ comment.created_at_cn }}</span>
    </div>
    <div class="panel-body">
      {{ comment.body }}
      <p v-if="comment.comment" class="well">
        <a :href="'/user/' + comment.comment.user.id">{{ comment.comment.user.name }}</a>: {{ comment.comment.body }}
      </p>
    </div>
  </div>
  <p>
    <button v-if="lastPage　>　currentPage" type="button" class="btn btn-default" @click="loadMoreComments">加载更多...</button>
  </p>
	
	<!-- 发布评论 -->
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="form-group">
        <label for="textarea" class="col-sm-2 control-label">评论:</label>
        <div class="col-sm-10">
          <textarea name="" id="textarea" class="form-control" rows="3" required="required" v-model="newComment.body"></textarea>
        </div>
      </div>
      <div class="pull-right top10">
        <button type="button" class="btn btn-danger" @click="postComment">提交评论</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {

  name: 'Comment',

  props: ['id', 'type', 'username'],

  mounted() {
    this.loadComments();
  },

  methods: {
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
      var api_url = window.tokenize('/api/comment/'+ this.id + '/' + this.type);
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
      this.$http.get(this.get_action_url(comment.id, 'like')).then(function(response) {
        comment.likes = response.data.likes;
      });
    },
    unlikeComment: function(comment) {
      comment.liked = 0;
      this.$http.get(this.get_action_url(comment.id, 'like')).then(function(response) {
        comment.likes = response.data.likes;
      });
    },
    reportComment: function(comment) {
      comment.reported = 1;
      this.$http.get(this.get_action_url(comment.id, 'report')).then(function(response) {
        comment.reports = response.data.reports;
      });
    },
    unreportComment: function(comment) {
      comment.reported = 0;
      this.$http.get(this.get_action_url(comment.id, 'report')).then(function(response) {
        comment.reports = response.data.reports;
      });
    },
    loadComments: function() {
      var vm = this;
      this.$http.get(this.get_get_url()).then(function(response) {
        vm.comments = vm.comments.concat(response.data.data);
        vm.lastPage = response.data.last_page;
        vm.total = response.data.total;
      });
    },
    loadMoreComments: function() {      
      if(this.lastPage > this.currentPage) {
        this.currentPage ++;
        this.loadComments();
      }
    },
  	postComment: function() {
      //乐观更新
      this.newComment.lou = this.total + 1;
      this.newComment.user = {
          name: this.username
      };
      var item = Object.assign({}, this.newComment);
      this.comments = this.comments.concat(item);

      var vm = this;      
  		this.$http.post(this.get_post_url(), this.newComment).then(function() {
        // vm.loadComments();        
        //发布一次，清空...
        vm.newComment.body = '';
        vm.newComment.comment_id = null;
        vm.newComment.comment = null;
  		});
  	},
    replyComment: function(comment) {
      this.newComment.body = '回复' + comment.user.name + ': ' ;
      this.newComment.comment_id = comment.id;
      this.newComment.comment = comment;
    }
  },

  data () {
    return {
      currentPage: 1,
      lastPage: null,
      total:0,
      comments: [],
    	newComment : {
        is_new: true,
        created_at_cn: '刚刚',
    		body: null,
    		object_id: this.id,
    		type: this.type,
    		comment_id: null,
        comment: null,
        likes: 0,
        reports: 0
    	}
    };
  }
};
</script>

<style lang="css" scoped>
</style>