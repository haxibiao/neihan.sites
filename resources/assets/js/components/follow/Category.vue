<template>
	<div v-if="!category" class="loading">
		正在加载....
	</div>
	<!-- 关注的专题 -->
	<div v-else="category" id="category">
		<div class="main_top">
            <a class="avatar avatar_lg avatar_collection" :href="'/'+category.name_en" target="_blank">
                <img :src="category.logo"/>
            </a>
            <a class="btn_base btn_hollow" :href="'/'+category.name_en" target="_blank">
                <span>
                	专题主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <a class="btn_base btn_hollow btn_hollow_xs" data-target=".modal-contribute" data-toggle="modal"  href="javascript:;"  @click="showModal()">
                <span>
                    投稿
                </span>
            </a>
            <div class="info_meta">
		        <a class="headline nickname" :href="'/'+category.name_en" target="_blank">
		            <span class="single_line">
		                 {{ category.name }}
		            </span>
		        </a>
                <p class="info_count">
                    收录了{{ category.count }}篇文章 · {{ category.count_follows }}人关注
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
                        <span>最新<span class="s_s_hide">收录</span></span>
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
                           <article-list :api="'/'+category.name_en+'?collected=1'" />
					</ul>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
                           <article-list :api="'/'+category.name_en+'?commented=1'" />
					</ul>
                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
                           <article-list :api="'/'+category.name_en+'?hot=1'" />
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

       showModal() {
           
            window.bus.$emit('showContribute',this.id);
       },

      fetchData(){
         this.id = this.$route.params.id;
        if(this.id){
          var vm=this;
          var api_url=window.tokenize('/api/category/' + this.id);
          window.axios.get(api_url).then(function(response){
          	   vm.category=response.data;

                //标记关注的最后查看时间
                var api_touch = window.tokenize('/api/follow/' + vm.id + '/categories');
                window.axios.get(api_touch);
          });
         }
      }
  },

  data () {
    return {
         category:null,
         id:null
    }
  }
}
</script>

<style lang="scss" scoped>
    #category {
        .main_top {
            @media screen and (max-width: 768px) {
                padding-bottom: 30px;
                .btn_base {
                    position: absolute;
                    top: 155px;
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