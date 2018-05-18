<template>
	<!-- 钱包页提现手续费收取问题 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="whyModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    5%的提现手续费是怎么收取的？
	                </h4>
	            </div>
	            <div class="modal-body">
	                <div>每次提现会收取5%手续费，比如您提现100元，扣除5%手续费后，您的支付宝实际到账金额为95元。</div>
	            </div>
	            <!-- <div class="modal-footer"></div> -->
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'WhyModal',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#whyModal {
		.modal-dialog {
			transform: translate(-50%, -50%);
		    .modal-body {
				font-size: 17px;
				padding: 20px;
				line-height: 30px;
		    }
		}
	}
</style>