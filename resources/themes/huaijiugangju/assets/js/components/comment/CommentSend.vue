<template>
  <div class="comment-send no-login">
    <div class="user-face">
      <img class="user-head" src="/images/movie/not_avatart.png" />
    </div>
    <div class="textarea-container">
      <div class="baffle-wrap">
        <div class="baffle">
          请先
          <a class="hxf-btn btn-mini-Login">登录</a>后发表评论 (・ω・)
        </div>
      </div>
      <textarea cols="80" name="msg" rows="5" placeholder="发条友善的评论" class="ipt-txt"></textarea>
      <button type="submit" class="comment-submit" disabled="disabled">发表评论</button>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {}
}
</script>

<style lang="scss" scoped>
.comment-send {
  position: relative;
  margin: 10px 0;
  &.no-login {
    .textarea-container {
      .baffle {
        display: block;
        position: absolute;
        z-index: 102;
        width: 100%;
        top: 0;
        line-height: 64px;
        font-size: 12px;
        border-radius: 4px;
        text-align: center;
        color: #777;
        background-color: #e5e9ef;
        overflow: hidden;
        .hxf-btn {
          padding: 4px 9px;
          margin: 0 3px;
          color: #fff;
          background-color: #29b6f6;
          border-radius: 4px;
        }
      }
      textarea {
        background-color: #e5e9ef;
      }
      .comment-submit {
        cursor: default;
        background-color: #e5e9ef !important;
        border-color: #e5e9ef !important;
        color: #b8c0cc !important;
      }
    }
  }
}
.user-face {
  float: left;
  margin: 7px 0 0 5px;
  position: relative;
  .user-head {
    width: 48px;
    height: 48px;
    border-radius: 50%;
  }
}
.textarea-container {
  position: relative;
  margin-left: 80px;
  margin-right: 80px;
  .baffle {
    display: none;
  }
  & > textarea {
    font-size: 12px;
    display: inline-block;
    box-sizing: border-box;
    background-color: #f4f5f7;
    border: 1px solid #e5e9ef;
    overflow: auto;
    border-radius: 4px;
    color: #555;
    width: 100% !important;
    height: 65px;
    transition: 0s;
    padding: 5px 10px;
    line-height: normal;
  }
  .comment-submit {
    width: 70px;
    height: 64px;
    position: absolute;
    right: -80px;
    top: 0;
    padding: 4px 15px;
    font-size: 14px;
    color: #fff;
    border-radius: 4px;
    text-align: center;
    min-width: 60px;
    vertical-align: top;
    cursor: pointer;
    background-color: #29b6f6;
    border: 1px solid #29b6f6;
    transition: 0.1s;
    user-select: none;
    outline: none;
  }
  @media (max-width: 767px) {
    margin-right: 0;
    .comment-submit {
      position: relative;
      float: right;
      right: 0;
      height: auto;
      margin-top: 10px;
      padding: 5px 2px;
    }
  }
}
</style>
