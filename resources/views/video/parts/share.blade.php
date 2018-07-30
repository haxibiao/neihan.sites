<div class="share-circle">
    <a data-action="weixin-share" data-toggle="tooltip" data-toggle="tooltip" data-placement="top" title="分享到微信">
         <i class="iconfont icon-weixin1 weixin"></i>
    </a>
    <a href="javascript:void((function(s,d,e,r,l,p,t,z,c){var%20f='http://v.t.sina.com.cn/share/share.php?appkey=1881139527',u=z||d.location,p=['&amp;url=',e(u),'&amp;title=',e(t||d.title),'&amp;source=',e(r),'&amp;sourceUrl=',e(l),'&amp;content=',c||'gb2312','&amp;pic=',e(p||'')].join('');function%20a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width-440)/2,',top=',(s.height-430)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent,'','','', '《{{$video->article->title }}》（ 分享自 @爱你城 ）','{{ url('/video/'.$video->article->video_id) }}?source=weibo','页面编码gb2312|utf-8默认gb2312'));" data-toggle="tooltip" data-placement="top" title="分享到微博">
        <i class="iconfont icon-sina weibo"></i>
    </a>
</div>