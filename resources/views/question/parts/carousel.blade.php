<div id="carousel-example-generic" class="carousel slide mini" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner" role="listbox">
{{--     <div class="item active">
      <img src="/images/carousel001.jpg" alt="...">
      <div class="carousel-caption">
      </div>
    </div>
    <div class="item">
      <img src="/images/carousel002.jpg" alt="...">
      <div class="carousel-caption">
      </div>
    </div>
    <div class="item">
      <img src="/images/carousel003.jpg" alt="...">
      <div class="carousel-caption">
      </div>
    </div> --}}
  </div>
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
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
                       $('.carousel-inner').append(`<div class="item active">
              
                           <img src="${data[i].image1}"/>
                 
                              <div class="carousel-caption">
                              </div>
                        </div>`);
                    }else{
                     $('.carousel-inner').append(`<div class="item">
                       
                           <img src="${data[i].image1}"/>
               
                        <div class="carousel-caption">
                        </div>
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
