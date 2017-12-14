<template>
<!-- 	<div v-if="!collection" class="loading">
		正在加载....
	</div> -->
	<!-- 关注的文集 -->
	<div  id="collection">
		<div class="main_top clearfix">
            <a class="avatar avatar_collection" href="/v1/category" target="_blank">
                <img src="/images/category_09.png"/>
            </a>
            <a class="botm" href="/v1/category" target="_blank">
                <span>
                	文集主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <div class="title">
	            <a class="name" href="/v1/category" target="_blank">
	                <span>
	                    王者荣耀
	                </span>
	            </a>
	        </div>
            <p>
            	<a href="/v1/user" target="_blank">爱你城精选</a>
                 编 · 收录了140029篇文章 · 1405173人关注
            </p>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新收录</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                        <i class="iconfont icon-svg37">
                        </i>
                        <span>最新评论</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                        <i class="iconfont icon-huo">
                        </i>
                        <span>热门</span>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="wenzhang" role="tabpanel">
                    <ul class="article_list">
                        <article-list api="/" />
					</ul>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
                        <article-list api="/" />
					</ul>

                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
                        <article-list api="/" />
					</ul>

                </div>
            </div>
        </div>
	</div>
</template>

<script>
export default {

  name: 'Collection',

  data () {
    return {
        collection:null
    }
  }
}
</script>

<style lang="css" scoped>
</style>