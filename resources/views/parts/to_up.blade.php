{{-- <link rel="stylesheet" href="/fonts/iconfont.css"> --}}

<!--向上小火箭-->
<div class="toUp-rocket iconfont icon-feiji3"></div>


<style>
    /*向上小火箭*/
.toUp-rocket {
  position: fixed;
  right: 20px;
  bottom: 40px;
  width: 50px;
  height: 80px;
  border-radius: 5px;
  background-color: rgba(95, 180, 255, 0.3);
  font-size: 50px;
  color: #F47723;
  cursor: pointer;
  display: none;
}
.toUp-rocket:hover {
  background-color: rgba(95, 180, 255, 0.6);
}
</style>

<script type="text/javascript">
  
  $(function() {
    //2-底部小火箭
    $(window).on("scroll",function () {
        if($(window).scrollTop()>1000) {
            $(".toUp-rocket").fadeIn(300);
        }else {
            $(".toUp-rocket").fadeOut(300);
        }
    });
    $(".toUp-rocket").on("click",function () {
        $("body,html").animate({"scrollTop": 0 }, 1000);
    });
  });

</script>