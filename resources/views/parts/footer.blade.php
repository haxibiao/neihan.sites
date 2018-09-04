<!-- 网站底部 -->
<footer id="footer">
    <div>
        <a target="_blank" href="/article/guanyuwomen">关于{{ config('app.name') }}</a><em> · </em>
        <a target="_blank" href="/article/lianxiwomen">联系我们</a><em> · </em>
        <a target="_blank" href="">{{ config('app.name') }}出版</a><em> · </em>
        <a target="_blank" href="/sitemap" title="站点地图">站点地图</a><em> · </em>
        <a target="_blank" href="/article/bangzhuzhongxin">帮助中心</a><em> · </em>
        <a target="_blank" href="/user">全部作者</a>
        
    </div>
    <div class="icp">
        <div>
            ©2017-2018 {{ env('COMPANY') }} / {{ config('app.name') }} / {{ env('ICPB') }}
            <a target="_blank" href="" alt="Smrz">
                <img src="/images/smrz.png" alt="实名认证">
            </a> / 
            <a target="_blank" href="" alt="Zggsrz">
                <img src="/images/yyzz.png" alt="电子安全监督">
            </a> 
        </div>
        公安备案号:<a target="_blank" href="">{{ env('ICPGA') }} / </a>
        举报电话：028-69514351, 联系网站管理 <a href="mailto:{{ "admin@".get_domain() }}">{{ "admin@".get_domain() }}</a>
    </div>
    <div>
        友情链接: @include('parts.friend_links') <a target="_blank" href="/">{{ config('app.name') }}</a>
    </div>
</footer>