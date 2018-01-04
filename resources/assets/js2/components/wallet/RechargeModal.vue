<template>
	<!-- 钱包页充值 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="rechargeModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    充值
	                </h4>
	            </div>
	            <div class="modal-body">
	                <form>
	                	<div class="money_input_box money_input_box_restyle">
	                		<label>充值金额</label>
	                		<input type="text" placeholder="0.00" class="form-control" />
	                	</div>
	                	<div class="method">
	                		<span>选择支付方式</span>
	                		<input type="radio" value="alipay" checked />
	                		<img src="/images/alipay_1.png" class="alipay" />
	                		<img src="/images/alipay_2.png" class="alipay night" />
	                	</div>
	                </form>
	            </div>
	            <div class="modal-footer">
	            	<div class="btn_base btn_follow btn_follow_lg">确认支付</div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'RechargeModal',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#rechargeModal {
		.modal-dialog {
			transform: translate(-50%, -50%);
		    .modal-body {
				font-size: 14px;
				padding: 40px 40px 20px;
				form {
					margin: 0 0 20px;
					div {
						line-height: 30px;
					}
					.money_input_box_restyle {
						&:first-child {
							.form-control {
								font-size: 32px;
							}
						}
					}
					.method {
						span {
							margin-right: 10px;
							vertical-align: middle;
						}
						.alipay {
							min-width: 79.33px;
							height: 28px;
							margin: 0 30px 0 10px;
						}
						.night {
							display: none;
						}
					}
				}
		    }
		    .modal-footer {
		    	border: none;
		    	padding: 0 15px 40px;
		    	text-align: center;
		    	.btn_follow_lg {
		    		cursor: pointer;
		    		padding: 12px;
		    	}
		    }
		}
	}
</style>