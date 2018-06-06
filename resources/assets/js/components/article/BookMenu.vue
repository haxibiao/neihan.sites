<template>
	<div class="book-menu">
	    <div class="menu-item">
	        <a :href="'/article/'+prevArticleId">
	          <i class="iconfont icon-zuobian"></i>上一篇
	        </a>
	    </div>
	    <div class="menu-item">
	      <a id="openBookChapterBtn" href="javascript:;">目录</a>
	    </div>
	    <div class="menu-item">
	        <a :href="'/article/'+nextArticleId">
	          下一篇<i class="iconfont icon-youbian"></i>
	        </a>
	    </div>
	  </div>
</template>

<script>
export default {

  name: 'BookMenu',

  props:["currentArticleId","prevArticleId","nextArticleId"],

  data () {
    return {

    }
  }
}
</script>

<style lang="scss">
.book-menu {
	margin: 40px 0 20px;
	height: 50px;
	font-size: 0;
	border: 1px solid #e0e0e0;
	border-radius: 5px;
	background-color: hsla(0,0%,71%,.1);
	overflow: hidden;
	.menu-item {
		display: inline-block;
		height: 100%;
		width: 33.33%;
		line-height: 48px;
		text-align: center;
		font-size: 16px;
		color: #333;
		border-right: 1px solid #e0e0e0;
		&:last-of-type {
			border-right: none;
		}
		a {
			display: inline-block;
		    width: 100%;
		    color: #333;
		}
	}
}
</style>