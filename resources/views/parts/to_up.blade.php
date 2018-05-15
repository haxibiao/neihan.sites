{{-- <link rel="stylesheet" href="/fonts/iconfont.css"> --}}

<!--向上小火箭-->
<div class="toUp-rocket" data-toggle="tooltip" data-placement="left" title="回到顶部"><i class="iconfont icon-xia"></i></div>


<style>
    /*向上小火箭*/
.toUp-rocket {
  position: fixed;
  right: 20px;
  bottom: 50px;
  width: 50px;
  height: 50px;
  border: 1px solid #ccc;
  border-radius: 1px;
  background-color: #fff;
  color: #333;
  cursor: pointer;
  display: none;;
  text-align: center;;
  line-height: 50px;
  transition: all .4s ease-in-out;
}
.toUp-rocket:hover {
  background-color: rgba(0,0,0,0.7);
  border: 1px solid rgba(0,0,0,0.7);
  color: #fff;
}
.toUp-rocket i {
  display: inline-block;
  font-size: 30px;
  transform: rotate(180deg);
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