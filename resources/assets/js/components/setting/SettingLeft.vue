<template>
	<div>
        <!-- 设置页的左侧 -->
        <ul>
            <li>
                <router-link to="/basic">
                	<div class="setting_icon">
                		<i class="iconfont icon-duoxuan"></i>
                	</div>
                	<span class="name"><span class="s_s_hide">基础</span>设置</span>
                </router-link>
            </li>
            <li>
                <router-link to="/profile">
                	<div class="setting_icon">
                		<i class="iconfont icon-ziliao"></i>
                	</div>
                	<span class="name"><span class="s_s_hide">个人</span>资料</span>
                </router-link>
            </li>
            <li>
                <router-link to="/reward-setting">
                	<div class="setting_icon">
                		<i class="iconfont icon-jinqian1"></i>
                	</div>
                	<span class="name">赞赏<span class="s_s_hide">设置</span></span>
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

<style lang="scss" scoped>
    ul {
        li {
            a {
                padding: 10px 25px;
                display: block;
                width: 100%;
                font-size: 15px;
                .setting_icon {
                    width: 32px;
                    height: 32px;
                    display: inline-block;
                    margin-right: 15px;
                    vertical-align: middle;
                    background-color: #a0a0a0;
                    border-radius: 4px;
                    text-align: center;
                    color: #fff;
                    i {
                        font-size: 18px;
                        margin: 6px 0 0 1px;
                        display: block;
                    }
                }
                span {
                    vertical-align: middle;
                }
                &:hover {
                    background-color: #f0f0f0;
                }
                @media screen and (max-width: 425px) {
                    padding: 10px 15px;
                    i {
                        margin-right: 3px;
                    }
                    .name {
                        display: block;
                        padding-top: 5px;
                    }
                }
                @media screen and (max-width: 425px) {
                    text-align: center;
                    .setting_icon {
                        margin-right: 0;
                    }
                }
            }
            .router-link-active {
                background-color: #f0f0f0;
                border-radius: 4px;
            }
        }
    }
</style>