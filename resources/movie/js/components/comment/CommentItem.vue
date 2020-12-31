<template>
  <div class="comment-item" :data-id="comment.id">
    <div class="user-face">
      <a href="/user/userId"   :data-usercard-mid="comment.user.id">
        <div class="app-avatar">
          <img class="app-avatar-img app-avatar-img-radius" :src="comment.user.avatar" alt="头像" />
        </div>
      </a>
    </div>
    <div class="comment-body">
      <div class="user">
        <a
                data-usercard-mid="9406473"
                href="/user/userId"
                 
                class="name vip-color"
        >{{comment.user.name}}</a>
      </div>
      <p class="text">{{comment.content}}</p>
      <div class="info">
        <span class="time">{{comment.time_ago}}</span>
        <span :class="['like', comment.is_Liked && 'liked']" v-on:click="likeHandler">
          <i :class="['iconfont', comment.is_Liked ? 'icon-good-fill' : 'icon-good']"></i>
          <span>{{comment.like_count}}</span>
        </span>
        <!-- <span class="reply btn-hover btn-highlight">回复</span> -->
        <!-- <div class="operation more-operation">
          <div class="spot"></div>
          <div class="opera-list">
            <ul>
              <li class="blacklist">加入黑名单</li>
              <li class="report">举报</li>
            </ul>
          </div>
        </div>-->
      </div>
      <!-- <div class="reply-box"></div> -->
    </div>
  </div>
</template>

<script>
  export default {
    props: ['comment'],
    mounted() {},
    methods: {
      toggleLike() {
        this.comment.is_Liked
                ? this.comment.like_count--
                : this.comment.like_count++
        this.comment.is_Liked = !this.comment.is_Liked
      },
      likeHandler() {
        if (!this.$user.token) {
          $('#login-modal').modal('toggle')
          return
        }
        const that = this
        this.toggleLike()
        window.axios
                .post(
                        `/api/movie/toggle-like`,
                        {
                          movie_id: that.comment.id,
                          type: 'comments'
                        },
                        {
                          headers: {
                            token: that.$user.token
                          }
                        }
                )
                .then(function(response) {
                  if (response && response.data) {
                    //   点赞成功
                  } else {
                    that.toggleLike()
                  }
                })
                .catch(e => {
                  that.toggleLike()
                })
      }
    },
    data() {
      return {}
    }
  }
</script>

<style lang="scss" scoped>
</style>
