<template>
  <div class="video-like">
      <div v-if="isLogin" :class="['btn-like',isLiked?'btn-select':'btn-noselect']" @click="toggle_like">
        <a>
          <i class="iconfont icon-03xihuan"></i>
        </a>
      </div>
      <a v-else href="/login">
        <div class="btn-like">
          <a>
            <i class="iconfont icon-03xihuan"></i>
          </a>      
        </div>
      </a>
      <div class="modal-wrap" >
        <a data-target=".like-user" data-toggle="modal">{{ likesTotal }}</a>
      </div>
    <modal-like-user v-if="isLogin" :id="id" :type="type" :likes="likes"></modal-like-user>
  </div>
</template>

<script>
export default {

  name: 'Like',

  props: ['id', 'type', 'isLogin', 'liked'],

  computed: {
    isLiked() {
      return this.is_liked !== null ? this.is_liked : this.liked ;
    }
  },

  created() {
    this.fetchData();
  },

  methods: {
  	api() {
      var api_url = '/api/like/' + this.id + '/' + this.type;
  		return window.tokenize ? window.tokenize(api_url) : api_url + '/guest';
  	},
    fetchData() {
      var _this = this;
      window.axios.get(this.api()).then(function(response) {
        _this.is_liked = response.data.is_liked;
        _this.likes = response.data.likes.data;
        _this.likesTotal = response.data.likes.total;
      });
    },
    toggle_like() {
      //乐观更新UI
      this.is_liked = !this.is_liked;
      this.is_liked ? this.likesTotal ++ : this.likesTotal --;
      
  		var _this = this;
      window.axios.post(this.api()).then(function(response) {
        _this.is_liked = response.data.is_liked;
        _this.likes = response.data.likes.data;
        _this.likesTotal = response.data.likes.total;
      });
  	}
  },

  data () {
    return {
      is_liked: null,
      likesTotal: 0,
    	likes: [],
    };
  }
};
</script>

<style lang="css" scoped>
</style>