<template>
	<div class="modal fade modal-share-weixin">
	    <div class="modal-dialog">
	        <div class="modal-content">
	        	<div class="modal-header">
	        	    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
	        	</div>
	            <div class="modal-body">
	             <h5>打开微信“扫一扫”，打开网页后点击屏幕右上角分享按钮</h5>
	             <div class="scan-code">
	              <!--  <img :src="this.qrCode_url" alt="Scan me!"> -->
	             </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'ModalShareWX',

  props:['url','aid'],

  mounted(){ 
      this.getQcode();
  },

  methods:{
        getQcode(){
               var vm=this;
               var myDate = new Date();
               var api='api/share/weixin/?url='+vm.url;
               console.log(api);
               window.axios.get(api).then(function(response){
                   vm.qrCode_url=response.data;
               });
        },
  },
  data () {
    return {
        'qrCode_url':null
    }
  }
}
</script>

<style lang="scss">
.modal-share-weixin {
  .modal-dialog {
    max-width: 360px;
    .modal-content {
      .modal-header {
      	border-bottom-color: transparent;
      }
      .modal-body {
        padding: 20px 60px 30px;
        text-align: center;
        .scan-code {
          img {
            display: block;
            width: 100%;
          }
        }
      }
    }
  }
}
</style>