<template>
	 <!-- 底部小火箭、分享、收藏、投稿  -->
  <div>
		<div class="side_tool">
		    <ul>
		        <li class="toup_rocket" data-container="body" data-title="回到顶部" data-placement="left" data-toggle="tooltip" data-trigger="hover">
		            <a href="javascript:;">
		                <i class="iconfont icon-xiangxiajiantou-copy">
		                </i>
		            </a>
		        </li>

		        <li data-container="body" data-original-title="将文章加入专题" data-placement="left" data-toggle="tooltip" data-trigger="hover">
		            <a data-target="#detailModal_user" data-toggle="modal" href="#">
		                <i class="iconfont icon-jia1">
		                </i>
		            </a>
		        </li>

		        <li data-container="body" data-original-title="文章投稿" data-placement="left" data-toggle="tooltip" data-trigger="hover">
		            <a data-target="#detailModal_home" data-toggle="modal" href="#">
		                <i class="iconfont icon-tougaoguanli">
		                </i>
		            </a>
		        </li>

		        <li data-container="body" :data-original-title="favorited?'取消收藏文章':'收藏该文章'" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="toggle">
		            <a href="javascript:;">
		                <i :class="['iconfont',favorited?'icon-shoucang1':'icon-shoucang']">
		                </i>
		            </a>
		        </li>

		        <li class="share" data-container="body" data-original-title="分享文章" data-placement="left" data-toggle="tooltip">
		            <a data-toggle="dropdown" href="javascript:;">
		                <i class="iconfont icon-fenxiang1">
		                </i>
		            </a>
		            <ul class="dropdown-menu">
		                <li>
		                    <a href="#">
		                        <i class="iconfont icon-weixin1">
		                        </i>
		                        <span>
		                            分享到微信
		                        </span>
		                    </a>
		                </li>
		                <li>
		                    <a href="#">
		                        <i class="iconfont icon-sina">
		                        </i>
		                        <span>
		                            分享到微博
		                        </span>
		                    </a>
		                </li>
		                <li>
		                    <a href="#">
		                        <i class="iconfont icon-zhaopian">
		                        </i>
		                        <span>
		                            下载长微博图片
		                        </span>
		                    </a>
		                </li>
		            </ul>
		        </li>
		  
		    </ul>
		</div>

</div>


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