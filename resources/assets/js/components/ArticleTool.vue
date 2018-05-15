<template>
    <ul>
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
    </ul>
</template>

<script>
export default {

  name: 'ArticleTool',

  props: ['id','isSelf','isLogin'],

  mounted() {
    this.fetchData();
  },

  methods: {
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
    }
  },

  data () {
    return {
    	favorited:false
    }
  }
}
</script>

<style lang="scss" scoped>

</style>