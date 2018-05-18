{{-- 轮播图 --}}
<div class="carousel slide interlocution_slide" data-ride="carousel" id="carousel-example-generic">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li class="active" data-slide-to="0" data-target="#carousel-example-generic">
        </li>
        <li data-slide-to="1" data-target="#carousel-example-generic">
        </li>
        <li data-slide-to="2" data-target="#carousel-example-generic">
        </li>
    </ol>
    <!-- Wrapper for slides -->
    <div id='poster' class="carousel-inner" role="listbox">
{{--         <div class="item active">
            <img src="/images/carousel001.jpg"/>
        </div>

        <div class="item">
            <img src="/images/carousel002.jpg"/>
        </div>

        <div class="item">
            <img src="/images/carousel003.jpg"/>
        </div> --}}
    </div>
    <!-- Controls -->
    <a class="left carousel-control" data-slide="prev" href="#carousel-example-generic" role="button">
        <span aria-hidden="true" class="symbol">
            <i class="iconfont icon-zuobian">
            </i>
        </span>
        <span class="sr-only">
            Previous
        </span>
    </a>
    <a class="right carousel-control" data-slide="next" href="#carousel-example-generic" role="button">
        <span aria-hidden="true" class="symbol">
            <i class="iconfont icon-youbian">
            </i>
        </span>
        <span class="sr-only">
            Next
        </span>
    </a>
</div>
<script type="text/javascript">
    window.onload=function(){
        $.ajax({
            type:"get",
            dataType:"json",
            url:'/api/commend-question',

            success:function(data){
                for(var i in data){
                    if(i==0){
                       $('#poster').append(`<div class="item active">
                        <a href="/question/${data[i].id}" >
                           <img src="${data[i].image1}"/>
                        </a>
                        </div>`);
                    }else{
                     $('#poster').append(`<div class="item">
                        <a href="/question/${data[i].id}" >
                           <img src="${data[i].image1}"/>
                        </a>
                        </div>`);
                    }
                }
            },

            error:function(data){
                console.log('ajax requestion error');
            },
        });
    }
</script>