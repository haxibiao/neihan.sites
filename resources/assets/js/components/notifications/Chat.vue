<template>
    <!-- 收到的聊天消息 -->
    <div id="chat_messages">
        <div class="chat_top">
            <router-link to="/chats" class="back_list">
                <i class="iconfont icon-zuobian"></i>
                返消息列表
            </router-link>
            <b>与 <a href="javascript:;">中南工大留级生</a> 的对话</b>
            <div class="dropdown">
                <a href="javascript: ;" class="dropdown-toggle open_dropdown" data-toggle="dropdown">
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
            <a href="#" class="load_more">加载更多</a>
            <ul class="message_list">
                <li class="message_l">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_02.jpg" />
                    </a>
                    <div>
                        <span class="content">你现在在吗？</span>
                    </div>
                    <span class="time">09:58</span>
                </li>
                <li class="message_l">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_02.jpg" />
                    </a>
                    <div>
                        <span class="content">一分钱太少了,我给你100吧</span>
                    </div>
                    <span class="time">09:58</span>
                </li>
                <li class="message_r">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_03.jpg" />
                    </a>
                    <div>
                        <span class="content">可以的</span>
                    </div>
                    <span class="time">14:14</span>
                </li>
                <li class="message_r">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_03.jpg" />
                    </a>
                    <div>
                        <span class="content">O(∩_∩)O谢谢土豪</span>
                    </div>
                    <span class="time">14:14</span>
                </li>
                <li class="message_l">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_02.jpg" />
                    </a>
                    <div>
                        <span class="content">我还想再给你100</span>
                    </div>
                    <span class="time">14:20</span>
                </li>
                <li class="message_l">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_02.jpg" />
                    </a>
                    <div>
                        <span class="content">因为我有钱</span>
                    </div>
                    <span class="time">14:20</span>
                </li>
                <li class="message_r">
                    <a href="javascript:;" class="avatar">
                        <img src="/images/photo_03.jpg" />
                    </a>
                    <div>
                        <span class="content">哦 666</span>
                    </div>
                    <span class="time">14:25</span>
                </li>
            </ul>
        </div>
        <div class="write_message">
            <form action="">
                <textarea name="" id="" cols="30" rows="10"></textarea>
                <input type="submit" class="button" value="发送">
            </form>
            <p class="hint">Return 直接发送</p>
        </div>
    </div>
</template>

<script>
export default {

  name: 'Chat',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>