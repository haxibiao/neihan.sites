<template>
  <div class="progress blue" v-if="progress!==100">
      <span class="progress-left">
          <span class="progress-bar" ref="left_bar"></span>
      </span>
      <span class="progress-right">
          <span class="progress-bar" ref="right_bar"></span>
      </span>
      <div class="progress-value">{{progress+"%"}}</div>
  </div>
</template>
<script>
   export default {
    name: "Loading",

    props: ["progress"],
     mounted() {
    },
    updated(){
        if(this.progress<50){
            $(this.$refs.right_bar).css("transform", `rotate(${this.progress * 3.6}deg)`);
        }else{
            $(this.$refs.right_bar).css("transform", `rotate(${50 * 3.6}deg)`)
            $(this.$refs.left_bar).css("transform", `rotate(${(this.progress-50) * 3.6}deg)`);
        }
    },
    methods:{
    },
    data() {
        return {
        
        }
    }
};

</script>
<style lang="scss">     
.progress.blue .progress-value{
    color: rgba(217, 106, 95, 1) ;
} 
.progress.blue .progress-bar{
    border-color: rgba(217, 106, 95, 1);
}
 .progress{
    width: 100px;
    height: 100px;
    line-height: 100px;
    background: none;
    margin: 0 auto;
    box-shadow: none;
    position: relative;
}
.progress:after{
    content: "";
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid #fff;
    position: absolute;
    top: 0;
    left: 0;
}
.progress {
	span{
	width: 50%;
    height: 100%;
    overflow: hidden;
    position: absolute;
    top: 0;
    z-index: 1;
	}
}
.progress .progress-left{
    left: 0;
}
.progress .progress-bar{
    width: 100%;
    height: 100%;
    background: none;
    border-width: 2px;
    border-style: solid;
    position: absolute;
    top: 0;
}
.progress .progress-left .progress-bar{
    left: 100%;
    border-top-right-radius: 80px;
    border-bottom-right-radius: 80px;
    border-left: 0;
    -webkit-transform-origin: center left;
    transform-origin: center left;
    transform: rotate(0deg);
    transition: all 1s ease;
}
.progress .progress-right{
    right: 0;
}
.progress .progress-right .progress-bar{
    left: -100%;
    border-top-left-radius: 80px;
    border-bottom-left-radius: 80px;
    border-right: 0;
    -webkit-transform-origin: center right;
    transform-origin: center right;
    transform: rotate(0deg);
}
.progress .progress-value{
    width: 78%;
    height: 78%;
    border-radius: 50%;
    border: 2px solid #ebebeb;
    font-size: 26px;
    line-height: 78px;
    text-align: center;
    position: absolute;
    top: 11%;
    left: 11%;
}
</style>