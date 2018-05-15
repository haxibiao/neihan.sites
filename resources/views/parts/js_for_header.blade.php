<script>
$(function(){

   // bs3提示框控件
    $('.dropdown-toggle').dropdown();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $('.form-control').focus(function(){
        $(this).siblings('.hot-search-wrp').css({'visibility':'visible','opacity':1});
    });
    $(document).click(function(e){
        if($(e.target).parents('.search-wrapper')[0]!=$('.search-wrapper')[0]){
            $('.hot-search-wrp').css({'visibility':'hidden','opacity':0});
        }
    });
})
</script>