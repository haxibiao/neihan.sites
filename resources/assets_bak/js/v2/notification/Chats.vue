<template>
    <!-- 收到的聊天消息列表 -->
    <div id="chats">
        <div class="notification_menu">
            全部消息
        </div>
        <ul class="chats_list">
            <li>
                <div class="pull-right">
                    <span class="time">
                        10:08
                    </span>
                    <div class="operation">
                        <a data-toggle="dropdown" href="">
                            <i class="iconfont icon-xia">
                            </i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="link" href="javascript:;">
                                    <i class="iconfont icon-lajitong">
                                    </i>
                                    <span>
                                        删除会话
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="link" href="javascript:;">
                                    <i class="iconfont icon-heimingdan1">
                                    </i>
                                    <span>
                                        加入黑名单
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="report link" href="javascript:;">
                                    <i class="iconfont icon-iconset03100">
                                    </i>
                                    <span>
                                        举报用户
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="avatar avatar_sm" href="/v2/user">
                    <img alt="" src="/images/photo_01.jpg" />
                    <span class="badge">5</span>
                </a>
                <a class="name" href="/v2/user">喵星菇凉</a>
                <router-link to="/chat/1" class="wrap">
                    <p class="new_abstract single_line">
                        呵呵哒
                    </p>
                </router-link>
            </li>
            <li>
                <div class="pull-right">
                    <span class="time">
                        10:08
                    </span>
                    <div class="operation">
                        <a data-toggle="dropdown" href="">
                            <i class="iconfont icon-xia">
                            </i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="link" href="javascript:;">
                                    <i class="iconfont icon-lajitong">
                                    </i>
                                    <span>
                                        删除会话
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="link" href="javascript:;">
                                    <i class="iconfont icon-heimingdan1">
                                    </i>
                                    <span>
                                        加入黑名单
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="report link" href="javascript:;">
                                    <i class="iconfont icon-iconset03100">
                                    </i>
                                    <span>
                                        举报用户
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <a class="avatar avatar_sm" href="/v2/user">
                    <img alt="" src="/images/photo_03.jpg" />
                </a>
                <a class="name" href="/v2/user">小食神</a>
                <router-link to="/chat/2" class="wrap">
                    <p class="new_abstract single_line">
                        欢迎加入丰富多彩的原创内容社区——懂美味。有任何疑问（如何投稿，专题主编，签约作者）可以随时联系我哟。
                    </p>
                </router-link>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {

  name: 'Chats',

  created(){
  	var  api=window.tokenize('/api/notification/chats')
  	var vm=this;
  	window.axios.get(api).then(function(response){
  		vm.chats=response.data.data;
  		vm.last_page=response.data.last_page;
  	});
  },

  data () {
    return {
           chats:[],
           page:1,
           lastPage:null
    }
  }
}
</script>
<style lang="scss" scoped="">
	#chats {
        .chats_list {
            li {
                border-top: 1px solid #f0f0f0;
                position: relative;
                .pull-right {
                    font-size: 13px;
                    margin: 20px 20px 0 0;
                    .time {
                        color: #969696;
                    }
                    .operation {
                        .icon-xia {
                            color: #969696;
                            font-size: 14px;
                        }
                        .dropdown-menu {
                            right: 15px;
                            li {
                                border: none !important;
                            }
                        }
                    }
                }
                .avatar {
                    float: left;
                    margin: 20px 10px 20px 20px;
                    position: relative;
                    .badge {
                        position: absolute;
                        top: -1px;
                        right: -5px;
                    }
                }
                .name {
                    position: absolute;
                    top: 25px;
                    font-size: 15px;
                }
                .wrap {
                    display: block;
                    padding: 20px 20px 20px 0;
                    min-height: 88px;
                    .new_abstract {
                        margin: 28px 0 0;
                    }
                }
            }
        }
    }
</style>