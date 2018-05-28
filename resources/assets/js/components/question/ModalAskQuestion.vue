<template>
    <div class="modal fade modal-ask-question">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
				      提问
					</h4>
                </div>
                <div class="modal-body">
    				<form method="post" action="/question" ref="questionForm">
    				<input type="hidden" name="_token" v-model="token">
					<div class="input-question">
						<input-matching name="title"></input-matching>
					</div>
					<div class="textarea-box">
                		<textarea name="background" placeholder='添加问题背景描述（选填)' v-model="description" maxlength='500'></textarea>
                		<span class="word-count">{{ description.length }}/500</span>
					</div>
					<div class="question-setting">
						<div class="setting-group">
							<div>
								<span class="option" for="is_anonymous">匿名提问</span>
								<input type="checkbox" name="is_anonymous" value="1" checked="checked">
							</div>
							<div>
								<span class="option hot" for="is_pay"><i class="iconfont icon-qianbao"></i>付费咨询</span>
								<input type="checkbox" name="is_pay" v-model="whetherPay">
							</div>
						</div>
	                    <div class="pay-group" v-show="whetherPay">
	                       <div class="pay-tip">
		                       	<span class="pay-tip-content">
		                       		使用付费咨询视为您已同意<a href="javascript:;" target="_blank">《问答细则及责任声明》</a>
		                       	</span>
	                       </div>
   		                   <div class="money-amount">
		                       	<div>
		                       		<a class="pull-right refresh" @click="refreshBalance">
										<i class="iconfont icon-shuaxin" ref="fresh"></i>刷新余额
								    </a>
		                       		付费金额 (当前账户余额：￥{{ balance }}) <a target="_blank" href="/wallet" v-if="money>balance"><span class="text-danger">余额不足，去充值</span></a>
		                       	</div>
   		                        <input id="option1" type="radio" name="bonus" value="5" v-model="money">
   		                        <label for="option1" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">5</span> <span class="piece">元</span></label>
   		                        <input id="option2" type="radio" name="bonus" value="10" v-model="money">
   		                        <label for="option2" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">10</span> <span class="piece">元</span></label>
   		                        <input id="option3" type="radio" name="bonus" value="50" v-model="money">
   		                        <label for="option3" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">50</span> <span class="piece">元</span></label>
   		                        <input id="option4" type="radio" name="bonus" value="100" v-model="money">
   		                        <label for="option4" class="option" @click="selectMoney"><i class="iconfont icon-jinqian1"></i> <span class="amount">100</span> <span class="piece">元</span></label>
   		                        <input id="custom-option" type="radio" name="bonus" class="custom-amount" ref="custom" :checked="custom">
   		                        <label for="custom-option" class="option" @click="customMoney"><span class="custom-text">自定义</span>
   	                            <div class="custom-amount-input">
   	                              <i class="iconfont icon-jinqian1 hidden-xs"></i>
   	                              <input v-if="custom" type="number" name="bonus" oninput="value = parseInt(Math.min(Math.max(value, 0), 10000), 10)" v-model="money" ref="customInput"> <span class="piece hidden-xs">元</span>
   	                            </div>
   		                        </label>
   		                   </div>
	                       <div class="expiration-date">
		                       	<div>悬赏日期</div>
		                       	<span class="option">一天</span>
		                       	<input type="radio" name="deadline" value="1" v-model="expiration_date">
		                       	<span class="option">三天</span>
		                       	<input type="radio" name="deadline" value="3" v-model="expiration_date">
		                       	<span class="option">七天</span>
		                       	<input type="radio" name="deadline" value="7" v-model="expiration_date">
		                       	<span class="option">不限制</span>
		                       	<input type="radio" name="deadline" value="0" v-model="expiration_date">
	                       </div>		
	                    </div>
					</div>

			        <input type="hidden" name="user_id" :value="user.id">
			        <input v-if="top3Imgs.length>0" name="image1" type="hidden" :value="top3Imgs[0].img">
			        <input v-if="top3Imgs.length>1" name="image2" type="hidden" :value="top3Imgs[1].img">
			        <input v-if="top3Imgs.length>2" name="image3" type="hidden" :value="top3Imgs[2].img">
			        </form>

                    <div class="img-selector">
                    	<div :class="['ask-img-header',top3Imgs.length>0?'bigger':'']">添加图片<span class="desc">（最多3张）</span></div>
						<div class="img-preview">
							<div class="img-preview-item" v-for="item in top3Imgs">
								<img :src="item.img" alt="" class="as-height">
								<div class="img-del" @click="deleteImg(item)"><i class="iconfont icon-cha"></i></div>
							</div>
						</div>
						<div class="tab-header">
							<ul>
								<li :class="tabActive=='free'?'tab-header-actived':''" @click="tabSwitch('free')">免费素材库</li>
								<li :class="tabActive=='file'?'tab-header-actived':''" @click="tabSwitch('file')">本地上传</li>
							</ul>
						</div>
						<div class="tab-body">
							<div class="tab-body-item" v-show="tabActive=='free'">
								<div class="material-search">
									<div class="search-icon"><i class="iconfont icon-sousuo"></i></div>
									<input type="text" class="search-input" placeholder="搜索免费图片库" v-model="query" @keyup.enter="searchImages">
									<input type="button" value="搜索" class="search-submit" @click="searchImages">
								</div>
								<div class="img-container">
									<div class="img-container-outer">
										<div class="img-tip">
											<span class="img-tip-content">
												使用图库视为您已同意<a href="javascript:;" target="_blank">《图片许可使用协议》</a>，如不同意，请停止使用图库。
											</span>
		
										</div>
										<div class="img-list">
											<div v-for="img in imgItems" :class="['img-item', img.selected ? 'img-item-check':'']" @click=selectImg(img)>
												<img :src="img.img" :alt="img.title" :title="img.title">
												<div class="img-check"><i class="iconfont icon-weibiaoti12"></i></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-body-item" v-show="tabActive=='file'">
								<div class="img-upload-field">
								    <div class="img-upload-btn">
								    	<i class="iconfont icon-tougaoguanli"></i>
								    	<span class="img-click-here">点击此处上传图片</span>
								        <div class="img-file">
								            <input type="file" @change="upload" multiple>
								        </div>
								    	<span class="img-limit">支持图片拖拽上传</span>
								    </div>
								    <div class="img-loading" style="display: none;">
								        <div class="img-progress">
								        	<span class="img-progress-bar" style="width: 100%;"></span>
								        	<span class="img-progress-num">图片上传中...</span>
								        </div>
								        <i class="iconfont icon-ask_close"></i>
								    </div>
								</div>
							</div>
						</div>
                    </div>
                </div>
                <footer class="clearfix">
                	<a v-if="whetherPay && balance < money" href="/wallet" class="btn-base btn-theme btn-md pull-right" target="_blank">充值</a>
                	<button v-else class="btn-base btn-handle btn-md pull-right" @click="submit">提交</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from "../../plugins/Dropzone";

export default {
	name: "ModalAskQuestion",

	computed: {
		token() {
			return window.csrf_token;
		},
		user() {
			return window.user;
		}
	},

	mounted() {
		Dropzone($(".img-upload-field")[0], this.dragDropUpload);
		this.fetchImages();
	},

	methods: {
		refreshBalance() {
			//TODO:: 奇怪这个不转动...
			this.counter++;
			$(this.$refs.fresh).css("transform", `rotate(${360 * this.counter}deg)`);

			var _this = this;
			axios.get(tokenize("/api/user")).then(function(response) {
				_this.balance = parseInt(response.data.balance);
			});
		},
		submit() {
			this.$refs.questionForm.submit();
		},
		dragDropUpload(fileObj, params) {
			if (this.filesCount >= 3) {
				return;
			}
			this._upload(fileObj);
			this.filesCount++;
		},
		upload(e) {
			for (var i = 0; i < e.target.files.length; i++) {
				if (this.filesCount >= 3) {
					break;
				}
				var fileObj = e.target.files[i];
				this._upload(fileObj);
				this.filesCount++;
			}
		},
		_upload(fileObj) {
			var api = window.tokenize("/api/image/save");
			var _this = this;
			let formdata = new FormData();
			formdata.append("from", "question");
			formdata.append("photo", fileObj);
			let config = {
				headers: {
					"Content-Type": "multipart/form-data"
				}
			};
			window.axios.post(api, formdata, config).then(function(response) {
				_this.imgItems.push({
					img: response.data,
					selected: 1
				});
				_this.top3Imgs = _this.selectedImgs();
			});
		},
		tabSwitch(tab) {
			this.tabActive = tab;
		},
		selectedImgs() {
			return _.filter(this.imgItems, ["selected", 1]);
		},
		selectImg(item) {
			if (!item.selected && this.selectedImgs().length >= 3) {
				return;
			}
			item.selected = item.selected ? 0 : 1;
			this.top3Imgs = this.selectedImgs();
			item.selected ? this.filesCount++ : this.filesCount--;
		},
		deleteImg(item) {
			item.selected = 0;
			this.top3Imgs = this.selectedImgs();
			this.filesCount--;
		},
		searchImages(e) {
			var _this = this;
			var api = window.tokenize("/api/image?q=" + this.query);
			window.axios.get(api).then(function(response) {
				var images = response.data.data;
				for (var i in images) {
					var image = images[i];
					var imgs = [];
					imgs.push({
						img: image.path,
						title: image.title,
						selected: 0
					});
					_this.imgItems = imgs.concat(_this.imgItems);
				}
			});

			e.preventDefault();
			return false;
		},
		fetchImages() {
			var _this = this;
			var api = window.tokenize("/api/image");
			window.axios.get(api).then(function(response) {
				var images = response.data.data;
				for (var i in images) {
					var image = images[i];
					var imgs = [];
					imgs.push({
						img: image.path,
						title: image.title,
						selected: 0
					});
					_this.imgItems = imgs.concat(_this.imgItems);
				}
			});
		},
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
		}
	},

	data() {
		return {
			counter: 1,
			balance: window.user.balance,
			query: null,
			description: "",
			tabActive: "free",
			filesCount: 0,
			top3Imgs: [],
			imgItems: [
				{ img: "/images/article_01.jpg", title: "", selected: 0 },
				{ img: "/images/article_02.jpg", title: "", selected: 0 },
				{ img: "/images/article_03.jpg", title: "", selected: 0 },
				{ img: "/images/article_04.jpg", title: "", selected: 0 },
				{ img: "/images/article_05.jpg", title: "", selected: 0 },
				{ img: "/images/article_06.jpg", title: "", selected: 0 },
				{ img: "/images/article_07.jpg", title: "", selected: 0 },
				{ img: "/images/article_08.jpg", title: "", selected: 0 },
				{ img: "/images/article_09.jpg", title: "", selected: 0 }
			],
			whetherPay: false,
			expiration_date: "0",
			money: 5,
			custom: null
		};
	}
};
</script>

<style lang="scss">
.modal-ask-question {
	.modal-dialog {
		padding-bottom: 20px;
		.modal-content {
			.modal-header {
				padding: 10px 20px;
			}
			.modal-body {
				padding: 5px 40px 0px;
				// max-height: 520px;
				overflow: auto;
				& > div {
					line-height: normal;
				}
				.input-question {
					margin-bottom: 5px;
				}
				.textarea-box {
					position: relative;
					textarea {
						padding-bottom: 25px;
					}
					.word-count {
						position: absolute;
						bottom: 1px;
						right: 6px;
						color: #969696;
						font-size: 14px;
					}
				}
				// 问答选项
				.question-setting {
					margin-top: 12px;
					& > p {
						font-size: 14px;
						font-weight: 500;
						margin-bottom: 10px;
					}
					.setting-group {
						& > div {
							margin-right: 20px;
							display: inline-block;
							span {
								font-size: 14px;
								color: #969696;
								display: inline-block;
								vertical-align: middle;
								&.hot {
									color: #d96a5f;
								}
							}
						}
					}
					.pay-group {
						.pay-tip {
							.pay-tip-content {
								font-size: 13px;
								color: #969696;
								line-height: 30px;
								a {
									color: #2b89ca;
								}
							}
						}
						.money-amount {
							& > div {
								font-size: 13px;
								margin-bottom: 10px;
							}
							.refresh {
								cursor: pointer;
								i.icon-shuaxin {
									display: inline-block;
									-webkit-transform: rotate(360deg);
									transform: rotate(360deg);
									-webkit-transition: all 0.5s ease-out;
									transition: all 0.5s ease-out;
								}
							}
							.option {
								text-align: center;
								position: relative;
								margin: 0 5px 5px 0px;
								width: 77px;
								height: 34px;
								line-height: 32px;
								border: 1px solid #e6e6e6;
								border-radius: 4px;
								font-weight: 400;
								color: #999;
								cursor: pointer;
								i {
								}
								.amount {
									font-size: 15px;
									vertical-align: top;
								}
								.piece {
									font-size: 13px;
								}
								.custom-text {
									font-size: 14px;
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
										left: 3px;
									}
									input {
										display: block;
										margin: 0 auto;
										width: 50px;
										height: 38px;
										line-height: 38px;
										border: none;
										font-size: 16px;
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
										right: 3px;
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
						.expiration-date {
							div {
								font-size: 13px;
								margin-bottom: 10px;
							}
							span {
								font-size: 13px;
								color: #969696;
								display: inline-block;
								vertical-align: middle;
							}
							input {
								width: 12px;
								margin-right: 10px;
								vertical-align: middle;
							}
						}
					}
				}
				// 配图
				.img-selector {
					margin-top: 11px;
					position: relative;
					.ask-img-header {
						font-size: 14px;
						padding-bottom: 20px;
						&.bigger {
							padding-bottom: 35px;
						}
						.desc {
							color: #969696;
						}
					}
					.img-preview {
						position: absolute;
						top: 0;
						right: 0;
						.img-preview-item {
							float: right;
							border: 1px solid #e8e8e8;
							margin-left: 4px;
							width: 60px;
							height: 60px;
							position: relative;
							overflow: hidden;
							.as-height {
								height: 100%;
							}
							.img-del {
								width: 18px;
								height: 18px;
								position: absolute;
								z-index: 2;
								top: 0;
								right: 0;
								background-color: rgba(0, 0, 0, 0.5);
								border-radius: 0 0 0 4px;
								padding: 1px;
								cursor: pointer;
								text-align: center;
								line-height: 18px;
								i {
									font-size: 14px;
									color: white;
								}
							}
						}
					}
					.tab-header {
						ul {
							li {
								padding-bottom: 6px;
								height: 20px;
								line-height: 20px;
								box-sizing: content-box;
								display: inline-block;
								font-size: 14px;
								color: #212121;
								margin-right: 30px;
								cursor: pointer;
								&.tab-header-actived {
									border-bottom: solid 2px #d96a5f;
									font-weight: bold;
								}
							}
						}
					}
					.tab-body {
						height: 256px;
						background-color: #f7f7f7;
						margin: 0 -40px;
						padding: 20px 0 0 0;
						position: relative;
						.tab-body-item {
							height: 100%;
							width: 100%;
							position: relative;
							.material-search {
								position: relative;
								height: 34px;
								margin: 0 40px 20px 40px;
								background-color: #ffffff;
								border: 1px solid #ececec;
								border-radius: 2px;
								overflow: hidden;
								.search-icon {
									position: absolute;
									top: 1px;
									left: 0;
									width: 31px;
									height: 31px;
									line-height: 31px;
									text-align: center;
								}
								.search-input {
									height: 100%;
									padding: 2px 64px 2px 32px;
									border: none;
									font-size: 14px;
									background-color: #fff;
									display: block;
									width: 100%;
								}
								.search-submit {
									height: 34px;
									width: 64px;
									background-color: #ececec;
									font-size: 14px;
									line-height: 14px;
									color: #515151;
									letter-spacing: 1px;
									border: none;
									position: absolute;
									top: 0;
									right: 0;
								}
							}
							.img-container {
								height: 180px;
								padding: 0 25px 0 40px;
								overflow-x: auto;
								.img-container-outer {
									overflow: hidden;
									.img-tip {
										margin: 0 0 20px 0;
										.img-tip-content {
											font-size: 13px;
											color: #969696;
											line-height: 18px;
											a {
												color: #2b89ca;
											}
										}
									}
								}
							}
							.img-upload-field {
								height: 100%;
								padding-top: 46px;
								.img-upload-btn {
									position: relative;
									text-align: center;
									i {
										font-size: 42px;
										color: #c4c4c4;
										line-height: 1;
									}
									.img-click-here,
									.img-limit {
										font-size: 14px;
										color: #2b89ca;
										display: block;
										margin-top: 16px;
										line-height: 1;
									}
									.img-file {
										position: absolute;
										overflow: hidden;
										left: 50%;
										top: 0;
										margin-left: -60px;
										width: 120px;
										height: 100px;
										cursor: pointer;
										input {
											width: 100%;
											height: 100%;
											opacity: 0;
											cursor: pointer;
										}
									}
									.img-limit {
										color: #969696;
										margin-top: 12px;
									}
								}
								.img-loading {
									.img-progress {
									}
									.img-progress-bar {
									}
									.img-progress-num {
									}
									i {
									}
								}
							}
						}
					}
				}
			}
			footer {
				padding: 15px 40px;
			}
		}
	}
}
</style>
