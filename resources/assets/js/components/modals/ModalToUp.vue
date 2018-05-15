<template>
	    <div class="modal fade modal-to-up">
	        <div class="modal-dialog">
	            <div class="modal-content">
                <form :action="getPayUrl" method="GET">
	                <div class="modal-header">
	                    <button type="button" data-dismiss="modal" class="close">×</button>
	                    <h4 class="modal-title">充值</h4>
                  </div>
	                <div class="modal-body">
                      <div class="money-input">
                        <label>充值金额</label>
                        <input name="amount" type="number" class="input-style" placeholder="10.00" min="10" max="5000" v-model="money">
                      </div>
                      <div class="method">
                    		<span>选择支付方式</span>
                        <input type="radio" value="alipay" checked="checked">
                        <img src="/images/alipay.png" class="alipay"> 
                      </div>
	                </div>
	                <div class="modal-footer">
	                    <button class="btn-base btn-handle btn-hg" type="submit">确认支付</button>
	                </div>
                </form>
	            </div>
	        </div>
	    </div>
</template>

<script>
export default {

  name: 'ModalToUp',

  computed: {
    getPayUrl() {
      //目前充值只支持支付宝
      return '/alipay/wap/pay';
    }
  },

  data () {
    return {
      money: 10
    }
  }
}
</script>

<style lang="scss" scoped>
.modal.modal-to-up {
  .modal-dialog {
    .modal-content {
      .modal-body {
        font-size: 14px;
        padding: 40px 40px 20px;
        .money-input {
        	position: relative;
        	margin-bottom: 40px;
        	label {
        		position: absolute;
        		top: 15px;
        		left: 20px;
        		font-weight: 400;
        	}
        	input {
    		    padding-left: 95px;
    		    color: #646464;
    		    font-size: 32px;
    		    line-height: 38px;
        	}
        }
        .method {
        	span {
        		margin-right: 10px;
        		font-size: 14px;
        		vertical-align: middle;
        	}
      		img {
      			height: 28px;
      			margin: 0 30px 0 10px;
      		}
        }
      }
      .modal-footer {
      	padding: 20px 15px 40px;
      	border: none;
      	background-color: transparent;
      	text-align: center;
      }
    }
  }
}
</style>