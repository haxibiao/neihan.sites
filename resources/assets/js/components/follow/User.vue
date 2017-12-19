<template>
	<div v-if="!user" class="loading">
		正在加载....
	</div>
	<!-- 关注的用户 -->
	<div v-else="user"id="user">
		<div class="main_top clearfix">
            <a class="avatar" href="'/user/'+user.id" target="_blank">
                <img :src="user.avatar"/>
            </a>
            <a class="botm contribute" :href="'/user/'+user.id" target="_blank">
                <span>
                	个人主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <a class="contribute" :href="'/chat/with/'+user.id" target="_blank">
                <span>
                    发消息
                </span>
            </a>
            <div class="title">
	            <a class="name" :href="'/user/'+user.id" target="_blank">
	                <span>
	                    {{ user.name }}
	                </span>
	            </a>
        	</div>
            <p>
                写了{{ user.count_words }}字，获得了{{ user.count_favorites }}个喜欢
            </p>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新发布</span>
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
                         <article-list api="/" />
					</ul>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
                         <article-list api="/" />
					</ul>

                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
                         <article-list api="/" />
					</ul>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
export default {

  name: 'User',

  created(){
     this.fetchData();
  },

  watch:{
  	//路由如果变化会再次执行该方法
  	'$route':'fetchData'
  },

  methods:{
      fetchData() {
           var api_url=window.tokenize('/api/user/'+this.$route.params.id);
           var vm=this;
           window.axios.get(api_url).then(function(response){
           	  vm.user=response.data.user;
           });
      },

  },

  data () {
    return {
         user:null
    }
  }
}
</script>

<style lang="css" scoped>
</style>