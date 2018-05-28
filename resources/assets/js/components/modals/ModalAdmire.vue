<template>
	 <div class="modal fade modal-admire">
			<div :class="['modal-dialog',wexinPay?'weixin-pay':'']">
			    <div class="modal-content">
			        <div class="modal-header">
			        	<button type="button" data-dismiss="modal" class="close" @click="rollBack">×</button>
			        </div>
			        <div class="modal-body">
			            <form v-if="!wexinPay" target="_blank" class="new-reward">
			                <div class="reward-intro"><a class="avatar"><img src="/images/xbx.jpg"></a> <span class="intro">支持作者</span> <i class="iconfont icon-jinqian1"></i></div>
			                <div class="main-inputs">
			                    <div class="amount-group">
			                        <input id="option1" type="radio" value="2" v-model="money">
			                        <label for="option1" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">2</span> <span class="piece">元</span></label>
			                        <input id="option2" type="radio" value="5" v-model="money">
			                        <label for="option2" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">5</span> <span class="piece">元</span></label>
			                        <input id="option3" type="radio" value="10" v-model="money">
			                        <label for="option3" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">10</span> <span class="piece">元</span></label>
			                        <input id="option4" type="radio" value="20" v-model="money">
			                        <label for="option4" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">20</span> <span class="piece">元</span></label>
			                        <input id="option5" type="radio" value="50" v-model="money">
			                        <label for="option5" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">50</span> <span class="piece">元</span></label>
			                        <input id="custom-option" type="radio" class="custom-amount" ref="custom" :checked="custom">
			                        <label for="custom-option" class="option" @click="customMoney"><span class="custom-text">自定义</span>
		                            <div class="custom-amount-input">
		                              <i class="iconfont icon-jinqian1 hidden-xs"></i>
		                              <input v-if="custom" type="number" oninput="value = parseInt(Math.min(Math.max(value, 0), 10000), 10)" v-model="money" ref="customInput"> <span class="piece hidden-xs">元</span>
		                            </div>
			                        </label>
			                    </div>
			                    <div class="message">
			                        <textarea placeholder="给Ta留言…" v-model="message"></textarea>
			                    </div>
			                </div>
			                <div class="reward-info">
			                	<span class="amount">￥{{ money }}</span>
			                </div>
			                <div class="choose-pay">
			                    <input id="method1" type="radio" name="pay-method" value="wx-pay" v-model="payMethod">
			                    <label for="method1" class="option"><img src="/images/wechat-pay.png" class="wechat"></label>
			                    <input id="method2" type="radio" name="pay-method" value="alipay" v-model="payMethod">
			                    <label for="method2" class="option"><img src="/images/alipay.png" class="alipay"></label>
			                </div>
			            </form>
			            <div v-show="wexinPay" class="wx-qr-code" >
			                <h3>微信扫码支付</h3>
			                <div class="qr-code" title="">
			                    <img alt="Scan me!" src="/images/code.png">
			                </div>
			                <div class="pay-amount">赞赏金额:<span>￥{{ money }}</span></div>
			            </div>
			        </div>
			        <div v-if="!wexinPay" class="modal-footer">
			            <div class="action">
			                <div class="btn-base btn-theme btn-lg" @click="payNow">立即支付</div>
			            </div>
			        </div>
			    </div>
			</div>
	 </div>
</template>

<script>
export default {
	name: "ModalAdmire",

	props: ["articleId"],

	methods: {
		customMoney() {
			this.money = "";
			this.custom = true;
			var vm = this;
			setTimeout(function() {
				vm.$refs.customInput.focus();
			}, 100);
		},
		selectMoney() {
			this.custom = null;
		},
		payNow() {
			if (this.payMethod == "wx-pay") {
				this.wexinPay = true;
			} else {
				var payUrl = this.payUrl.replace("amount=2", "amount=" + this.money);
				payUrl = payUrl + "&article_id=" + this.articleId;
				if (this.message) {
					payUrl = payUrl + "&message=" + encodeURIComponent(this.message);
				}
				window.location.href = payUrl;
			}
		},
		rollBack() {
			this.wexinPay = null;
		}
	},

	data() {
		return {
			money: 2,
			custom: null,
			payMethod: "wx-pay",
			wexinPay: null,
			message: null,
			payUrl: "/pay?amount=2&type=tip"
		};
	}
};
</script>

<style lang="scss" scoped>
.modal.modal-admire {
	.modal-dialog {
		&.weixin-pay {
			width: 370px;
		}
		.modal-content {
			.modal-header {
				padding: 15px 20px 0;
				border-bottom: none;
			}
			.modal-body {
				height: auto;
				.new-reward {
					margin: 0 auto;
					padding: 0 60px;
					.reward-intro {
						margin-bottom: 20px;
						font-size: 16px;
						.avatar {
							width: 36px;
							height: 36px;
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
					.main-inputs {
						margin: 25px 0;
						.amount-group {
							margin: 0 -5px;
							.option {
								position: relative;
								margin: 0 5px 15px;
								width: 155px;
								height: 56px;
								line-height: 54px;
								border: 1px solid #e6e6e6;
								border-radius: 4px;
								font-weight: 400;
								color: #999;
								cursor: pointer;
								i {
									vertical-align: sub;
								}
								.amount {
									font-size: 28px;
									vertical-align: middle;
								}
								.piece {
									font-size: 13px;
									vertical-align: sub;
								}
								.custom-text {
									font-size: 16px;
								}
								.custom-amount-input {
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
										&::-webkit-inner-spin-button {
											-webkit-appearance: none !important;
											margin: 0;
										}
									}
									.piece {
										position: absolute;
										top: 0;
										right: 10px;
									}
								}
							}
							input {
								display: none;
								&:checked + .option {
									color: #d96a5f;
									border-color: #d96a5f;
									.custom-amount-input {
										z-index: 1;
										opacity: 1;
									}
									.custom-text {
										opacity: 0;
									}
								}
							}
						}
						.message {
							font-size: 14px;
							color: #333;
							textarea {
								font-size: 14px;
							}
						}
					}
					.reward-info {
						font-size: 28px;
						font-weight: 700;
						color: #d96a5f;
					}
					.choose-pay {
						margin: 0 -5px;
						.option {
							margin: 20px 5px 10px;
							width: 155px;
							height: 56px;
							line-height: 54px;
							text-align: center;
							border: 1px solid #e6e6e6;
							border-radius: 4px;
							cursor: pointer;
							img {
								height: 30px;
								&.wechat {
									min-width: 112px;
								}
								&.alipay {
									min-width: 85px;
								}
							}
						}
						input {
							display: none;
							&:checked + .option {
								color: #d96a5f;
								border-color: #d96a5f;
							}
						}
					}
					@media screen and (max-width: 600px) {
						.main-inputs {
							.amount-group {
								.option {
									width: 46%;
									margin: 0 1% 15px;
									height: 50px;
									line-height: 48px;
								}
							}
						}
						.choose-pay {
							.option {
								margin: 20px 1.5% 10px;
								width: 45%;
								height: 50px;
								line-height: 48px;
								img.wechat,
								img.alipay {
									min-width: unset;
									max-width: 90%;
									height: 60%;
								}
								.custom-amount-input {
									input {
										height: 50px;
										line-height: 48px;
									}
								}
							}
						}
					}
				}
				.wx-qr-code {
					display: inline-block;
					h3 {
						margin-bottom: 20px;
						color: #333;
					}
					.qr-code {
						img {
							margin: 0 auto;
							padding: 10px;
							width: 200px;
							background-color: #fff;
						}
					}
					.pay-amount {
						margin: 20px 0;
						color: #787878;
						font-size: 18px;
						span {
							color: #d96a5f;
						}
					}
				}
			}
			.modal-footer {
				padding: 20px 15px;
				border: none;
				background-color: transparent;
				text-align: center;
			}
		}
	}
}
</style>
