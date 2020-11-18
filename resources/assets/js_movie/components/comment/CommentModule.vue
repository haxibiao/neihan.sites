<template>
  <div id="comment_module" class="comment-wrapper">
    <div class="module-head">
      <span class="count_comment">2365</span>
      <span>评论</span>
    </div>
    <div class="comment-container">
      <div class="comment-box">
        <div class="comment-header clearfix">
          <div class="tabs-order">
            <ul class="clearfix">
              <li data-sort="1" class="hot-sort on">按热度排序</li>
              <li data-sort="2" class="new-sort">按时间排序</li>
            </ul>
          </div>
        </div>
        <comment-send />
        <div class="comment-list">
          <!-- <comment-item /> -->
        </div>
        <!-- <div class="bottom-page paging-box-big">
          <span class="disabled">上一页</span>
          <span class="current">1</span>
          <a href="javascript:;" class="tcd-number">2</a>
          <a href="javascript:;" class="tcd-number">3</a>
          <a href="javascript:;" class="tcd-number">4</a>
          <span class="dian">...</span>
          <a href="javascript:;" class="tcd-number">79</a>
          <a href="javascript:;" class="next">下一页</a>
          <div class="page-jump">
            共
            <span>79</span>页，跳至
            <input type="text" />页
          </div>
        </div>-->
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.comment-wrapper {
  padding-top: 20px;
  border-top: 1px solid #e5e9ef;
}
.module-head {
  font-size: 18px;
  line-height: 24px;
  color: #222;
  margin-bottom: 20px;
  .count_comment {
    margin-right: 10px;
  }
}
.comment-container {
  position: relative;
}
.comment-box {
  font-family: Microsoft YaHei, Arial, Helvetica, sans-serif;
  font-size: 0;
  zoom: 1;
  min-height: 100px;
  background: #fff;
}
.comment-header {
  margin: 0 0 24px;
  border-bottom: 1px solid #e5e9ef;
}
.tabs-order {
  li {
    background-color: transparent;
    border-radius: 0;
    border: 0;
    padding: 8px 0;
    margin-right: 16px;
    border-bottom: 1px solid transparent;
    position: relative;
    float: left;
    cursor: pointer;
    line-height: 20px;
    font-size: 14px;
    font-weight: 700;
    color: #222;
    &.on {
      border-bottom: 1px solid #29b6f6;
      color: #29b6f6;
    }
  }
  .hot-sort {
  }
  .new-sort {
  }
}
</style>
