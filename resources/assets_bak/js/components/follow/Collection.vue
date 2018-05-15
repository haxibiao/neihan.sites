<template>
<!-- 	<div v-if="!collection" class="loading">
		正在加载....
	</div> -->
	<!-- 关注的文集 -->
	<div  id="collection">
		<div class="main_top">
            <a class="avatar avatar_lg avatar_collection" href="/v2/category/2">
                <img src="/images/category_09.png"/>
            </a>
            <a class="btn_base btn_hollow" href="#">
                <span>
                    文集主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <div class="info_meta">
                <a class="headline nickname" href="/v2/category/2">
                    <span class="single_line">王者荣耀</span>
                </a>
                <p class="info_count">
                    <a href="/v2/user" target="_blank">爱你城精选</a>
                 编 · 收录了140029篇文章 · 1405173人关注
                </p>
            </div>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新<span class="s_s_hide">收录</span></span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                        <i class="iconfont icon-svg37">
                        </i>
                        <span><span class="s_s_hide">最新</span>评论</span>
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

<style lang="scss" scoped>
    #collection {
        .main_top {
            @media screen and (max-width: 768px) {
                padding-bottom: 20px;
                .btn_base {
                    position: absolute;
                    top: 148px;
                    right: 70px;
                }
            }
        }
        @media screen and (max-width: 768px) {
            .trigger_menu {
                li {
                    a {
                        padding: 13px 4px 4px 4px;
                        i {
                            margin: 0;
                        }
                    }
                }
            }
        }
    }
</style>