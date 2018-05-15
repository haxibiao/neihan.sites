<template>
	<div class="modal fade modal-withdraw">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" data-dismiss="modal" class="close">×</button>
	                <h4 class="modal-title">提现</h4></div>
	            <div class="modal-body">
	                <form>
	                    <div class="money-input">
	                        <label>提现金额: </label>
	                        <input type="text" class="input-style" placeholder="0.00"> <span class="amount">元</span></div>
	                    <div class="money-input">
	                        <label>支付宝账号: </label>
	                        <input type="text" class="input-style" placeholder="填入收款支付宝账号邮箱或手机号">
	                    </div>
	                    <div class="money-input">
	                        <label>收款人真实姓名: </label>
	                        <input type="text" class="input-style" placeholder="支付宝账户对应的真实姓名，如果错误无法提现">
	                    </div>
	                </form>
	                <div>* 暂时仅支持提现至支付宝账户。</div>
	                <div><strong>* 提现申请提交后 3 个工作日内到账。</strong></div>
	                <div>* 提现申请提交后暂时无法撤销。</div>
	                <div>* 每次提现收取 5% 手续费。</div>
	            </div>
	            <div class="modal-footer">
	                <button class="btn-base btn-handle btn-hg">确认提现</button>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'ModalWithdraw',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.modal.modal-withdraw {
  .modal-dialog {
    .modal-content {
      .modal-body {
        font-size: 14px;
        padding: 40px 40px 20px;
        .money-input {
        	position: relative;
        	label {
        		position: absolute;
        		top: 15px;
        		left: 20px;
        		font-weight: 400;
        	}
          .amount {
            position: absolute;
            top: 14px;
            right: 20px;
          }
        	input {
    		    padding-left: 135px;
    		    color: #646464;
    		    font-size: 14px;
    		    line-height: 38px;
            border-bottom: none;
            border-radius: 0;
        	}
          &:first-child {
            input {
              font-size: 32px;
              border-radius: 4px 4px 0 0;
            }
          }
          &:last-child {
            margin-bottom: 20px;
            input {
              border-radius: 0 0 4px 4px;
              border-bottom: 1px solid #c8c8c8;;
            }
          }
        }
      }
      .modal-footer {
      	padding: 0 15px 40px;
      	border: none;
      	background-color: transparent;
      	text-align: center;
      }
    }
  }
}
</style>