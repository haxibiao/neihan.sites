<template>
    <a :data-content="dataContent" :data-placement="placement" data-html="true" data-original-title="" data-toggle="popover" data-trigger="focus" role="button" tabindex="0" title="">
        <i :class="iconClass"></i>
        <slot></slot>
    </a>
</template>
<script>
    export default {

  name: 'ShareModal',

  props:['placement'],

  computed: {
    iconClass(){
        return this.placement=='left'?'iconfont icon-fenxiang1':'iconfont icon-fenxiang1';
    },
    dataContent() {
        return `<ul class='popover_share_menu'>
                    <li>
                        <a href='javascript:;'>
                            <i class='iconfont icon-weixin1'></i>
                            <span>分享到微信</span>
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            <i class='iconfont icon-sina'></i>
                            <span>分享到微博</span>
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            <i class='iconfont icon-qq1'></i>
                            <span>分享到空间</span>
                        </a>
                    </li>
                </ul>`;
    }
  },

  data () {
    return {

    }
  }
}
</script>
<style lang="scss" scoped="">
</style>