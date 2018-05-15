<template>
  <div v-if="!with_user" class="loading">
    正在加载...
  </div>
  <div v-else>
    <div class="chat-wrapper">
      <div class="chat-top">
        <router-link to="/chats" class="back-list active"><i class="iconfont icon-zuobian"></i><span class="hidden-xs">返回消息列表</span></router-link>
        <b>与 <a href="javascript:;" target="_blank" class="single-line ">{{ with_user.name }}</a> 的对话</b>
        <div class="dropdown">
          <a href="javascript:;" class="open-dropdown dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="iconfont icon-xia"></i></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:;" class="link"><i class="iconfont icon-heimingdan1"></i><span>加入黑名单</span></a></li>
            <li><a href="javascript:;" class="report link"><i class="iconfont icon-iconset03100"></i><span>举报用户</span></a></li>
          </ul>
        </div>
      </div>
      <div class="message-show" ref="message_list" id="message_list">
        <a v-if="last_page > 1 && page<last_page" class="load-more" @click="loadMore">加载更多</a>
        <ul class="message-list">         
          <li v-for="message in messages"  :class="'message-'+(isSelf(message.user_id) ? 'r':'l')">
            <a :href="'/user/'+message.user.id" class="avatar"><img :src="message.user.avatar"></a>
            <div class="content-wrap"><div class="clearfix"><span class="content" v-html="message.message"></span></div><span class="time">{{ message.time }}</span></div> 
          </li>       
        </ul>
      </div>
      <div class="write-message">
          <textarea name="" id="" cols="30" rows="10" v-model="new_message.message" @keyup="enterSend"></textarea>
          <div class="write-block">
            <div class="hint">Return 直接发送</div>
            <input type="submit" class="btn-base btn-handle btn-md" value="发送" @click="send">
          </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Chat",

  created() {
    this.fetchData();
  },

  methods: {
    showMenu: function(e) {
      e.preventDefault();
      $(e.currentTarget)
        .children(".dropdown_menu")
        .toggleClass("show");
    },

    fetchData() {
      var api = window.tokenize("/api/notification/chat/" + this.$route.params.id + "?page=" + this.page);
      var _this = this;
      window.axios.get(api).then(function(response) {
        _this.with_user = response.data.with_user;
        _this.last_page = response.data.messages.last_page;
        var paged_messages = response.data.messages.data;
        for (var i in paged_messages) {
          var message = paged_messages[i];
          _this.messages.unshift(message);
        }
      });
    },

    loadMore() {
      ++this.page;
      this.fetchData();
    },

    isSelf(user_id) {
      return window.user.id == user_id;
    },

    enterSend(e) {
      //回车发送
      if (e.keyCode != 13) {
        return;
      }
      //按了shift,就不发送，换行！
      if (e.shiftKey) {
        return;
      }
      this.send();
    },

    send(e) {
      if (this.new_message.message && this.new_message.message.trim().length < 1) {
        //不把能发空消息
        return;
      }
      var api = window.tokenize("/api/notification/chat/" + this.$route.params.id + "/send");
      var formData = {
        message: this.new_message.message
      };

      //optimistic update ..
      this.messages = this.messages.concat(this.new_message);

      //post
      var _this = this;
      window.axios.post(api, formData).then(function(response) {
        //real update
        _this.messages.pop();
        _this.messages = _this.messages.concat(response.data);
        _this.new_message.message = "";

        _this.scrollToBottom();
      });
    },

    scrollToBottom() {
      var _this = this;
      this.$nextTick(() => {
        var div = _this.$refs.message_list;
        window.scrollTo(0, div.scrollHeight);
      });
    }
  },

  data() {
    return {
      with_user: null,
      page: 1,
      last_page: null,
      messages: [],
      new_message: {
        user: window.user,
        user_id: window.user.id,
        message: null
      }
    };
  }
};
</script>

<style lang="scss" scoped>
.chat-wrapper {
  // 聊天内容
  .message-show {
    padding-top: 50px;
    .load-more {
      width: 100%;
      height: 40px;
      padding: 10px 15px;
      text-align: center;
      font-size: 14px;
      color: #2b89ca;
      display: block;
      cursor: pointer;
    }
    .message-list {
      padding: 10px 0 140px;
      .message-l,
      .message-r {
        margin-bottom: 15px;
        line-height: 20px;
        border: none !important;
        padding: 10px 0 !important;
        .avatar {
          margin: 0;
        }
        .content-wrap {
          &::before,
          &::after {
            content: "";
            position: absolute;
            display: inline-block;
            top: 0;
          }
          &::after {
            top: 1px;
          }
        }
      }
      .content-wrap {
        position: relative;
        display: block;
        margin: 4px 56px 0;
        min-height: 39px;
        .content {
          position: relative;
          padding: 8px 12px;
          font-size: 14px;
          border: 1px solid;
          word-break: break-word !important;
          line-height: 1.5;
          display: inline-block;
        }
      }
      .time {
        margin-top: 2px;
        font-size: 12px;
        color: #d9d9d9;
      }
      .message-r {
        .avatar {
          float: right;
        }
        .content-wrap {
          .content {
            float: right;
            min-height: 39px;
            background-color: #eee;
            border-color: #d9d9d9;
            border-radius: 4px 0 4px 4px;
          }
          &::before,
          &::after {
            right: -7px;
            border-right: 9px solid transparent;
            border-top: 16px solid #d9d9d9;
          }
          &::after {
            right: -5px;
            border-top: 16px solid #eee;
          }
        }
        .time {
          float: right;
        }
      }
      .message-l {
        .avatar {
          float: left;
        }
        .content-wrap {
          .content {
            min-height: 39px;
            background-color: #e7f1fc;
            border-color: #bad0e9;
            border-radius: 0 4px 4px 4px;
          }
          &::before,
          &::after {
            left: -7px;
            border-left: 9px solid transparent;
            border-top: 16px solid #bad0e9;
          }
          &::after {
            left: -5px;
            border-top: 16px solid #e7f1fc;
          }
        }
        .time {
          float: left;
        }
      }
    }
  }
  // 输入框
  .write-message {
    position: fixed;
    bottom: 0;
    background-color: #fff;
    form {
      width: 100%;
      textarea {
        padding: 6px 12px;
      }
      .btn-handle {
        float: right;
        width: 78px;
        font-size: 15px;
      }
    }
  }
}
</style>
