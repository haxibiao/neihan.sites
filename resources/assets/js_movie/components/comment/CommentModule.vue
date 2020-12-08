<template>
  <div id="comment_module" class="comment-wrapper">
    <div class="module-head">
      <span class="count_comment">{{ countComment || 0 }}</span>
      <span>评论</span>
    </div>
    <div class="comment-container">
      <div class="comment-box">
        <div class="comment-header clearfix">
          <div class="tabs-order">
            <ul class="clearfix">
              <li
                      data-order="1"
                      :class="['hot-order', order == 'like_count' && 'on']"
                      v-on:click="changeCommentsOrder('like_count')"
              >按热度排序</li>
              <li
                      data-order="2"
                      :class="['hot-order', order == 'id' && 'on']"
                      v-on:click="changeCommentsOrder('id')"
              >按时间排序</li>
            </ul>
          </div>
        </div>
        <comment-send ref="commentSend" @submitComment="createComment" />
        <div class="comment-list">
          <comment-item v-for="comment in commentData" :key="comment.id" :comment="comment" />
        </div>
        <pagination
                :count="Number(countComment)"
                :offset="Number(pageOffset)"
                :current.sync="currentPage"
        />
        <!-- <comment-send v-if="countComment>10" ref="commentSend" @submitComment="createComment" /> -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['movieId', 'countComment', 'pageOffset'],
    created() {
      this.fetchData();
    },
    computed: {
      countPage() {
        return Math.ceil(this.countComment / this.pageOffset);
      }
    },
    methods: {
      // 排序
      changeCommentsOrder(order) {
        this.order = order;
        this.fetchData();
      },
      // 获取评论数据
      fetchData() {
        const that = this;
        window.axios
                .get(
                        `/api/movie/${that.movieId}/comment?page=${this.currentPage}&order=${this.order}`,
                        {
                          headers: {
                            token: this.$user.token
                          }
                        }
                )
                .then(function(response) {
                  const data = that.$optional(response, 'data.data');
                  if (data) {
                    that.commentData = data;
                  }
                })
                .catch(e => {});
      },
      //写新评论
      createComment(body) {
        const that = this;
        window.axios
                .post(
                        `/api/movie/comment/store`,
                        {
                          movie_id: that.movieId,
                          content: body
                        },
                        {
                          headers: {
                            token: this.$user.token
                          }
                        }
                )
                .then(function(response) {
                  if (response && response.data) {
                    const newComment = that.$optional(response, 'data.data');
                    if (newComment) {
                      newComment.user = that.$user;
                      that.commentData = [newComment, ...that.commentData];
                    }
                  }
                })
                .catch(e => {})
                .then(function submitted() {
                  that.$refs.commentSend.submitted();
                });
      }
    },
    watch: {
      currentPage(newV, oldV) {
        if (newV) {
          this.fetchData();
        }
      }
    },
    data() {
      return {
        order: 'like_count',
        currentPage: 1,
        commentData: []
      };
    }
  };
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
    .hot-order {
    }
    .new-order {
    }
  }
  .comment-list {
    padding: 20px 0;
  }
</style>
