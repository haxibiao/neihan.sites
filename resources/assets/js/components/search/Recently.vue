<template>
	<div class="search_recent">
        <div class="litter_title" v-if="!hideTitle">
            最近搜索
            <a href="javascript:;">
                清空
            </a>
        </div>
        <ul class="search_recent_item_wrap">
            <li>
                <a href="#" target="_blank">
                    <i class="iconfont icon-unie646 browse"></i>
                    <span class="single_line">王者</span>
                    <i class="iconfont icon-cha clear"></i>
                </a>
            </li>
            <li>
                <a href="#" target="_blank">
                    <i class="iconfont icon-unie646 browse"></i>
                    <span class="single_line">王者荣耀王者荣耀王者荣耀王者荣耀</span>
                    <i class="iconfont icon-cha clear"></i>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'Recently',

  props: ['hideTitle'],

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.search_recent {
    .litter_title {
        line-height: 2;
        margin-bottom: 10px;
    }
    .search_recent_item_wrap {
        li {
            a {
                padding: 10px 15px;
                height: 40px;
                line-height: 20px;
                display: inline-block;
                font-size: 14px;
                width: 100%;
                i {
                    font-size: 18px;
                    color: #787878;
                    vertical-align: middle;
                }
                .browse {
                    float: left;
                    margin-right: 10px;
                }
                .clear {
                    float: right;
                    font-size: 12px;
                    font-weight: 700;
                    display: none;
                    &:hover {
                        color: #333;
                    }
                }
                span {
                    display: inline-block;
                    width: 70%;
                }
                &:hover {
                    background-color: #f0f0f0;
                    border-radius: 4px;
                    .clear {
                        display: block;
                    }
                }
            }
        }
    }
}

</style>