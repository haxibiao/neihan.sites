<!-- 网站底部 -->
<footer id="footer">
    <div>
        <a target="_blank" href="/blog">{{ config('app.name_cn') }}官方博客</a><em> · </em>
        <a target="_blank" href="/pivacy-and-policy">隐私政策</a><em> · </em>
        <a target="_blank" href="">{{ config('app.name_cn') }}出版</a><em> · </em>
        <a target="_blank" href="/sitemap" title="站点地图">站点地图</a><em> · </em>
        {{-- <a target="_blank" href="/help">帮助中心</a><em> · </em> --}}
        <a target="_blank" href="/user">全部作者</a>
        
    </div>
    <div class="icp">
        <div>
            ©2017-2018 {{ env('COMPANY') }} / {{ config('app.name_cn') }} / {{ env('ICPB') }}
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
        友情链接: @include('parts.friend_links') <a target="_blank" href="/">{{ config('app.name_cn') }}</a>
    </div>
</footer>