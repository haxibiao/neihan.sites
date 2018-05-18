<template>
	<div>
        <!-- 设置页的左侧 -->
        <ul>
            <li>
                <router-link to="/basic">
                	<div class="setting_icon">
                		<i class="iconfont icon-duoxuan"></i>
                	</div>
                	<span class="name">基础设置</span>
                </router-link>
            </li>
            <li>
                <router-link to="/profile">
                	<div class="setting_icon">
                		<i class="iconfont icon-ziliao"></i>
                	</div>
                	<span class="name">个人资料</span>
                </router-link>
            </li>
            <li>
                <router-link to="/reward-setting">
                	<div class="setting_icon">
                		<i class="iconfont icon-jinqian1"></i>
                	</div>
                	<span class="name">赞赏设置</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'SettingLeft',

  data () {
    return {

    }
  },
  
  mounted(){
    this.$router.push({path:"/basic"});
    var route_path = window.location.hash.replace("#/","");
    this.route_path = route_path;
  },
}
</script>

<style lang="css" scoped>
</style>