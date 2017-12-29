<template>
	<div id="left">
        <!-- 消息页的左侧 -->
        <ul>
            <li @clikc="skip" :class="route_path=='comments'?'active':''">
                <router-link to="/comments">
                	<i class="iconfont icon-xinxi"></i>
                	<div class="name">评论</div>
                    <div v-if="unreads.comments" class="badge">{{ unreads.comments }}</div>
                </router-link>
            </li>
            <li>
                <router-link to="/chats">
                	<i class="iconfont icon-email"></i>
                	<div class="name">私信</div>
                    <div class="badge">5</div>
                </router-link>
            </li>
            <li>
                <router-link to="/requests">
                	<i class="iconfont icon-tougaoguanli"></i>
                	<div class="name">投稿请求</div>
                    <div class="badge">12</div>
                </router-link>
            </li>
            <li>
                <router-link to="/likes">
                	<i class="iconfont icon-xin"></i>
                	<div class="name">喜欢和赞</div>
                    <div v-if="unreads.likes" class="badge">{{ unreads.likes }}</div>
                </router-link>
            </li>
            <li>
                <router-link to="/follows">
                	<i class="iconfont icon-jiaguanzhu"></i>
                	<div class="name">关注</div>
                    <div v-if="unreads.follows" class="badge">{{ unreads.follows }}</div>
                </router-link>
            </li>
            <li>
                <router-link to="/tip">
                	<i class="iconfont icon-zanshangicon"></i>
                	<div class="name">赞赏</div>
                    <div v-if="unreads.tips" class="badge">{{ unreads.tips }}</div>
                </router-link>
            </li>
            <li>
                <router-link to="/others">
                	<i class="iconfont icon-gengduo"></i>
                	<div class="name">其他消息</div>
                    <div v-if="unreads.others" class="badge">{{ unreads.others }}</div>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'NotificationLeft',

  created(){

    var route_path= window.location.hash.replace('#/','');
    this.route_path=route_path;
    if(route_path==''){
        this.$router.push( {path:'/comments'} );
        this.route_path='comments';

    }
    this.fetchData();
  },

  methods:{
      skip(e){
        $(e.target).parents('li').addClass('active').siblings().removeClass('active');
      },
      fetchData(){
        var api=window.tokenize('/api/unreads');
        var vm=this;
        window.axios.get(api).then(function(response){
            vm.unreads=response.data;
        });
      },
  },

  data () {
    return {
        route_path: 'comments',
        unreads:[]
    }
  }
}
</script>

<style lang="scss" scoped>
    #left {
        ul {
            li {
                a {
                    display: inline-block;
                    padding: 10px 25px;
                    line-height: 30px;
                    width: 100%;
                    border-radius: 4px;
                    i {
                        font-size: 24px;
                        color: #fd5b78;
                        font-weight: 400;
                        margin-right: 15px;
                        vertical-align: middle;
                    }
                    .name {
                        font-size: 15px;
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .badge {
                        float: right;
                        margin-top: 6px;
                    }
                    &:hover,&.router-link-active {
                        background-color: #f0f0f0;
                    }
                    @media screen and (max-width: 540px) {
                        padding: 10px;
                        .name {
                            display: none;
                        }
                    }
                }
            }
        }
    }
</style>