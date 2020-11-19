{{-- 腾讯统计 --}}
<script>
    var _mtac = {};
    (function() {
        var mta = document.createElement("script");
        mta.src = "//pingjs.qq.com/h5/stats.js?v2.0.4";
        mta.setAttribute("name", "MTAH5");
        mta.setAttribute("sid", "500733348");
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(mta, s);
    })();

</script>

{{-- 百度推送 --}}
<script>
    (function() {
        var canonicalURL, curProtocol;
        //Get the <link> tag
        var x = document.getElementsByTagName("link");
        //Find the last canonical URL
        if (x.length > 0) {
            for (i = 0; i < x.length; i++) {
                if (x[i].rel.toLowerCase() == 'canonical' && x[i].href) {
                    canonicalURL = x[i].href;
                }
            }
        }
        //Get protocol
        if (!canonicalURL) {
            curProtocol = window.location.protocol.split(':')[0];
        } else {
            curProtocol = canonicalURL.split(':')[0];
        }
        //Get current URL if the canonical URL does not exist
        if (!canonicalURL) canonicalURL = window.location.href;
        //Assign script content. Replace current URL with the canonical URL
        ! function() {
            var e = /([http|https]:\/\/[a-zA-Z0-9\_\.]+\.baidu\.com)/gi,
                r = canonicalURL,
                t = document.referrer;
            if (!e.test(r)) {
                var n = (String(curProtocol).toLowerCase() === 'https') ?
                    "https://sp0.baidu.com/9_Q4simg2RQJ8t7jm9iCKT-xh_/s.gif" : "//api.share.baidu.com/s.gif";
                t ? (n += "?r=" + encodeURIComponent(document.referrer), r && (n += "&l=" + r)) : r && (n += "?l=" +
                    r);
                var i = new Image;
                i.src = n
            }
        }(window);
    })();

</script>

@php
$ga_id = neihan_ga_measure_id();
@endphp

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga_id }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    console.log('{{ $ga_id }}', '{{ $ga_id }}');
    gtag('config', '{{ $ga_id }}');

</script>
