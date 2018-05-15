<template>
    <div id="left">
        <!-- 关注页的左侧 -->
        <div class="dropdown change_type">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="s_s_hide">全部关注</span>
                <i class="iconfont icon-xialaxuan">
                </i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" @click="show('all')">
                        全部关注
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('users')">
                        只看作者
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('categories')">
                        只看专题
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('collection')">
                        只看文集
                    </a>
                </li>
                <li>
                    <a href="#">
                        只看<span class="s_s_hide">推送</span>更新
                    </a>
                </li>
            </ul>
        </div>
        <router-link to="/add" class="add_people">
            <i class="iconfont icon-guanzhu">
            </i>
            <span class="s_s_hide">添加关注</span>
        </router-link>
        <ul class="js_subscription_list">
            <li>
                <router-link to="/timeline">
                    <div class="avatar avatar_xs">
                        <img src="/logo/ainicheng.com.jpg"/>
                    </div>
                    <div class="name">
                        爱你城动态圈
                    </div>
                </router-link>
            </li>
            <li v-for="follow in follows_showing" @click="skip">
                <router-link :to="'/'+follow.type+'/'+follow.id">
                    <div :class="['avatar avatar_xs',follow.type== 'categories' ? 'avatar_collection':'']">
                        <img :src="follow.img" alt="">
                    </div>
                    <div class="name">{{ follow.name }}</div>
                    <span class="count">{{ follow.updates }}</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'FollowLeft',

  created(){
    //default timeline
    this.$router.push({path:"/timeline"});
    var route_path = window.location.hash.replace("#/","");
    this.route_path = route_path;

    this.fetchData();
  },

  computed:{

  },

  methods:{
      show(type){
         console.log(this.follows);
         console.log(type);
         if(type=='all'){
            this.follows_showing=this.follows;
         }else{
            this.follows_showing=[];
            //javascript recursion
            for(var i in this.follows){
                var follow=this.follows[i];
                console.log(follow.type);
                if(follow.type==type){
                    this.follows_showing.push(follow);
                }
            }
            console.log(this.follows_showing);
         }
      },


    skip(e) {
        $(e.target).parents("li").addClass('active').siblings().removeClass('active');
    },

    fetchData() {
        var vm = this;
        var api_url = window.tokenize('/api/follows');
        window.axios.get(api_url).then(function(response){
            vm.follows = response.data;
            vm.follows_showing = response.data;
        });
    }
  },

  data () {
    return {

        route_path: 'timeline',
        follows: [],
        follows_showing: [],

    }
  }
}
</script>

<style lang="scss" scoped>
    #left {
        a {
            i {
                font-size: 13px;
            }
            .icon-xialaxuan {
                color: #c8c8c8;
            }
        }
        .change_type {
            display: inline-block;
            font-size: 15px;
            margin: 4px 0 0 10px;
            .dropdown-menu {
                left: -10px;
                @media screen and (max-width: 768px) {
                    min-width: 71px;
                    li {
                        margin-bottom: 10px;
                        a {
                            padding: 10px 0;
                            text-align: center;
                        }
                    }
                }
            }
        }
        .add_people {
            font-size: 13px;
            float: right;
            margin: 4px 8px 0 0;
            @media screen and (max-width: 768px) {
                // float: none;
            }
        }
        .js_subscription_list {
            margin-top: 7px;
            border-top: 1px solid #f0f0f0;
            li {
                a {
                    display: inline-block;
                    padding: 10px;
                    font-size: 14px;
                    width: 100%;
                    @media screen and (max-width: 768px) {
                        text-align: center;
                    }
                    .name {
                        display: inline-block;
                        vertical-align: middle;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        max-width: 210px;
                        @media screen and (max-width: 992px) {
                            max-width: 90px;
                        }
                        @media screen and (max-width: 768px) {
                            display: block;
                            padding-top: 5px;
                        }
                    }
                    .count {
                        float: right;
                        margin-top: 10px;
                        color: #969696;
                    }
                    &:hover,&.router-link-active {
                        background-color: #f0f0f0;
                        border-radius: 4px 0 0 4px!important;
                    }
                }
                &:first-child a {
                    border-radius: 0 0 0 4px!important;
                }
            }
        }
    }
</style>