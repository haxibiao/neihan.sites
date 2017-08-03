<template>
<div>
	
	<!-- 评论列表 -->
	<div class="panel panel-default" v-for="comment in comments">
    <div class="panel-heading">
    <div class="pull-right">
      <button type="button" class="btn　btn-sm btn-default" @click="replyComment(comment)">回复</button>
      <button type="button" class="btn　btn-sm btn-success" @click="likeComment(comment)">点赞</button>
      <span>点赞数：{{ comment.likes }}</span>
      <button type="button" class="btn　btn-sm btn-danger" @click="reportComment(comment)">举报</button>
      <span>举报数：{{ comment.reports }}</span>
    </div>
      <h3 class="panel-title" style="line-height: 30px">{{　comment.lou }}楼：{{ comment.user.name }}</h3>
    </div>
    <div class="panel-body">
      {{ comment.body }}
      <p v-if="comment.comment" class="well">
        {{ comment.comment.body }}
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

  props: ['id', 'type'],

  mounted() {
    this.loadComments();
  },

  methods: {
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
      this.$http.get(this.get_action_url(comment.id, 'like')).then(function(response) {
        comment.likes = response.data.likes;
      });
    },
    reportComment: function(comment) {
      this.$http.get(this.get_action_url(comment.id, 'report')).then(function(response) {
        comment.reports = response.data.reports;
      });
    },
    loadComments: function() {
      var vm = this;
      this.$http.get(this.get_get_url()).then(function(response) {
        // vm.comments = vm.comments.concat(response.data.data).unique();
        //用下最朴素的排除重复，更新算法最好用.
        for(var i in response.data.data) {
          var item  =response.data.data[i];
          var exist = false;
          for(var c in vm.comments) {
            var cm = vm.comments[c];
            if(cm.id == item.id) {
              exist = true;
            }
          }
          if(!exist) {
            vm.comments.push(item);
          }
        }
        vm.lastPage = response.data.last_page;
      });
    },
    loadMoreComments: function() {      
      if(this.lastPage > this.currentPage) {
        this.currentPage ++;
        this.loadComments();
      }
    },
  	postComment: function() {
      var vm = this;
  		this.$http.post(this.get_post_url(), this.newComment).then(function() {
        vm.loadComments();
        //发布一次，清空...
        vm.newComment.body = '';
        vm.newComment.comment_id = null;
  		});
  	},
    replyComment: function(comment) {
      this.newComment.body = '回复' + comment.user.name + ': ' ;
      this.newComment.comment_id = comment.id;
    }
  },

  data () {
    return {
      currentPage: 1,
      lastPage: null,
      comments: [],
    	newComment : {
    		body: null,
    		object_id: this.id,
    		type: this.type,
    		comment_id: null,
    	}
    };
  }
};
</script>

<style lang="css" scoped>
</style>