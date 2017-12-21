<template>
	<div v-if="!category" class="loading">
		正在加载....
	</div>
	<!-- 关注的专题 -->
	<div v-else="category" id="category">
		<div class="main_top clearfix">
            <a class="avatar avatar_collection" :href="'/'+category.name_en" target="_blank">
                <img :src="category.logo"/>
            </a>
            <a class="botm contribute" :href="'/'+category.name_en" target="_blank">
                <span>
                	专题主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <a class="contribute" href="#">
                <span>
                    投稿
                </span>
            </a>
            <div class="title">
		        <a class="name" :href="'/'+category.name_en" target="_blank">
		            <span>
		                 {{ category.name }}
		            </span>
		        </a>
            </div>
            <p>
                收录了{{ category.count }}篇文章 · {{ category.count_follows }}人关注
            </p>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新收录</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                        <i class="iconfont icon-svg37">
                        </i>
                        <span>最新评论</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                        <i class="iconfont icon-huo">
                        </i>
                        <span>热门</span>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="wenzhang" role="tabpanel">
                    <ul class="article_list">
                           <!-- <article-list :api="'/'+ category.name_en+'?commented=1'" start-page="2" /> -->
                           <article-list api='/' />
					</ul>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
                          <!--  <article-list :api="'/' + category.name_en+'?collected=1'" start-page="2" /> -->
                          <article-list api='/' />
					</ul>
                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
                           <!-- <article-list :api="'/' + category.name_en +'?hot=1'" start-page="2" /> -->
                           <article-list api='/' />
					</ul>
                </div>
            </div>

        </div>
	</div>
</template>

<script>
export default {

  name: 'Category',

  created(){
     this.fetchData();
  },

  watch:{
  	  //监视情况，路由变化就会再执行该方法.
     '$route' : 'fetchData'
  },

  methods:{
      fetchData(){
          var vm=this;
          var api_url=window.tokenize('/api/category/' + this.$route.params.id);
          window.axios.get(api_url).then(function(response){
          	   vm.category=response.data;
          });
      }
  },

  data () {
    return {
         category:null
    }
  }
}
</script>

<style lang="css" scoped>
</style>