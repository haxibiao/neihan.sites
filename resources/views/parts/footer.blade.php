<!-- 网站底部 -->
<footer id="footer">
    <div class="icp">
        <div>
            <a target="_blank" href="http://beian.miit.gov.cn/ ">{{ seo_value('备案','copyright') }}</a><br>
            <a target="_blank" href="http://beian.miit.gov.cn/ ">{{ seo_value('备案','备案号') }} 邮箱：support@beian.gov.cn</a><br>
            <a target="_blank" href="http://beian.miit.gov.cn/ ">
                <img src="http://cos.haxibiao.com/images/yyzz.png" alt="电子安全监督">
                {{ seo_value('备案','公安网备号')  }}
            </a><br>

            {{--©2017-2018 {{ env('COMPANY') }} / {{ config('app.name_cn') }} / {{ env('ICPB') }}--}}
            {{--<a target="_blank" href="" alt="Smrz">--}}
            {{--<img src="/images/smrz.png" alt="实名认证">--}}
            {{--</a> / --}}
            {{--<a target="_blank" href="" alt="Zggsrz">--}}
            {{--<img src="/images/yyzz.png" alt="电子安全监督">--}}
            {{--</a> --}}

        </div>

        {{--公安备案号:<a target="_blank" href="">{{ env('ICPGA') }} / </a>--}}
        {{--举报电话：028-69514351, 联系网站管理 <a href="mailto:{{ "admin@".get_domain() }}">{{ "admin@".get_domain() }}</a>--}}
    {{--</div>--}}
    {{--<div>--}}
        {{--友情链接: @include('parts.friend_links') <a target="_blank" href="/">{{ config('app.name_cn') }}</a>--}}


    </div>
</footer>
