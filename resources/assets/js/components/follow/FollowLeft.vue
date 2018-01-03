<template>
    <div id="left">
        <!-- 关注页的左侧 -->
        <div class="dropdown change_type">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                全部关注
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
                        只看推送更新
                    </a>
                </li>
            </ul>
        </div>
        <router-link to="/add" class="add_people">
            <i class="iconfont icon-guanzhu">
            </i>
            添加关注
        </router-link>
        <ul class="js_subscription_list">
            <li>
                <router-link to="/timeline">
                    <div class="avatar avatar_xs">
                        <img src="/images/photo_02.jpg"/>
                    </div>
                    <div class="name">
                        爱你城朋友圈
                    </div>
                </router-link>
            </li>
            <li v-for="follow in follows_showing" @click="skip">
                <router-link :to="'/'+follow.type+'/'+follow.id">
                    <a href="javascript:;" class="category">
                        <div class="avatar avatar_xs avatar_collection">
                            <img :src="follow.img" alt="">
                        </div>
                        <div class="name">{{ follow.name }}</div>
                        <span class="count">{{ follow.updates }}</span>
                    </a>
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
        }
        .add_people {
            font-size: 13px;
            float: right;
            margin: 4px 8px 0 0;
        }
        .js_subscription_list {
            margin-top: 7px;
            border-top: 1px solid #f0f0f0;
            clear: both;
            li {
                a {
                    display: inline-block;
                    padding: 10px;
                    font-size: 14px;
                    width: 100%;
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
                        @media screen and (max-width: 540px) {
                            display: block;
                            padding: 5px 0 0 0;
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