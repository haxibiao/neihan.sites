<template>
<div>
	
	<!-- 评论列表 -->
	
	
	<!-- 发布评论 -->
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
</template>

<script>
export default {

  name: 'Comment',

  props: ['id', 'type'],

  methods: {
  	get_post_url: function() {
  		return window.tokenize('/api/comment');
  	},
  	get_get_url: function() {
  		return window.tokenize('/api/comment/'+ this.id + '/' + this.type);
  	},
  	postComment: function() {
  		this.$http.post(this.get_post_url(), this.newComment).then(function() {

  		});
  	}
  },

  data () {
    return {
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