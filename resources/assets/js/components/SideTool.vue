<template>
    <ul v-if="id?true:(isArticle?false:true)">
        <li id="goTop" data-placement="left" data-toggle="tooltip" data-container="body" data-title="回到頂部">
          <a class="function_button" @click="goTop"><i class="iconfont icon-xiangshangjiantou"></i></a>
        </li>
        <template v-if="id">
          <li v-if="isLogin && isSelf" data-placement="left" data-toggle="tooltip" data-container="body" data-title="文章投稿">
            <a class="js-submit-button" data-target=".modal-category-contribute" data-toggle="modal"><i class="iconfont icon-tougaoguanli"></i></a>
          </li>
          <li v-if="isLogin && !isSelf" data-placement="left" data-toggle="tooltip" data-container="body" data-title="將文章加入專題">
          	<a class="submit-button" data-target=".modal-add-category" data-toggle="modal"><i class="iconfont icon-jia1"></i></a>
          </li>
          <li v-if="isLogin" data-placement="left" data-toggle="tooltip" data-container="body" 
          :data-title="favorited?'取消收藏文章':'收藏该文章'" @click="toggle">
          	<a class="function_button"><i :class="['iconfont',favorited?'icon-shoucang1':'icon-shoucang']"></i></a>
          </li>
          <li>
          	 <slot></slot>
          </li>
        </template>
    </ul>
</template>

<script>
export default {

  //网站的aside-tool 根据props判断tool类型

  name: 'SideTool',

  props: ['id','isSelf','isLogin'],

  mounted() {
    //判断是否为文章详情页
    this.isArticle = location.pathname.slice(1,8) == "article";
    this.listen();
    this.fetchData();
    var that = this;
    $(window).on("scroll",function () {
       that.listen();
    });
  },

  methods: {
    goTop() {
      $("body,html").animate({"scrollTop": 0 }, 1000);
    },
    listen() {
      if($(window).scrollTop()>1000) {
          $("#goTop").fadeIn(300);
      }else {
          $("#goTop").fadeOut(300);
      }
    },
    api() {
      return window.tokenize('/api/favorite/' + this.id + '/articles');
    },
    toggle: function() {
      if(window.tokenize){
        var vm = this;
        window.axios.post(this.api())
          .then(function(response) {
          vm.favorited  = response.data;
        });
      }
    },
    fetchData() {
      if(window.tokenize){
        var vm = this;
        window.axios.get(this.api()).then(function(response){
          vm.favorited = response.data
        });
      }
    },
  },

  data () {
    return {
    	favorited:false,
      isArticle: false
    }
  }
}
</script>

<style lang="scss" scoped>

</style>