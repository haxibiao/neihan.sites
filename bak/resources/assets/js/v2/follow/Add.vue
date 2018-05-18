<template>
    <!-- <div v-if="!loaded" class="loading"></div> -->
    <!-- 添加关注 -->
	<div v-else="loaded" id="add">
        <!-- Nav tabs -->
        <ul class="trigger_menu" role="tablist">
            <li class="active" role="presentation">
                <a aria-controls="tuijian" data-toggle="tab" href="#tuijian" role="tab">
                    <i class="iconfont icon-duoxuan">
                    </i>
                    <span>全部推荐</span>
                </a>
            </li>
            <li role="presentation">
                <a aria-controls="users" data-toggle="tab" href="#users" role="tab">
                    <i class="iconfont icon-yonghu01">
                    </i>
                    <span>推荐作者</span>
                </a>
            </li>
            <li role="presentation">
                <a aria-controls="zhuanti1" data-toggle="tab" href="#zhuanti1" role="tab">
                    <i class="iconfont icon-zhuanti1">
                    </i>
                    <span>推荐专题</span>
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <ul class="tab-pane fade in active user_list" id="tuijian" role="tabpanel">
                <li>
                    <div class="author">
                        <a class="avatar avatar_in" href="/v2/user">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="#">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                汤圆er
                            </a>
                            <div class="meta">
                                <a href="#">
                                    懂点医 、
                                </a>
                                <a href="#">
                                    小云淮
                                </a>
                                <span>
                                    关注了作者
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="author">
                        <a class="avatar avatar_in" href="#">
                            <img src="/images/photo_03.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="#">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                爱你城播客
                            </a>
                            <p class="notice">
                                爱你城播客官号。爱你城播客将邀请各主播大咖朗读简书的文章，愿美妙的声音一直陪伴你。
                            </p>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    【爱你城鸡汤馆】妈，我不...
                                </span>
                            </a>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    【爱你城鸡汤馆】你有最美...
                                </span>
                            </a>
                            <div class="meta">
                                <a href="#">
                                    懂点医 、
                                </a>
                                <a href="#">
                                    小云淮
                                </a>
                                <span>
                                    关注了作者
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="author">
                        <a class="avatar avatar_in avatar_collection" href="#">
                            <img src="/images/photo_03.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="#">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                爱你城交友
                            </a>
                            <p class="notice">
                                据说在爱你城认真写作的人都可以在这里找到TA的灵魂伴侣。玩转爱你城第一步，从【爱你城交友】开始。专题接受爱你城交友、爱你城同城、交友故事和情书，详见投稿须知。
                            </p>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-quanbu">
                                </i>
                                <span>
                                    2.0K篇文章 · 244.1K人关注
                                </span>
                            </a>
                            <div class="meta">
                                <a href="#">
                                    懂点医 、
                                </a>
                                <a href="#">
                                    小云淮
                                </a>
                                <span>
                                    关注了作者
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="tab-pane fade user_list" id="users" role="tabpanel">
                <li>
                    <div class="author">
                        <a class="avatar avatar_in" href="#">
                            <img src="/images/photo_03.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="#">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                爱你城播客
                            </a>
                            <p class="notice">
                                爱你城播客官号。爱你城播客将邀请各主播大咖朗读简书的文章，愿美妙的声音一直陪伴你。
                            </p>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    【爱你城鸡汤馆】妈，我不...
                                </span>
                            </a>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-wenji">
                                </i>
                                <span>
                                    【爱你城鸡汤馆】你有最美...
                                </span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="tab-pane fade user_list" id="zhuanti1" role="tabpanel">
                <li>
                    <div class="author">
                        <a class="avatar avatar_in avatar_collection" href="#">
                            <img src="/images/photo_03.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="#">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="headline nickname" href="#">
                                爱你城交友
                            </a>
                            <p class="notice">
                                据说在爱你城认真写作的人都可以在这里找到TA的灵魂伴侣。玩转爱你城第一步，从【爱你城交友】开始。专题接受爱你城交友、爱你城同城、交友故事和情书，详见投稿须知。
                            </p>
                            <a href="#" class="anthology">
                                <i class="iconfont icon-quanbu">
                                </i>
                                <span>
                                    2.0K篇文章 · 244.1K人关注
                                </span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <a class="load_more" href="#">
                阅读更多
            </a>
        </div>
    </div>
</template>

<script>
export default {

  name: 'Add',

  created(){
     this.fetchData();
  },
  
  methods:{
     fetchData(){
        var api_url=window.tokenize('/api/follow/recommends');
        var vm =this;
        window.axios.get(api_url).then(function(response){
            vm.user=response.data.user;
            vm.recommends=response.data.recommends;
            vm.recommended_users=response.data.recommended_users.data;
            vm.recommended_categories=response.data.recommended_categories.data;
            vm.loaded =true;

        });
     }
  },

  data () {
    return {
        loaded: false,
        user:null,
        recommends:[],
        recommended_users:[],
        recommended_categories:[],
    }
  }
}
</script>

<style lang="scss" scoped>
    #add {
        @media screen and (max-width: 640px) {
            .trigger_menu {
                li {
                    a {
                        padding: 13px 20px 4px 20px;
                        span {
                            display: none;
                        }
                    }
                }
            }
        }
        .user_list {
            .info_meta {
                .nickname {
                    display: block;
                    margin-bottom: 8px; 
                }
                .meta {
                    margin-top: 8px;
                }
            }
        }
    }
</style>