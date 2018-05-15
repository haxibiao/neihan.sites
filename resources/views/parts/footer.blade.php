<!-- 网站底部 -->
<footer id="footer">
    <div>
        <a target="_blank" href="/">关于{{ config('app.name') }}</a><em> · </em>
        <a target="_blank" href="">联系我们</a><em> · </em>
        <a target="_blank" href="">加入我们</a><em> · </em>
        <a target="_blank" href="">{{ config('app.name') }}出版</a><em> · </em>
        <a target="_blank" href="/sitemap" title="站点地图">站点地图</a><em> · </em>
        <a target="_blank" href="{{ config('editor.help') }}">帮助中心</a><em> · </em>
        <a target="_blank" href="/user">全部作者</a>
        
    </div>
    <div class="icp">
        <div>
            ©2012-2018 {{ env('COMPANY') }} / {{ config('app.name') }} / {{ env('ICPB') }}/{{ config('editor.appId.recordId') }}
            <a target="_blank" href="" alt="Smrz">
                <img src="/images/smrz.png" alt="">
            </a> 
        </div>
{{--         <a target="_blank" href="">{{ env('ICPGA') }} / </a>
        举报电话：028-69514351 / --}}
        <a target="_blank" href="" alt="Zggsrz">
            <img src="/images/yyzz.png" alt="">
        </a> 
    </div>
    <div>
        友情链接: @include('parts.friend_links') <a target="_blank" href="/">{{ config('app.name') }}</a>
    </div>
</footer>