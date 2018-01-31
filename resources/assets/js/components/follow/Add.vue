<template>
    <div v-if="!loaded" class="loading"></div>
    <!-- 添加关注 -->
	<div v-else="loaded" id="add">
        <!-- Nav tabs -->
        <ul class="trigger_menu" role="tablist">
            <li class="active" role="presentation">
                <a aria-controls="tuijian" data-toggle="tab" href="#tuijian" role="tab">
                    <i class="iconfont icon-duoxuan">
                    </i>
                    <span>全部<span class="s_s_hide">推荐</span></span>
                </a>
            </li>
            <li role="presentation">
                <a aria-controls="users" data-toggle="tab" href="#users" role="tab">
                    <i class="iconfont icon-yonghu01">
                    </i>
                    <span><span class="s_s_hide">推荐</span>作者</span>
                </a>
            </li>
            <li role="presentation">
                <a aria-controls="zhuanti1" data-toggle="tab" href="#zhuanti1" role="tab">
                    <i class="iconfont icon-zhuanti1">
                    </i>
                    <span><span class="s_s_hide">推荐</span>专题</span>
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <ul class="tab-pane fade in active user_list" id="tuijian" role="tabpanel">
              <div v-for="recommend in recommends">
                    <li v-if="recommend.type=='users'">
                        <div class="author">
                            <a class="avatar avatar_in" href="#">
                                <img :src="recommend.avatar"/>
                            </a>
                              <follow type="users" :id="recommend.id" :user-id="user.id" :followed="recommend.is_followed"></follow>
                            <div class="info_meta">
                                <a class="headline nickname" href="#">
                                    <span class="single_line">{{ recommend.name }}</span>
                                </a>
                                <div class="meta">
                                    <a :href="/user/+recommend.followed_user_id">
                                        {{ recommend.followed_user }}
                                    </a>
                                    <span>
                                        关注了作者
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                      <li v-if="recommend.type=='categories'">
                            <div class="author">
                                <a class="avatar avatar_in" href="#">
                                    <img :src="recommend.logo"/>
                                </a>
                                <follow type="categories" :id="recommend.id" :user-id="user.id" :followed="recommend.is_followed"></follow>

                                <div class="info_meta">
                                    <a class="headline nickname" href="#">
                                        <span class="single_line">{{ recommend.name }}</span>
                                    </a>
                                    <p class="notice">
                                        {{ recommend.description }}
                                    </p>
                                    <a href="#">
                                        <i class="iconfont icon-quanbu">
                                        </i>
                                        <span>
                                            5.4K篇文章 · 231.3K人关注
                                        </span>
                                    </a>
                                </div>
                            </div>
                     </li>
              </div>
            </ul>
            <ul class="tab-pane fade user_list" id="users" role="tabpanel">
                <div v-for="recommended_user in recommended_users">
                    <li>
                        <div class="author">
                            <a class="avatar avatar_in" :href="'/user/'+recommended_user.id">
                                <img :src="recommended_user.avatar"/>
                            </a>

                              <follow type="users" :id="recommended_user.id" :user-id="user.id" :followed="recommended_user.followed"></follow>
                            <div class="info_meta">
                                <a class="headline nickname" :href="'/user/'+recommended_user.id">
                                    <span class="single_line">{{ recommended_user.name }}</span>
                                </a>
                                <p class="notice">
                                    {{ recommended_user.introduction }}
                                </p>

                                <a v-for="collection in recommended_user.collections" href="#" class="anthology">
                                    <i class="iconfont icon-wenji">
                                    </i>
                                    <span>
                                        {{ collection.name }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </li>
               </div>
            </ul>
            <ul class="tab-pane fade user_list" id="zhuanti1" role="tabpanel">
                <div v-for="category in recommended_categories">
                    <li>
                        <div class="author">

                            <a class="avatar avatar_collection" :href="'/'+category.name_en">
                                <img :src="category.logo"/>
                            </a>
                                <follow type="categories" :id="category.id" :user-id="user.id" :followed="category.followed"></follow>
                            <div class="info_meta">
                                <a class="headline nickname" href="#">
                                    <span class="single_line">{{ category.name }}</span>
                                </a>
                                <p class="notice">
                                    {{ category.description }}
                                </p>
                                <a :href="'/'+category.name_en" class="anthology">
                                    <i class="iconfont icon-quanbu">
                                    </i>
                                    <span>
                                       {{ category.count }}篇文章 · {{ category.count_follows }}人关注
                                    </span>
                                </a>
                            </div>
                        </div>
                    </li>
                </div>
            </ul>
<!--             <a class="load_more" href="#">
                阅读更多
            </a> -->
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
        @media screen and (max-width: 660px) {
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
        @media screen and (max-width: 600px) {
            .btn_base {
                width: 84px;
                padding: 4px;
            }
            .btn_followed {
                padding: 3px;
            }
        }
        .user_list {
            .info_meta {
                .nickname {
                    margin-bottom: 8px;
                }
                .meta {
                    margin-top: 8px;
                }
            }
        }
    }
</style>