<template>
  <div class="signs">
    <h4 class="title">
      <div class="normal-title">
        <a href="javascript:;" @click="toggle('login')" :class="tab == 'login' ? 'active' : ''">登录</a>
        <b>·</b>
        <a href="javascript:;" @click="toggle('register')" :class="tab == 'register' ? 'active' : ''">注册</a>
      </div>
    </h4>
    <div class="signs-container" v-if="isLogin">
        <form id="loginForm" action="/login" accept-charset="UTF-8" method="post">
            <input type="hidden" name="_token" v-model="token">
            <div class="input-prepend restyle">
                <input placeholder="邮箱" type="text" name="email"  required="required">
                <i class="iconfont icon-yonghu01"></i>
            </div>
            <div class="input-prepend">
                <input placeholder="密码" type="password" name="password" required="required">
                <i class="iconfont icon-suo2"></i>
            </div>
            <!-- <captcha></captcha> -->
            <div class="remember-btn">
                <input type="checkbox" value="true" checked="checked" name="remember_me"><span>记住我</span>
            </div>
            <div class="forget-btn">
                <a class="javascript:;" data-toggle="dropdown" href="javascript:;">登录遇到问题?</a>
                <ul class="dropdown-menu">
                    <li><a class="link" href="javascript:;">用手机号重置密码</a></li>
                    <li><a class="link" href="javascript:;">用邮箱重置密码</a></li>
                    <li><a class="link" target="_blank" href="javascript:;">无法用海外手机号登录</a></li>
                    <li><a class="link" target="_blank" href="javascript:;">无法用 Google 帐号登录</a></li>
                </ul>
            </div>
            <input type="submit" name="commit" value="登录" class="btn-base btn-login">
        </form>
        <!-- 更多登录方式 -->
        <!--    <div class="more-sign">
            <h6>社交帐号登录</h6>
            <social-login></social-login>
        </div> -->
    </div>
    <div class="signs-container" v-else>
        <form id="registerForm" action="/register" accept-charset="UTF-8" method="post">
            <input type="hidden" name="_token" v-model="token">
            <div class="input-prepend restyle">
                <input placeholder="你的昵称" type="text" name="name" required="required">
                <i class="iconfont icon-yonghu01"></i>
            </div>
            <div class="input-prepend restyle no-radius">
                <input placeholder="邮箱" type="email" name="email" required="required">
                <i class="iconfont icon-ordinarymobile"></i>
            </div>
            <div class="input-prepend">
                <input placeholder="设置密码" type="password" name="password" required="required">
                <i class="iconfont icon-suo2"></i>
            </div>
            <input type="submit" name="commit" value="注册" class="btn-base btn-handle">
            <p class="sign-up-msg">
              点击 “注册” 即表示您同意并愿意遵守爱你城<br>
              <a target="_blank" href="https://ainicheng.com/article/12422">用户协议</a> 和 
              <a target="_blank" href="https://ainicheng.com/article/12423">隐私政策</a> 。
            </p>
        </form>
        <!-- 更多登录方式 -->
        <!--  <div class="more-sign">
            <h6>社交帐号直接注册</h6>
            <social-login></social-login>
        </div> -->
    </div>
  </div>
</template>

<script>
export default {
  name: "Sign",

  props: ["register"],

  created() {
    if (this.register !== undefined) {
      this.tab = "register";
    }
  },

  computed: {
    token() {
      return window.csrf_token;
    },
    isLogin() {
      return this.tab == "login";
    }
  },

  methods: {
    toggle(tab) {
      this.tab = tab;
    }
  },

  data() {
    return {
      tab: "login"
    };
  }
};
</script>

<style lang="scss" scoped>
.signs {
  width: 40%;
  min-width: 340px;
  max-width: 400px;
  margin: 60px auto 0;
  padding: 50px 50px 30px;
  background-color: #fff;
  border-radius: 4px;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
  vertical-align: middle;
  display: inline-block;
  .title {
    margin-bottom: 50px;
    padding: 10px;
    font-weight: 400;
    color: #969696;
    .normal-title {
      a {
        padding: 10px;
        color: #969696;
      }
      b {
        padding: 10px;
      }
      .active {
        font-weight: 700;
        color: #d96a5f;
        border-bottom: 2px solid #d96a5f;
      }
    }
  }
  .signs-container {
    form {
      margin-bottom: 30px;
      .input-prepend {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
        input {
          width: 100%;
          height: 50px;
          margin-bottom: 0;
          padding: 4px 12px 4px 35px;
          border: 1px solid #c4c4c4;
          border-radius: 0 0 4px 4px;
          background-color: hsla(0, 0%, 71%, 0.1);
          vertical-align: middle;
          font-size: 15px;
        }
        i {
          position: absolute;
          width: 30px;
          height: 30px;
          text-align: center;
          line-height: 30px;
          top: 10px;
          left: 5px;
          font-size: 18px;
          color: #969696;
        }
      }
      .restyle {
        margin-bottom: 0;
        input {
          border-bottom: none;
          border-radius: 4px 4px 0 0;
        }
      }
      .no-radius {
        input {
          border-radius: 0;
        }
        i {
          font-size: 20px;
        }
      }
      .remember-btn {
        float: left;
        margin: 15px 0;
        span {
          margin-left: 5px;
          font-size: 15px;
          color: #969696;
          vertical-align: middle;
        }
      }
      .forget-btn {
        float: right;
        position: relative;
        margin: 15px 0;
        font-size: 14px;
        & > a {
          color: #969696;
        }
        .dropdown-menu {
          right: 5px;
        }
      }
      .btn-base {
        width: 100%;
        padding: 12px;
        font-size: 18px;
      }
      .sign-up-msg {
        margin: 10px 0;
        padding: 0;
        text-align: center;
        font-size: 12px;
        line-height: 20px;
        color: #969696;
        a {
          color: #2b89ca;
        }
      }
    }
    .more-sign {
      margin-top: 30px;
      h6 {
        position: relative;
        margin: 0 0 20px;
        font-size: 12px;
        color: #969696;
        &::after,
        &::before {
          content: "";
          border-top: 1px solid #b5b5b5;
          display: block;
          position: absolute;
          width: 60px;
          top: 5px;
        }
        &::after {
          right: 30px;
        }
        &::before {
          left: 30px;
        }
      }
    }
  }
}
</style>
