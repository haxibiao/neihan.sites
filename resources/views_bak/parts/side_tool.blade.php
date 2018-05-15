<article-tool id="{{ $article->id }}"></article-tool>
@push('scripts')
<script>
    $(function() {
        // $('[data-toggle="popover"]').popover();

        // 小火箭
        $(window).on("scroll",function() {
            if($(window).scrollTop()>1000) {
                $(".toup_rocket").fadeIn(300);
            }else {
                $(".toup_rocket").fadeOut(300);
            }
        });
        $(".toup_rocket").on("click",function() {
            $("body,html").animate({"scrollTop": 0 }, 1000);
        });

        $(".share").click(function() {
          $(".tooltip").hide();
        });
    });
</script>
@endpush
