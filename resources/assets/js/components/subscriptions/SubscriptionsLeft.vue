<template>
    <div>
        <div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                全部关注
                <i class="iconfont icon-xialaxuan">
                </i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#">
                        全部关注
                    </a>
                </li>
                <li>
                    <a href="#">
                        只看作者
                    </a>
                </li>
                <li>
                    <a href="#">
                        只看专题
                    </a>
                </li>
                <li>
                    <a href="#">
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
        <router-link to="/subscriptions_add" class="add_people">
            <i class="iconfont icon-tianjiaguanzhu">
            </i>
            添加关注
        </router-link>
        <ul class="js_subscription_list">
            <li>
                <router-link to="/friend">
                    <div class="avatar">
                        <img src="//cdn2.jianshu.io/assets/web/jianyouquan-2fb0cd72e35147c79d6507c3a3a2591b.png" style="border: none;"/>
                    </div>
                    <div class="name">
                        爱你城朋友圈
                    </div>
                </router-link>
            </li>
            <li>
                <router-link to="/category">
                    <div class="avatar avatar_collection">
                        <img src="//upload.jianshu.io/collections/images/12/1239240801.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/120/h/120"/>
                    </div>
                    <div class="name">
                        散文
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/user">
                    <div class="avatar">
                        <img src="//upload.jianshu.io/users/upload_avatars/8370162/1728c3a2-91d4-4554-be23-124fe4233dc8.jpg?imageMogr2/auto-orient/strip|imageView2/1/w/120/h/120"/>
                    </div>
                    <div class="name">
                        爱你城精选
                    </div>
                    <span class="count">
                        254
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/collection">
                    <div class="avatar avatar_collection">
                        <img src="//cdn2.jianshu.io/assets/default_avatar/avatar-notebook-default-640f7dde88592bdf6417d8ce1902636e.png"/>
                    </div>
                    <div class="name">
                        王者荣耀
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'SubscriptionsLeft',

  mounted(){
    this.$router.push({path:"/friend"});
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