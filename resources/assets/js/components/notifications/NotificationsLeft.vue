<template>
	<div>
        <!-- 消息页的左侧 -->
        <ul>
            <li>
                <router-link to="/comments">
                	<i class="iconfont icon-xinxi"></i>
                	<span class="name">评论</span>
                </router-link>
            </li>
            <li>
                <router-link to="/chats">
                	<i class="iconfont icon-email"></i>
                	<span class="name">私信</span>
                    <span class="badge">5</span>
                </router-link>
            </li>
            <li>
                <router-link to="/requests">
                	<i class="iconfont icon-tougaoguanli"></i>
                	<span class="name">投稿请求</span>
                </router-link>
            </li>
            <li>
                <router-link to="/likes">
                	<i class="iconfont icon-xin"></i>
                	<span class="name">喜欢和赞</span>
                </router-link>
            </li>
            <li>
                <router-link to="/follows">
                	<i class="iconfont icon-jiaguanzhu"></i>
                	<span class="name">关注</span>
                </router-link>
            </li>
            <li>
                <router-link to="/tip">
                	<i class="iconfont icon-zanshangicon"></i>
                	<span class="name">赞赏</span>
                </router-link>
            </li>
            <li>
                <router-link to="/others">
                	<i class="iconfont icon-gengduo"></i>
                	<span class="name">其他消息</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'NotificationsLeft',

  created(){

    var route_path= window.location.hash.replace('#/','');
    this.route_path=route_path;
    if(route_path==''){
        this.$route.push( {path:'/comments'} );
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

<style lang="css" scoped>
    ul {
        li {
            &.active {
                background-color: #f0f0f0;
                border-radius: 4px;
            }
            .link {
                height: auto;
                padding: 10px 25px;
                line-height: 30px;
                display: block;
                color: #333;
                i {
                    margin-right: 15px;
                    font-size: 22px;
                    color: #FF9D23;
                    vertical-align: middle;
                }
                span {
                    font-size: 15px;
                    vertical-align: middle;
                }
                .badge {
                    float: right;
                    margin: 6px 15px 0 0;
                    padding: 3px 6px;
                    font-size: 12px;
                    background-color: #FF9D23;
                }
            }
        }
    }
</style>