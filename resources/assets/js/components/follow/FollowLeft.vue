<template>
    <div>
        <!-- 关注页的左侧 -->
        <div class="dropdown change_type">
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
        <router-link to="/add" class="add_people">
            <i class="iconfont icon-guanzhu">
            </i>
            添加关注
        </router-link>
        <ul class="js_subscription_list">
            <li>
                <router-link to="/timeline">
                    <div class="avatar">
                        <img src="/images/photo_02.jpg"/>
                    </div>
                    <div class="name">
                        爱你城朋友圈
                    </div>
                </router-link>
            </li>
            <li>
                <router-link to="/category/1">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_03.jpg"/>
                    </div>
                    <div class="name">
                        散文
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/user/1">
                    <div class="avatar">
                        <img src="/images/photo_03.jpg"/>
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
                <router-link to="/collection/1">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_09.png"/>
                    </div>
                    <div class="name">
                        王者荣耀
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/category/2">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_03.jpg"/>
                    </div>
                    <div class="name">
                        散文
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/user/2">
                    <div class="avatar">
                        <img src="/images/photo_03.jpg"/>
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
                <router-link to="/collection/2">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_09.png"/>
                    </div>
                    <div class="name">
                        王者荣耀
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/category/3">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_03.jpg"/>
                    </div>
                    <div class="name">
                        散文
                    </div>
                    <span class="count">
                    </span>
                </router-link>
            </li>
            <li>
                <router-link to="/user/3">
                    <div class="avatar">
                        <img src="/images/photo_03.jpg"/>
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
                <router-link to="/collection/3">
                    <div class="avatar avatar_collection">
                        <img src="/images/category_09.png"/>
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

  name: 'FollowLeft',

  mounted(){
    this.$router.push({path:"/timeline"});
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