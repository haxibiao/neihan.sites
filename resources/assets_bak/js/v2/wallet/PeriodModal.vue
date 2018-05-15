<template>
	<!-- 钱包页提现遇到的问题 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="periodModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    提现周期是多久？
	                </h4>
	            </div>
	            <div class="modal-body">
	            	<div>提现周期为3个工作日，钱将通过支付宝转账至你的账号，请在支付宝账单里面查看，转账方为：《三河市哈希表计算机技术有限公司》。</div>
	            	<div>若在3个工作日后仍未到账，可以通过下列渠道进行咨询：</div>
	            	<div>微信公众号：爱昵称（ainichengcom）</div>
	            </div>
	            <!-- <div class="modal-footer"></div> -->
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'PeriodModal',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#periodModal {
		.modal-dialog {
		    .modal-body {
				font-size: 17px;
				padding: 20px;
		    }
		}
	}
</style>