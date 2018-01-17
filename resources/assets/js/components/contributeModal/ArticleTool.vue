<template>
	 <!-- 底部小火箭、分享、收藏、投稿  -->
    <ul>
        <li data-container="body" data-original-title="将文章加入专题" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_user" data-toggle="modal" href="#" class="js_submit_button">
                <i class="iconfont icon-jia1">
                </i>
            </a>
        </li>
        <li data-container="body" data-original-title="文章投稿" data-placement="left" data-toggle="tooltip" data-trigger="hover">
            <a data-target="#detailModal_home" data-toggle="modal" href="#" class="js_submit_button">
                <i class="iconfont icon-tougaoguanli">
                </i>
            </a>
        </li>

        <li data-container="body" :data-original-title="favorited?'取消收藏文章':'收藏该文章'" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="toggle">
            <a href="javascript:;" class="function_button">
                <i :class="['iconfont',favorited?'icon-shoucang1':'icon-shoucang']">
                </i>
            </a>
        </li>
        <li>
            <share class="function_button" placement="left"></share>
        </li>
    </ul>
</template>
<script>
export default {

  name: 'ArticleTool',

  props:['id'],

  mounted(){
      this.fetchData();
  },

  methods:{
  	   api(){
  	   	 return window.tokenize('/api/favorite/' + this.id + '/articles');
  	   },

  	   fetchData(){
  	   	  var vm=this;
  	   	  window.axios.get(this.api()).then(function(response){
  	   	  	  vm.favorited=response.data;
  	   	  });
  	   },

       toggle(){
       	  var vm=this;
       	  window.axios.post(this.api()).then(function(response){
       	  	   vm.favorited=response.data;
       	  });
       }
  },

  data () {
    return {
        favorited:false
    }
  }
}
</script>

<style lang="css" scoped>
</style>