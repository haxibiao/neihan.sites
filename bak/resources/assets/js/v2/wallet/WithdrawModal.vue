<template>
	<!-- 钱包页提现 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="withdrawModal" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    提现
	                </h4>
	            </div>
	            <div class="modal-body">
	                <form>
	                	<div class="money_input_box money_input_box_restyle">
	                		<label>提现金额：</label>
	                		<input type="text" placeholder="0.00" class="form-control" />
	                		<span class="amount">元</span>
	                	</div>
	                	<div class="money_input_box money_input_box_restyle">
	                		<label>支付宝账号：</label>
	                		<input type="text" placeholder="填入收款支付宝账号邮箱或手机号" class="form-control" />
	                	</div>
	                	<div class="money_input_box money_input_box_restyle">
	                		<label>收款人真实姓名：</label>
	                		<input type="text" placeholder="支付宝账户对应的真实姓名，如果错误无法提现" class="form-control" />
	                	</div>
	                </form>
	                <div>* 暂时仅支持提现至支付宝账户。</div>
	                <div>
	                	<strong>* 提现申请提交后 3 个工作日到账</strong>
	                </div>
	                <div>* 提现申请提交后暂时无法撤销。</div>
	                <div>* 每次提现收取 5% 手续费</div>
	            </div>
	            <div class="modal-footer">
	            	<div class="btn_base btn_follow btn_follow_lg">确认提现</div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'WithdrawModal',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#withdrawModal {
		.modal-dialog {
		    .modal-body {
				font-size: 14px;
				padding: 40px 40px 20px;
				form {
					margin: 0 0 20px;
					.money_input_box_restyle {
						margin-bottom: 0;
						line-height: 30px;
						.form-control {
							border-radius: 0;
							padding-left: 135px;
						}
						&:first-child {
							.form-control {
								border-radius: 4px 4px 0 0;
								font-size: 32px;
							}
						}
						&:last-child {
							margin-bottom: 20px;
							.form-control {
								border-radius: 0 0 4px 4px;
							}
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