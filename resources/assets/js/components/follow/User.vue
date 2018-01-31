<template>
	<div v-if="!user" class="loading">
		正在加载....
	</div>
	<!-- 关注的用户 -->
	<div v-else="user"id="user">
		<div class="main_top">
            <a class="avatar avatar_lg" href="'/user/'+user.id" target="_blank">
                <img :src="user.avatar"/>
            </a>
            <a class="btn_base btn_hollow" :href="'/user/'+user.id" target="_blank">
                <span>
                	个人主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <a class="btn_base btn_hollow btn_hollow_xs" :href="'/chat/with/'+user.id" target="_blank">
                <span>
                    发消息
                </span>
            </a>
            <div class="info_meta">
	            <a class="headline nickname" :href="'/user/'+user.id" target="_blank">
	                <span class="single_line">
	                    {{ user.name }}
	                </span>
	            </a>
                <p class="info_count">
                    写了{{ user.count_words }}字，获得了{{ user.count_favorites }}个喜欢
                </p>
             </div>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新<span class="s_s_hide">发布</span></span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                        <i class="iconfont icon-svg37">
                        </i>
                        <span><span class="s_s_hide">最新</span>评论</span>
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
                        <article-list :api="'/user/'+user.id+'?articles=1'" />
					</ul>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
                         <article-list :api="'/user/'+user.id+'?commented=1'" />
					</ul>

                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
                         <article-list :api="'/user/'+user.id+'?hot=1'" />
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
           this.id=this.$route.params.id;
           if(this.id){
           var api_url=window.tokenize('/api/user/'+this.id);
           var vm=this;
           window.axios.get(api_url).then(function(response){
           	       vm.user=response.data;
                  // update_at watch last
                  var api_touch=window.tokenize('/api/follow/'+vm.id+'/users');
                  window.axios.get(api_touch);
           });
          }
      },

  },

  data () {
    return {
         user:null
    }
  }
}
</script>

<style lang="scss" scoped>
    #user {
        .main_top {
            @media screen and (max-width: 768px) {
                padding-bottom: 30px;
                .btn_base {
                    position: absolute;
                    top: 150px;
                }
                .btn_hollow {
                    right: 40px;
                }
                .btn_hollow_xs {
                    margin-right: 110px;
                }
            }
        }
        @media screen and (max-width: 630px) {
            .trigger_menu {
                li {
                    a {
                        padding: 13px 4px 4px 4px;
                        i {
                            margin: 0;
                        }
                    }
                }
            }
        }
    }
</style>