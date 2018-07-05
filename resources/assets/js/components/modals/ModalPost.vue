<template>
    <div class="modal fade modal-post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
				      上传
					</h4>
                </div>
                <div class="modal-body">
    				<form method="post" action="/question" ref="questionForm">
    				<input type="hidden" name="_token" v-model="token">
					<div class="input-question">
						<input-matching name="title" placeholder="请输入标题"></input-matching>
					</div>
					<div class="textarea-box">
                		<textarea name="background" placeholder='快来说点什么吧' v-model="description" maxlength='500'></textarea>
                		<span class="word-count">{{ description.length }}/500</span>
					</div>
			        <input type="hidden" name="user_id" :value="user.id">
			        <input v-if="top3Imgs.length>0" name="image1" type="hidden" :value="top3Imgs[0].img">
			        <input v-if="top3Imgs.length>1" name="image2" type="hidden" :value="top3Imgs[1].img">
			        <input v-if="top3Imgs.length>2" name="image3" type="hidden" :value="top3Imgs[2].img">
			        </form>

                    <div class="img-selector">
                    	<div :class="['ask-img-header',top3Imgs.length>0?'bigger':'']"><span class="desc">（最多9张图片或者1个视频）</span></div>
						<div class="img-preview clearfix">
							<div class="img-preview-item clearfix" v-for="item in top3Imgs">
								<img :src="item.img" alt="" class="as-height">
								<div class="img-del" @click="deleteImg(item)"><i class="iconfont icon-cha"></i></div>
							</div>
							<div v-if="videoPath">
								<video class="video" :src="videoPath" controls="" ref="video_ele"></video>
								<span class="size">{{videoObj.name}}</span>
					            <div class="progress-box">
					            	<div>{{conver(videoObj.size)}}</div>
					            	<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
						            	<div class="progress-bar progress-bar-success" style="width:0%">
							            </div>
							        </div>
					            </div>
								<div class="video-del" @click="deleteVideo"><i class="iconfont icon-cha"></i></div>
							</div>
						</div>
						<div class="tab-header">
							<ul>
								<li :class="tabActive=='file'?'tab-header-actived':''" @click="tabSwitch('file')">上传图片或者视频</li>
							</ul>
						</div>
						<div class="tab-body">
							<div class="tab-body-item" v-show="tabActive=='file'">
								<div class="img-upload-field">
								    <div class="img-upload-btn">
								    	<i class="iconfont icon-icon20"></i>
								    	<span class="img-click-here">点击此处上传图片</span>
								        <div class="img-file">
								            <input type="file" @change="upload"  :accept="fileFormat" multiple>
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
                	<button class="btn-base btn-handle btn-md pull-right" @click="submit">提交</button>
                </footer>
            </div>
        </div>
    </div>
</template>


<script>
import Dropzone from "../../plugins/Dropzone";

export default {
	name: "ModalPost",

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
		submit() {
			this.$refs.questionForm.submit();
		},
		dragDropUpload(fileObj, params) {
			if (this.filesCount >= 9) {
				return;
			}
			this._upload(fileObj);
			this.filesCount++;
		},
		upload(e) {
			for (var i = 0; i < e.target.files.length; i++) {
				if (e.target.files[0].type.indexOf("image") != -1) {
					this.fileFormat =
						".bmp,.jpg,.png,.tiff,.gif,.pcx,.tga,.exif,.fpx,.svg,.psd,.cdr,.pcd,.dxf,.ufo,.eps,.ai,.raw,.WMF,.webp";
					if (this.filesCount >= 9) {
						break;
					}
					var fileObj = e.target.files[i];
					this._upload(fileObj);
					this.filesCount++;
				} else if (e.target.files[0].type.indexOf("video") != -1) {
					this.fileFormat =
						".avi,.wmv,.mpeg,.mp4,.mov,.mkv,.flv,.f4v,.m4v,.rmvb,.rm,.3gp,.dat,.ts,.mts,.vob";

					var _this = this;
					var reader = new FileReader();
					this.videoObj = e.target.files[0];
					reader.readAsDataURL(e.target.files[0]);

					reader.onload = function(e) {
						_this.videoPath = e.target.result;
					};
					if (this.videoObj.length >= 1) {
						break;
					}
				}
			}
		},
		conver(limit) {
			var size = "";
			if (limit < 0.1 * 1024) {
				//如果小于0.1KB转化成B
				size = limit.toFixed(2) + "B";
			} else if (limit < 0.1 * 1024 * 1024) {
				//如果小于0.1MB转化成KB
				size = (limit / 1024).toFixed(2) + "KB";
			} else if (limit < 0.1 * 1024 * 1024 * 1024) {
				//如果小于0.1GB转化成MB
				size = (limit / (1024 * 1024)).toFixed(2) + "MB";
			} else {
				//其他转化成GB
				size = (limit / (1024 * 1024 * 1024)).toFixed(2) + "GB";
			}
			var sizestr = size + "";
			var len = sizestr.indexOf(".");
			var dec = sizestr.substr(len + 1, 2);
			if (dec == "00") {
				//当小数点后为00时 去掉小数部分
				return sizestr.substring(0, len) + sizestr.substr(len + 3, 2);
			}
			return sizestr;
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
			if (this.top3Imgs.length < 1) {
				this.fileFormat = true;
			}
		},
		deleteVideo() {
			this.videoPath = null;

			if (!this.videoPath) {
				this.fileFormat = true;
			}
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
			tabActive: "file",
			filesCount: 0,
			top3Imgs: [],
			videoPath: null,
			videoObj: null,
			fileFormat: true,
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
.modal-backdrop {
	position: static !important;
}
.modal-post {
	.modal-dialog {
		padding-bottom: 20px;
		max-width: 720px !important;
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
						position: relative;
						margin-bottom: 10px;
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
						.video {
							width: 50%;
						}
						.size {
							vertical-align: top;
							margin: 0 40px;
						}
						.progress-box {
							vertical-align: top;
							display: inline-block;
							width: 90px;
						}
						.video-del {
							position: absolute;
							top: -22px;
							right: 0;
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
