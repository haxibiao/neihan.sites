<template>
	<div>
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

  //  mounted(){
  //   this.$router.push({path:"/comments"});
  //   var route_path = window.location.hash.replace("#/","");
  //   this.route_path = route_path;
  // },

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>