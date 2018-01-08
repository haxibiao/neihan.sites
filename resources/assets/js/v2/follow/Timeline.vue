<template>
    <!-- 朋友圈 -->
    <!-- TODO::算法比较随机,还待调整 -->
	<div id="timeline">
        <ul class="article_list">
            <li class="article_item have_img">
                <a class="wrap_img" href="/v2/detail" target="_blank">
                    <img src="/images/details_07.jpg"/>
                </a>
                <div class="content">
                    <div class="author">
                        <a class="avatar" href="/v2/user" target="_blank">
                            <img src="/images/photo_01.jpg"/>
                        </a>
                        <div class="info_meta">
                            <a href="/v2/user" target="_blank" class="nickname">
                                喵星菇凉
                            </a>
                            <span class="time">
                                发表了文章 · 06.15.12:12
                            </span>
                        </div>
                    </div>
                    <a class="headline paper_title" href="/v2/detail" target="_blank">
                        <span>魔兽世界7.2.5全新版本资料片：新橙戒+新黑庙+乐队活动</span>
                    </a>
                    <p class="abstract">
                        《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的《魔兽世界》7.2.5版本今日在国服上线。虽然是小版本更新，但改动内容还是十分丰富的。比如新的橙装、大秘境调整、黑庙加入时空漫游、克罗米战役等等。以下是17173为各位整理的
                    </p>
                    <div class="meta">
                        <a href="/v2/detail" target="_blank" class="count count_link">
                            <i class="iconfont icon-liulan">
                            </i>
                            717
                        </a>
                        <a href="/v2/detail" target="_blank" class="count count_link">
                            <i class="iconfont icon-svg37">
                            </i>
                            6
                        </a>
                        <span class="count">
                            <i class="iconfont icon-03xihuan">
                            </i>
                            13
                        </span>
                    </div>
                </div>
            </li>
        </ul>
        <a class="load_more" href="#">
            阅读更多
        </a>
    </div>
</template>

<script>
export default {

  name: 'Timeline',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>