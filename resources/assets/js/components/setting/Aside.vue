<template>
  <ul class="aside aside-menu">
    <router-link tag="li" to="/base" active-class="active">
      <a href="javascript:;"><div class="icon-wrp"><i class="iconfont icon-duoxuan"></i></div> <span>基础设置</span></a>
    </router-link>
    <router-link tag="li" to="/profile" active-class="active">
      <a href="javascript:;"><div class="icon-wrp"><i class="iconfont icon-ziliao"></i> </div><span>个人资料</span>
      </a>
    </router-link>
    <router-link tag="li" to="/reward" active-class="active">
      <a href="javascript:;"><div class="icon-wrp"><i class="iconfont icon-jinqian1"></i> </div><span>赞赏设置</span>
      </a>
    </router-link>
  </ul>
</template>

<script>
export default {

  name: 'Aside',

  mounted() {
    if(!this.leftMenu) {
      this.$router.push({path:'/base'})
    }
  },

  data () {
    return {
      
    }
  }
}
</script>
<style lang="scss" scoped>
.aside-menu {
  li {
    &>a {
      .icon-wrp {
        i {
          font-size: 22px;
        }
      }
    }
  }
}
</style>