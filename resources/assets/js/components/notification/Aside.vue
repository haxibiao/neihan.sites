<template>
  <ul class="aside notification-aside">    
    <router-link to="/comments" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-xinxi"></i> <span class="hidden-xs">评论</span>
      <span　v-if="unreads.comments" class="badge">{{ unreads.comments }}</span></a>
    </router-link>
  
    <router-link to="/chats" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-email"></i> <span class="hidden-xs">消息</span>
      <span　v-if="unreads.chats" class="badge">{{ unreads.chats }}</span></a>
    </router-link>
  
    <router-link to="/requests" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-tougaoguanli"></i> <span class="hidden-xs">投稿请求</span>
      <span　v-if="unreads.requests" class="badge">{{ unreads.requests }}</span></a>
    </router-link>
  
    <router-link to="/likes" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-xin"></i> <span class="hidden-xs">喜欢和赞</span>
      <span　v-if="unreads.likes" class="badge">{{ unreads.likes }}</span></a>
    </router-link>

    <router-link to="/follows" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-jiaguanzhu"></i> <span class="hidden-xs">关注</span>
      <span　v-if="unreads.follows" class="badge">{{ unreads.follows }}</span></a>
    </router-link>
  
    <router-link to="/tips" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-zanshangicon"></i> <span class="hidden-xs">赞赏</span>
      <span　v-if="unreads.tips" class="badge">{{ unreads.tips }}</span></a>
    </router-link>
  
    <router-link to="/others" tag="li" active-class="active">
    <a href="javascript:;" class="link"><i class="iconfont icon-gengduo"></i> <span class="hidden-xs">其他消息</span>
      <span　v-if="unreads.others" class="badge">{{ unreads.others }}</span></a>
    </router-link>
    
  </ul>
</template>

<script>
export default {
  name: "Aside",

  created() {
    var route_path = window.location.hash.replace("#/", "");
    this.route_path = route_path;
    if (route_path == "") {
      this.$router.push({ path: "/comments" });
      this.route_path = "comments";
    }
    this.fetchData();
  },

  methods: {
    skip: function(e) {
      $(e.target)
        .parents("li")
        .addClass("active")
        .siblings()
        .removeClass("active");
    },
    fetchData() {
      var api = window.tokenize("/api/unreads");
      var vm = this;
      window.axios.get(api).then(function(response) {
        vm.unreads = response.data;
      });
    }
  },

  data() {
    return {
      route_path: "comments",
      unreads: []
    };
  }
};
</script>
