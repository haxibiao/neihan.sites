<template>
	<div>
	    <table class="form-menu">
	        <tbody class="reward">
	            <tr>
	                <td class="setting-title pull-left top-line">
	                    赞赏功能
	                </td>
	                <td class="top-line">
	                	<div class="row">
		                    <div class="col-xs-6">
		                        <input type="radio" value="1" v-model="user.enable_tips"> <span>开启</span></div>
		                    <div class="col-xs-18">
		                        <input type="radio" value="0" v-model="user.enable_tips"> <span>关闭</span></div>
	                	</div>
	                    <p>开启后赞赏按钮将出现在你的文章底部</p>
	                </td>
	            </tr>
	            <tr>
	                <td class="setting-title pull-left">赞赏描述</td>
	                <td>
	                    <textarea row="3" placeholder="如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！" v-model="user.tip_words"></textarea>
	                </td>
	            </tr>
	        </tbody>
	    </table>
	    <input type="button" class="btn-base btn-handle btn-bold" value="保存" @click="save">

	    <div class="alert alert-success" v-if="saved">
	    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    	<strong>提示！</strong> 赞赏设置保存成功...
	    </div>
	</div>
</template>

<script>
export default {

  name: 'Reward',

  created() {
  	this.fetchData();
  },

  methods: {  	
  	api() {
  		return window.tokenize('/api/user');
  	},
	fetchData() {
		var _this = this;
		window.axios.get(this.api()).then(function(response){
			_this.user = response.data;
		});
	},
	save() {
		var _this = this;
		var formData = {
			enable_tips: this.user.enable_tips,
			tip_words: this.user.tip_words
		};
		window.axios.post(this.api(), formData).then(function(response){
			_this.user = response.data;
			_this.saved = true;
		});
	}
  },

  data () {
    return {
    	saved: null,
    	user: {},
    }
  }
}
</script>

<style lang="scss" scoped>
	.reward {
		textarea {
			font-size: 15px;
		}
	}
</style>