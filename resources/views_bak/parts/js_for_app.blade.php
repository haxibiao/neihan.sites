@if(is_in_app())
<script src="/js/rnwi-browser.js"></script>

<script>
    var invoke = window.WebViewInvoke;

    var goVideoDetail = invoke.bind('goVideoDetail');
    function appGoVideoDetail(id, title) {
        goVideoDetail(id, title)
    }

    var goDetail = invoke.bind('goDetail');
    function appGoDetail(id, title) {
        goDetail(id, title)
    }

    var goCategory = invoke.bind('goCategory');
    function goCategoryInApp(cate, cate_name) {
        goCategory(cate, cate_name)
    }

    var webInitialize = invoke.bind('webInitialize');
    $(function(){
        var height = $(document).height();
        webInitialize(height);
    });
</script>
@endif