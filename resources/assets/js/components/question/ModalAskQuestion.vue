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

</style>
