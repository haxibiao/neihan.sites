<template>
	<div>
        <ul>
            <li>
                <router-link to="/comments">
                	<i class="iconfont icon-pinglun1"></i>
                	<span>评论</span>
                </router-link>
            </li>
            <li>
                <router-link to="/chats">
                	<i class="iconfont icon-sixin"></i>
                	<span>消息</span>
                </router-link>
            </li>
            <li>
                <router-link to="/requests">
                	<i class="iconfont icon-tougaoqingqiu"></i>
                	<span>投稿请求</span>
                </router-link>
            </li>
            <li>
                <router-link to="/likes">
                	<i class="iconfont icon-xihuan"></i>
                	<span>喜欢和赞</span>
                </router-link>
            </li>
            <li>
                <router-link to="/follows">
                	<i class="iconfont icon-yiguanzhu"></i>
                	<span>关注</span>
                </router-link>
            </li>
            <li>
                <router-link to="/tip">
                	<i class="iconfont icon-zanshang"></i>
                	<span>赞赏</span>
                </router-link>
            </li>
            <li>
                <router-link to="/others">
                	<i class="iconfont icon-sandian"></i>
                	<span>其他消息</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'NotificationsLeft',

   mounted(){
    this.$router.push({path:"/comments"});
    var route_path = window.location.hash.replace("#/","");
    this.route_path = route_path;
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>