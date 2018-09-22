<template>
	<div>
		<form class="new-comment">
			<a class="avatar"><img :src="isLogin?user.avatar:'/images/user.png'"></a>
			<div v-if="isLogin" >
				<div placeholder="写下你的评论..." @click="showSend" @keyup.ctrl.enter="send" @input="setComment" id="editComment" class="commenTable" contentEditable="true"></div>
				<transition name="fade">
					<div v-if="showButton" class="write-block">
						<div class="emoji-wrap">
							<a class="emoji"><i class="iconfont icon-smile"></i></a>
						</div>
						<div class="hint">Ctrl+Enter 发表</div>
						<a class="btn-base btn-handle btn-md" :class="{'disable':!newComment}" @click="send">发送</a>
						<a class="cancel" @click="hideSend">取消</a>
					</div>
				</transition>
			</div>
			<div v-else>
				<div class="sign-container">
			    <a href="/login" class="btn-base btn-handle btn-md">登录</a>
			    <span>后发表评论</span>
				</div>
			</div>
		</form>
	</div>
</template>

<script>
export default {
	name: "NewComment",

	computed: {
		user() {
			return window.user;
		},
		isLogin() {
			return window.user !== undefined;
		}
	},

	methods: {
		send() {
			if (!this.newComment) {
				return null;
			}

			//防止XSS 数据格式化 替换成at标签
        	var reg = /<span class="atwho-inserted" data-atwho-at-query="@(.*?)" contenteditable="false"><span data-id="(\d+)">@(.*?)<\/span><\/span>/g;
        	this.newComment = this.newComment.replace(reg,'<at href="/user/$2" data-id="$2">@$3</at>');

			this.$emit("sendComment", this.newComment);
			//清空掉评论框内容
			$('#editComment').empty();
			this.newComment = null;
		},
		setComment:function(){
			this.newComment = $('#editComment').html();
		},
		showSend: function() {
			this.showButton = true;
		},
		hideSend: function() {
			this.showButton = false;
		}
	},

	data() {
		return {
			newComment: null,
			showButton: false
		};
	}
};
</script>

<style lang="scss" scoped>
.fade-enter-active {
	transition: opacity 0.3s;
}
.fade-leave-active {
	transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
	opacity: 0;
}
</style>
