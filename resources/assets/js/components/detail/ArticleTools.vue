<template>
    <div>
        <div class="side_tool article_top">
            <ul>
                <li  class="writer" data-container="body" data-title="作者" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="tooltipHide">
                    <writer class="function_button" placement="left" :article-id="this.articleId"></writer>
                </li>
                <li class="catalog" data-container="body" data-title="目录" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="tooltipHide">
                    <catalog class="function_button" placement="left"></catalog>
                </li>
                <li  data-container="body" data-title="编辑器" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="showEditor">
                    <a href="javascript:;" class="function_button">
                        <i class="iconfont icon-xie">
                        </i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="editor_container" v-if="isEditor">
          <form @submit.prevent="sendComment">
            <div class="editor_box simditor_submi">
                <div class="clearfix">
                    <div class="close" @click="closeBtn">×</div>
                </div>
                <editor v-model="report.body"></editor>
                <div class="submitbar">
                    <div class="pull-right">
                        <button type="submit" class="btn_base btn_creation btn_followed_xs">发表</button>
                    </div>
                </div>
            </div>
           </form>
        </div>
    </div>
</template>

<script>
export default {

  name: 'ArticleTools',

  props:['articleId','userId','articleUserId'],

  methods:{

    tooltipHide() {
        $(".tooltip").hide();
    },

    showEditor(){
        this.isEditor=this.isEditor? false:true ;
    },

    closeBtn() {
        this.isEditor = false;
    },
    isSelf(){
        return this.userId == this.articleUserId;
    },

    sendComment(){
        var api=window.tokenize('/api/comment');
        var vm=this;
        var formdata=new FormData();
        formdata.append('commentable_id',this.articleId);
        formdata.append('body',report.body);
        formdata.append('commentable_type','articles_author');
        window.axios.post(api).then(function(response){
             if(response.status==200){
                window.location.reload();
             }
        });
    }

  },

  data () {
    return {
        isEditor:false,
        report:[]

    }
  }
}
</script>

<style lang="scss" scoped>
.editor_container {
    width: 100%;
    height: 393px;
    overflow: auto;
    position: fixed;
    bottom: 0;
    left: 0;
    z-index: 1030;
    background-color: transparent;
    .editor_box {
        width: 95%;
        background-color: #d96a5f;
        margin: 0 auto;
        padding: 13px 10px;
        @media screen and (min-width: 1200px) {
            width: 760px;
        }
        @media screen and (min-width: 992px) and (max-width: 1200px) {
            width: 615px;
        }
        @media screen and (min-width: 768px) and (max-width: 992px) {
            width: 710px;
        }
        .clearfix {
            margin-bottom: 5px;
            .close {
                color: #fff;
                opacity: 0.9;
                &:hover {
                    opacity: 1.5;
                }
            }
        }
    }
    @media screen and (max-width: 512px) {
        height: 433px;
    }
}
</style>