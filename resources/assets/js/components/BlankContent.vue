<template>
	<div class="blank-content">
		<div class="tip">
			<p>这里还木有内容哦~</p>
			<span class="iconfont icon-meiyouxiaoxi1"></span>
		</div>
	</div>
</template>

<script>
export default {

  name: 'BlankContent',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.blank-content {
	width: 100%;
	height: 100%;
	min-height: 300px;
	text-align: center;
	color: #969696;
	font-size: 14px;
	.tip {
		span {
			font-size: 100px;
			color: #969696;
		}
	}
}
</style>