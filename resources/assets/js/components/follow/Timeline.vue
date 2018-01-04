<template>
    <!-- 朋友圈 -->
    <!-- TODO::算法比较随机,还待调整 -->
	<div id="friend">
                <article-list api="/" start-page="2" />
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