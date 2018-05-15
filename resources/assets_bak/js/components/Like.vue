<template>
             <div class="like">
            <div :class="liked?'btn_base btn_like_group btn_like_group_active':'btn_base btn_like_group'">
              <div class="btn_like">
                  <a v-if="isLogin" @click="toggle_like">
                      <i :class="liked ? 'iconfont icon-03xihuan' : 'iconfont icon-xin' ">
                      </i>
                      喜欢
                  </a>

                  <a v-if="!isLogin" href="/login">
                      <i class="iconfont icon-xin">
                      </i>
                      喜欢
                  </a>
              </div>
              <div class="modal_wrap">
                  <a href="javascrip:;">
                      {{ likes }}
                  </a>
              </div>
            </div>
        </div>
</template>

<script>
export default {
  name: "Like",

  props: ["id", "type", "isLogin", "articleLikes"],

  created() {
    this.likes = this.articleLikes;
    this.get();
  },

  methods: {
    toggle_like() {
      var vm = this;
      var api_url = window.tokenize("/api/like/" + this.id + "/" + this.type);
      this.$http.post(api_url).then(function(response) {
        vm.liked = response.data.is_liked;
        vm.likes = response.data.likes;
      });
    },
    get() {
      var vm = this;
      var api = window.tokenize("/api/like/" + this.id + "/" + this.type);
      window.axios.get(api).then(function(response) {
        vm.liked = response.data.likes;
      });
    }
  },

  data() {
    return {
      liked: false,
      likes: 0
    };
  }
};
</script>

<style lang="css" scoped>
</style>
