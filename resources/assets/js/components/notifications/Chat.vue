<template>
    <div v-if="!with_user" class="loading">
        正在加载...
    </div>
    <div v-else>
     <div id="chat_messages">
        <div class="notification_top">
            <router-link to="/chats" class="back_list">
                <i class="iconfont icon-zuobian"></i>
                返消息列表
            </router-link>
            <div class="thematic">
                与 <a href="javascript:;">{{ with_user.name }}</a> 的对话
            </div>
            <div class="operation">
                <a href="javascript: ;" class="dropdown-toggle open_dropdown" data-toggle="dropdown" aria-expanded="false" >
                    <i class="iconfont icon-xia"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#">
                            <i class="iconfont icon-heimingdan1"></i>
                            <span>加入黑名单</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="iconfont icon-iconset03100"></i>
                            <span>举报用户</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="message_show">
            <a v-if="last_page>1" class="load_more" @click="loadMore">加载更多</a>
            <ul class="message_list">
                <li v-for="message in messages" :class="'message message_'+(isSelf(message.user_id) ? 'r':'l')">
                    <a :href="'/user/'+message.user.id" class="avatar avatar_xs">
                        <img :src="message.user.avatar" />
                    </a>
                    <div class="message_box">
                        <span class="content">{{ message.message }}</span>
                    </div>
                    <span class="time">{{ message.time }}</span>
                </li>
            </ul>
        </div>
        <div class="write_message">
            <form action="">
                <textarea name="" id="" cols="30" rows="10" v-model="new_message.message"></textarea>
                <input type="button" class="button" value="发送" @click="send">
            </form>
            <p class="hint">Return 直接发送</p>
        </div>
    </div>
    </div>
</template>

<script>
export default {

  name: 'Chat',

  created() {
    this.fetchData();
  },
  
  methods: {
    showMenu:function (e) {
        e.preventDefault();
        $(e.currentTarget).children('.dropdown_menu').toggleClass('show');
    },

    fetchData() {
        var api = window.tokenize('/api/notification/chat/'+this.$route.params.id + '?page='+this.page);
        var vm = this;
        console.log(api);
        window.axios.get(api).then(function(response) {
            vm.with_user = response.data.with_user;
            vm.last_page = response.data.messages.last_page;
            var paged_messages = response.data.messages.data;
            for(var i in paged_messages) {
                var message = paged_messages[i];
                vm.messages.unshift(message) ;
            }
        });
    },

    loadMore() {
        ++this.page;
        this.fetchData();
    },

    isSelf(user_id) {
        return window.current_user_id == user_id;
    },

    send() {
        var api = window.tokenize('/api/notification/chat/'+this.$route.params.id+'/send');
        var formData = {
            message: this.new_message.message
        };

        //optimistic update ..
        this.messages = this.messages.concat(this.new_message);

        //post 
        var vm = this;
        window.axios.post(api, formData).then(function(response) {
            //real update
            vm.messages.pop();
            vm.messages = vm.messages.concat(response.data);
            vm.new_message.message = '';
        });
    }
  },

  data () {
    return {
        with_user: null,
        page: 1,
        last_page: null,
        messages:[],
        new_message: {
            user: {
                id: window.current_user_id,
                name: window.current_user_name,
                avatar: window.current_user_avatar,
            },
            user_id: window.current_user_id,
            message: null
        },
    }
  }
}
</script>

<style lang="scss" scoped>
     #chat_messages {
        .notification_top {
            @media screen and (max-width: 1200px) {
                width: 580.01px;
            }
            @media screen and (max-width: 992px) {
                width: 489.99px;
            }
            @media screen and (max-width: 768px) {
                width: 389px;
                text-align: left;
                .back_list {
                    float: none;
                }
                .operation {
                    float: none;
                }
            }
        }
        .message_show {
            padding-top: 50px;
            .load_more {
                margin: 0 auto;
                background: none;
                font-size: 14px;
                color: #2B89CA;
            }
            .message_list {
                padding: 10px 0 110px;
                .time {
                    font-size: 12px;
                    color: #d9d9d9;
                    margin-top: 2px;
                }
                .message_l {
                    .avatar {
                        float: left;
                    }
                    div {
                        .content {
                            background-color: #e7f1fc;
                            border-color: #bad0e9;
                            border-radius: 0 4px 4px 4px;
                        }
                        &::before,
                        &::after {
                            left: -9px;
                            border-left: 9px solid transparent;
                            border-top: 16px solid #bad0e9;
                        }
                        &::after {
                            left: -7px;
                            border-top: 16px solid #e7f1fc;
                        }
                    }
                    .time {
                        float: left;
                        margin-left: 56px;
                    }
                }
                .message_r {
                    .avatar {
                        float: right;
                    }
                    div {
                        .content {
                            float: right;
                            background-color: #eee;
                            border-color: #d9d9d9;
                            border-radius: 4px 0 4px 4px;
                        }
                        &::before,
                        &::after {
                            right: -9px;
                            border-right: 9px solid transparent;
                            border-top: 16px solid #d9d9d9;
                        }
                        &::after {
                            right: -7px;
                            border-top: 16px solid #eee;
                        }
                    }
                    .time {
                        float: right;
                        margin-right: 56px;
                    }
                }
            }
        }
        .write_message {
            @media screen and (max-width: 1200px) {
                width: 580.01px;
            }
            @media screen and (max-width: 992px) {
                width: 489.99px;
            }
            @media screen and (max-width: 768px) {
                width: 250px;
            }
        }
    }
</style>