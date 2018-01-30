<template>
    <div class="tool_cabinet">
        <div class="question_bottom">
            <div class="comment_wrap">
                <div class="tool_group">
                    <a href="javascript:;" class="action_btn"　@click="toggle">
                        <i :class="['iconfont',favorited?'icon-shoucang1 like_active':'icon-shoucang']" ></i>
                        <span>收藏问题</span>
                    </a>
                    <a href="javascript:;" class="action_btn" @click="showInvite">
                        <i class="iconfont icon-guanzhu"></i>
                        <span>邀请回答</span>
                    </a>
                    <share class="action_btn" placement="top">
                        <span>分享</span>
                    </share>
                    <a href class="pull-right action_btn">
                        <i class="iconfont icon-jinggao"></i>
                        <span>举报</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="invite_answer" v-if="isInvite">
            <div class="invite_user">
                <div class="invite_status">立即邀请用户，更快获得回答</div>
                <ul class="invite_list">
                    <li class="note_info" v-for="user in uninvited">
                        <div class="author">
                            <a :href="'/user/'+user.id" class="avatar avatar_xs" v-if="!user.invited">
                                <img :src="user.avatar" />
                            </a>
                            <a class="btn_base btn_sign" @click="inviteUser(user)">
                                <i class="iconfont icon-guanzhu"></i>
                                <span>邀请</span>
                            </a>
                            <div class="info_meta">
                                <a :href="'/user/'+user.id" class="headline nickname">
                                    {{ user.name }}
                                </a>
                                <div class="meta single_line">
                                    {{ user.introduction }}
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {

  name: 'QuestionTool',

  props:['questionId'],

  mounted(){
      this.get();
  },

  methods:{
        showInvite(){
        this.isInvite=this.isInvite? false:true;
        },

        api(){
            return window.tokenize('/api/favorite/' + this.questionId + '/questions');
        },
        toggle(){
              var vm=this;
              window.axios.post(this.api()).then(function(response){
                   vm.favorited=response.data;
              });
           },
         get(){
              var vm=this;
              window.axios.get(this.api()).then(function(response){
                 vm.favorited=response.data;
              });
              
              var api=window.tokenize('/api/user/question-'+this.questionId+'-uninvited');
              window.axios.get(api).then(function(response){
                     vm.users = response.data;
                     vm.uninvited = vm.users;
              });
         },
        inviteUser(user) {
        user.invited = 1;
        this.uninvited = _.filter(this.users, ['invited', 0]);
        //ajax get to send question invite
        window.axios.get(window.tokenize('/api/user/'+user.id+'/question-invite/'+this.questionId));
        },

  },

  data () {
    return {
        isInvite:false,
        favorited:null,
        users:[],
        uninvited:[]
    }
  }
}
</script>

<style lang="scss" scoped>
.tool_cabinet {
    .invite_answer {
        margin: 0 0 36px 0;
        overflow: hidden;
        .invite_user {
            border-top: 1px solid #efefef;
            .invite_status {
                height: 50px;
                line-height: 50px;
                color: #666;
                font-size: 14px;
            }
            .invite_list {
                max-height: 338px;
                overflow: auto;
                border-top: 1px solid #f8f8f8;
                .note_info {
                    width: 98%;
                    margin-top: 20px;
                    font-size: 14px;
                    .btn_sign {
                        float: right;
                        margin: 6px 0 0 0;
                        padding: 6px 8px;
                    }
                    .info_meta {
                        padding: 0 100px 0 50px;
                        .headline {
                            font-size: 14px!important;
                        }
                    }
                }
            }
        }
    }
}
</style>