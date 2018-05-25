<template>
    <a class="share-button" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" :data-placement="placement" data-html="true"
      :data-content="dataContent"  data-original-title="" title="">
      <i :class="iconClass"></i>
      <slot></slot>
    </a>
</template>

<script>
export default {

  name: 'Share',

  props:['placement', 'url', 'title', 'author','articleId'],

  mounted() {
    $('[data-toggle="popover"]').popover();
  },

  computed: {
    iconClass(){
      return this.placement=='left'?'iconfont icon-fenxiang1':'more iconfont icon-fenxiang1';
    },
    dataContent() {
      return `<ul class='popover-share-menu'>
                  <li><a href='/share/weixin/?url=${this.url}&article_id=${this.articleId}' class='link' target="_blank"><i class='iconfont icon-weixin1 weixin'></i><span>分享到微信</span></a></li>
                  <li><a href="javascript:void((function(s,d,e,r,l,p,t,z,c){var%20f='http://v.t.sina.com.cn/share/share.php?appkey=1881139527',u=z||d.location,p=['&amp;url=',e(u),'&amp;title=',e(t||d.title),'&amp;source=',e(r),'&amp;sourceUrl=',e(l),'&amp;content=',c||'gb2312','&amp;pic=',e(p||'')].join('');function%20a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width-440)/2,',top=',(s.height-430)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent,'','','', '推荐 @${this.author} 的文章《${this.title}》（ 分享自 @懂美味 ）','${this.url}?source=weibo','页面编码gb2312|utf-8默认gb2312'));" class='link'><i class='iconfont icon-sina weibo'></i><span>分享到微博</span></a></li>
                  <li><a href='javascript:;' class='link'><i class='iconfont icon-qq1 qqzone'></i><span>分享到空间</span></a></li>
                </ul>`;
    }
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>

</style>