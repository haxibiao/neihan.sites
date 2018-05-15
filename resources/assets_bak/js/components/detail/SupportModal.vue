<template>
	<!-- 详情页赞赏 -->
	<div class="modal fade" id="support_modal">
	    <div :class="['modal-dialog',wexinPay?'weixin_pay':'']">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button class="close" data-dismiss="modal" type="button" @click="rollBack">×</button>
	            </div>
	            <div class="modal-body">
	                <form v-if="!wexinPay" target="_blank" class="new_reward">
	                	<div class="reward_intro">
	                		<a href="javascript:;" class="avatar avatar_ty">
	                			<img :src="user.avatar" />
	                		</a>
	                		<span class="intro">支持作者</span>
	                		<i class="iconfont icon-jinqian1"></i>
	                	</div>
	                	<div class="main_inputs">
	                		<div class="amount_group">
	                			<input id="option1" type="radio" value="2" v-model="money" />
	                			<label for="option1" class="option" @click="selectMoney">
	                				<i class="iconfont icon-jinqian1"></i>
	                				<span class="amount">2</span>
	                				<span class="piece">元</span>
	                			</label>
	                			<input id="option2" type="radio" value="5" v-model="money" />
	                			<label for="option2" class="option" @click="selectMoney">
	                				<i class="iconfont icon-jinqian1"></i>
	                				<span class="amount">5</span>
	                				<span class="piece">元</span>
	                			</label>
	                			<input id="option3" type="radio" value="10" v-model="money" />
	                			<label for="option3" class="option" @click="selectMoney">
	                				<i class="iconfont icon-jinqian1"></i>
	                				<span class="amount">10</span>
	                				<span class="piece">元</span>
	                			</label>
	                			<input id="option4" type="radio" value="20" v-model="money" />
	                			<label for="option4" class="option" @click="selectMoney">
	                				<i class="iconfont icon-jinqian1"></i>
	                				<span class="amount">20</span>
	                				<span class="piece">元</span>
	                			</label>
	                			<input id="option5" type="radio" value="50" v-model="money" />
	                			<label for="option5" class="option" @click="selectMoney">
	                				<i class="iconfont icon-jinqian1"></i>
	                				<span class="amount">50</span>
	                				<span class="piece">元</span>
	                			</label>
	                			<input id="custom_option" type="radio" class="custom_amount" ref="custom" :checked="custom" />
	                			<label for="custom_option" class="option" @click="customMoney">
	                				<span class="custom_text">自定义</span>
	                				<div class="custom_amount_input">
	                					<i class="iconfont icon-jinqian1"></i>
	                					<input v-if="custom" type="number" oninput="value = parseInt(Math.min(Math.max(value, 0), 10000), 10)" v-model="money" ref="customInput" />
	                					<span class="piece">元</span>
	                				</div>
	                			</label>
	                		</div>
	                		<div class="leaving_message">
	                			<div class="textarea_box">
	                				<textarea placeholder="给Ta留言..."></textarea>
	                			</div>
	                		</div>
	                	</div>
	                	<div class="reward_info">
	                		<span class="amount">￥{{ money }}</span>
	                	</div>
	                	<div class="choose_pay">
	<!--                 		<input id="method1" type="radio" name="pay_method" value="wx-pay" v-model="payMethod" />
	                		<label for="method1" class="option">
                				<img src="/images/alipay_3.png" />
                			</label> -->
                			<input id="method2" type="radio" name="pay_method" value="alipay" v-model="payMethod" />
	                		<label for="method2" class="option">
                				<img src="/images/alipay_1.png" />
                			</label>
	                	</div>
	            </form>

	                <div v-show="wexinPay" class="wx_qr_code" >
		                <h3>微信扫码支付</h3>
		                <div class="qr_code" title="">
		                    <img alt="Scan me!" src="/images/scan.jpeg" />
		                </div>
		                <div class="pay_amount">
		                	赞赏金额:
		                	<span>￥{{ money }}</span>
		                </div>
		            </div>
	            </div>
	            <div v-if="!wexinPay" class="modal-footer">
	            	<div class="btn_base btn_pay" @click="skipPay">立即支付</div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'SupportModal',

  props:['userId','articleId'],

  created(){
     this.fechData();
  },

    methods:{
  	customMoney() {
  		this.money='';
  		this.custom = true;
  		var vm = this;
  		setTimeout(function(){
  			vm.$refs.customInput.focus();
  		},100);
  	},

  	selectMoney(value) {
  		this.custom = null;
  	},

  	skipPay() {
  		// if(this.payMethod=='wx-pay') {
  		// 	this.wexinPay = true;
  		// }else {
  			var vm=this;
  		window.location.href='/pay?amount='+vm.money+'&type=tip&article_id='+vm.articleId;
  		// }
  	},

  	rollBack() {
  		this.wexinPay=null;
  	},

  	fechData(){
  		var vm=this;
  		var api=window.tokenize('/api/user/'+vm.userId);
  		window.axios.get(api).then(function(response){
  			 vm.user=response.data;
  		});
  	},
  },

  data () {
    return {
    	money:2,
    	custom: null,
    	payMethod:'wx-pay',
    	wexinPay:null,
    	user:[],
    }
  }
}
</script>

<style lang="scss" scoped>
	#support_modal {
		.modal-dialog {
			text-align: center;
			.modal-header {
				padding: 15px 20px 0;
				border: none;
			}
		    .modal-body {
		    	height: 480px;
	            overflow: auto;
				.new_reward {
					padding: 0 60px;
					input[type="radio"] {
						display: none;
						&:checked+.option {
							color: #d96a5f;
							border-color: #d96a5f;
							.custom_text {
								opacity: 0;
							}
							.custom_amount_input {
								z-index: 1;
								opacity: 1;
							}
						}
					}
					.option {
						position: relative;
						width: 140px;
						height: 56px;
						line-height: 54px;
						border: 1px solid #e6e6e6;
						border-radius: 4px;
						cursor: pointer;
					}
					.reward_intro {
						margin-bottom: 20px;
						font-size: 16px;
						.avatar {
							cursor: default !important;
						}
						.intro {
							margin-right: 5px;
							font-weight: 700;
							vertical-align: middle;
						}
						i {
							color: #d96a5f;
							vertical-align: middle;
						}
					}
					.main_inputs {
						margin: 25px 0;
						.amount_group {
							.option {
								margin: 0 5px 15px;
								font-weight: 400;
								color: #999;
								i {
									font-size: 16px;
									vertical-align: middle;
								}
								.amount {
									font-size: 28px;
									vertical-align: middle;
								}
								.piece {
									font-size: 13px;
									vertical-align: sub;
								}
								.custom_text {
									font-size: 17px;
								}
								.custom_amount_input {
									position: absolute;
									top: 0;
									z-index: -1;
									width: 100%;
									opacity: 0;
									i {
										position: absolute;
										top: 0;
										left: 10px;
									}
									input {
										display: block;
										margin: 0 auto;
										width: 80px;
										height: 54px;
										line-height: 54px;
										border: none;
										font-size: 28px;
										text-align: center;
										background: transparent;
										&::-webkit-outer-spin-button,
							          	&::-webkit-inner-spin-button{
							          		-webkit-appearance: none !important;
							          		margin: 0;
							          	}
									}
									.piece {
										position: absolute;
										top: 0px;
										right: 10px;
									}
								}
							}
						}
						.leaving_message {
							margin: 0 15px;
						}
						@media screen and (max-width: 768px) {
							.amount_group {
								margin: 0 -5px;
								.option {
									width: 46%;
									margin: 0 1% 15px;
								}
							}
							.leaving_message {
								margin: 0;
							}
						}
					}
					.reward_info {
						.amount {
							font-size: 28px;
							font-weight: 700;
							color: #d96a5f;
						}
					}
					.choose_pay {
						.option {
							margin: 20px 5px 10px;
							img {
								height: 30px;
								min-width: 80px;
							}
							@media screen and (max-width: 768px) {
								width: 45%;
								margin: 20px 1.5% 10px;
							}
						}
					}
				}
		    }
		    .modal-footer {
		    	border: none;
		    	padding: 15px 20px;
		    	text-align: center;
		    }
		}
		.weixin_pay {
			width: 370px;
			.modal-body {
				height: auto;
			}
			.wx_qr_code {
	        	display: inline-block;
	        	h3 {
					margin-bottom: 20px;
					color: #333;
	        	}
	        	.qr_code {
					img {
						margin: 0 auto;
						padding: 10px;
						width: 200px;
						background-color: #fff;
					}
	        	}
	        	.pay_amount {
					margin: 20px 0;
					color: #787878;
					font-size: 18px;
					span {
						color: #d96a5f;
					}
	        	}
	        }
		}
	}
</style>