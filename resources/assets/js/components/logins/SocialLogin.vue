<template>
	<ul class="social-login">
	    <li><a class="weibo-bg" target="_blank" href=""><i class="iconfont icon-sina"></i></a></li>
	    <li><a class="weixin-bg" target="_blank" href=""><i class="iconfont icon-weixin1"></i></a></li>
	    <li><a class="qq-bg" target="_blank" href=""><i class="iconfont icon-qq2"></i></a></li>
	    <li class="more-method"><a href="javascript:void(0);" class="other-bg"><i class="iconfont icon-gengduo"></i></a></li>
	    <li class="hide-method hide"><a class="git" target="_blank" href="javascript:;"><i class="iconfont icon-Git"></i></a></li>
	</ul>
</template>

<script>
export default {

  name: 'SocialLogin',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.social-login {
	margin-bottom: 30px;
	li {
		margin: 0 3px;
		display: inline-block;
		a {
			width: 50px;
			height: 50px;
			border-radius: 50%;
			line-height: 50px;
			display: block;
			i {
				color: #fff;
				font-size: 24px;
			}
		}
	}
}
</style>