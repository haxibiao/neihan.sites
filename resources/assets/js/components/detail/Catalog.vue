<template>
	<a :data-content="dataContent" :data-placement="placement" class="share
    _button" data-html="true" data-original-title="" data-toggle="popover" data-trigger="focus" role="button" tabindex="0" title="">
        <i class="iconfont icon-duoxuan"></i>
    </a>
</template>

<script>
export default {

  name: 'Catalog',

    props:['placement'],

  computed: {
    dataContent() {
        return `<ul class='popover_share_menu'>
                    <li>
                        <a href='javascript:;'>
                            第一楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第二楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第三楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第四楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第五楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第六楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第七楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第八楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第九楼
                        </a>
                    </li>
                    <li>
                        <a href='javascript:;'>
                            第十楼
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

<style lang="css" scoped>
</style>