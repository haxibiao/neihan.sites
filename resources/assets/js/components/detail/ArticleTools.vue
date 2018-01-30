<template>
    <div>
        <div class="side_tool article_top">
            <ul>
                <li class="writer" data-container="body" data-title="作者" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="tooltipHide">
                    <writer class="function_button" placement="left"></writer>
                </li>
                <li class="catalog" data-container="body" data-title="目录" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="tooltipHide">
                    <catalog class="function_button" placement="left"></catalog>
                </li>
                <li data-container="body" data-title="编辑器" data-placement="left" data-toggle="tooltip" data-trigger="hover" @click="showEditor">
                    <a href="javascript:;" class="function_button">
                        <i class="iconfont icon-xie">
                        </i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="editor_container" v-if="isEditor">
            <div class="editor_box simditor_submi">
                <div class="clearfix">
                    <div class="close" @click="closeBtn">×</div>
                </div>
                <editor></editor>
                <div class="submitbar">
                    <div class="pull-right">
                        <button class="btn_base btn_creation btn_followed_xs">发表</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

  name: 'ArticleTools',

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

  },

  data () {
    return {
        isEditor:false,
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