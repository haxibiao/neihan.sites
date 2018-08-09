<template>
    <div class="modal fade modal-post" tabindex="1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
				      发布动态
					</h4>
                </div>
                <div class="modal-body">
    				<form method="post" action="/post" ref="postForm" enctype="multipart/form-data">
					<input type="hidden" name="_token" v-model="token">
					<div class="input-question">
						<input-matching name="title" placeholder="输入标题" hide-suggestion></input-matching>
					</div>
					<div class="textarea-box">
                		<textarea name="body" placeholder='再说点什么...' v-model="description" maxlength='500'></textarea>
                		<span class="word-count">{{ description.length }}/500</span>
					</div>
					<div>
						<category-select placeholder="再选择专题"></category-select>
					</div>
                    <div class="img-selector">
                    	<div :class="['ask-img-header',selectedImgs.length > 0 ? 'bigger' : '']"><span class="desc">（每次最多上传9张图片或者1个视频）</span></div>
						<div class="img-preview clearfix">
							<div class="img-preview-item clearfix" v-for="image in selectedImgs">
								<img :src="image.url" alt="" class="as-height">
								<div class="img-del" @click="deleteImg(image)"><i class="iconfont icon-cha"></i></div>
							</div>
							<div v-if="videoPath">
								<div class="modal-video-box">
									<video class="video" :src="videoPath" controls="" ref="video_ele">
									</video>
									<div class="progress_box" ref="progress_box">
										<loading :progress="progress"></loading>
									</div>
								</div>
								
								<div class="video-del" @click="deleteVideo"><i class="iconfont icon-cha"></i></div>
							</div>
						</div>
						<div class="tab-header">
							<div class="tab-header-actived">图片或者视频</div>
						</div>
						<div v-if="bool" class="tab-body">
							<div class="tab-body-item">
								<div class="img-upload-field">
								    <div class="img-upload-btn">
								    	<i class="iconfont icon-icon20"></i>
								    	<span class="img-click-here">点击此处上传图片</span>
								        <div class="img-file">
								            <input type="file" @change="upload"  :accept="fileFormat" multiple ref="upload" name="video">
								            <modal-upload-check></modal-upload-check>
								        </div>
								    	<span class="img-limit">支持图片拖拽上传</span>
								    </div>
								</div>
							</div>
						</div>
                    </div>
                    <input type="hidden" name="user_id" :value="user.id">
			        <input v-for="img in selectedImgs" name="image_urls[]" type="hidden" :value="img.url">
			        <input v-if="video_id" name="video_id" type="hidden" :value="video_id">
			        </form>
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
import ModalUploadCheck from "./ModalUploadCheck";

export default {
	name: "ModalPost",

	props: [],

	components:{
		'modal-upload-check':ModalUploadCheck,
	},

	computed: {
		token() {
			return window.csrf_token;
		},
		user() {
			return window.user;
		},
		selectedImgs() {
			return _.filter(this.imgItems, ["selected", 1]);
		}
	},

	mounted() {
		Dropzone($(".img-upload-field")[0], this.dragDropUpload);
	},

	methods: {
		submit() {
			this.$refs.postForm.submit();
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
					this.fileFormat = ".bmp,.jpg,.png,.tiff,.gif,.pcx,.tga,.exif,.fpx,.svg,.psd,.cdr,.pcd,.dxf,.ufo,.eps,.ai,.raw,.WMF,.webp";
					if (this.filesCount >= 9) {
						break;
					}
					let fileObj = e.target.files[i];
					this._upload(fileObj);
					this.filesCount++;
				} else if (e.target.files[0].type.indexOf("video") != -1) {
					let _this = this;
					_this.fileFormat = ".avi,.wmv,.mpeg,.mp4,.mov,.mkv,.flv,.f4v,.m4v,.rmvb,.rm,.3gp,.dat,.ts,.mts,.vob";
					_this.videoObj = e.target.files[0];
					// _this.videoObjSize = _this.videoObj.size/1024/1024;
					// //not allow 50Mb
					// if(_this.videoObjSize >= 50){
					// 	$('#myModal').modal('show');
					// 	return;
					// }
					_this.bool = false;
					let reader = new FileReader();
					reader.readAsDataURL(e.target.files[0]);
					reader.onload = function(e) {
						_this.videoPath = e.target.result;
					};
					if (_this.videoObj && _this.videoObj.length >= 1) {
						break;
					}
					_this.video_upload(_this.videoObj);
				}
			}
		},
		_upload(fileObj) {
			var api = window.tokenize("/api/image/save");
			var _this = this;
			let formdata = new FormData();
			formdata.append("from", "post");
			formdata.append("photo", fileObj);
			let config = {
				headers: {
					"Content-Type": "multipart/form-data"
				}
			};
			window.axios.post(api, formdata, config).then(function(res) {
				var image = res.data;
				_this.imgItems.push({
					url: image.url,
					id: image.id,
					selected: 1
				});
			});
		},
		video_upload(videoFile) {
			var _this = this;
			_this.progress = 0;
			console.log(videoFile);
			console.log("start upload to qcvod ...");
			qcVideo.ugcUploader.start({
				videoFile: videoFile, //视频，类型为 File
				getSignature: function(callback) {
					$.ajax({
						url: "/sdk/qcvod.php", //获取客户端上传签名的 URL
						type: "GET",
						success: function(signature) {
							//result 是派发签名服务器的回包
							//假设回包为 { "code": 0, "signature": "xxxx"  }
							//将签名传入 callback，SDK 则能获取这个上传签名，用于后续的上传视频步骤。
							callback(signature);
						}
					});
				},
				error: function(result) {
					//上传失败时的回调函数
					//...
					console.log("上传失败的原因：" + result.msg);
				},
				progress: function(result) {
					// console.log("上传进度：" + result.curr);
					let progress = parseInt(result.curr * 100);
					// $(".progress-bar-success").css("width", progress + "%");
					_this.progress = progress;
				},
				finish: function(result) {
					$(_this.$refs.upload).val("");
					//上传成功时的回调函数
					$(_this.$refs.video_ele).css({ opacity: "1" });
					console.log("上传结果的fileId：" + result.fileId);
					console.log("上传结果的视频名称：" + result.videoName);
					console.log("上传结果的视频地址：" + result.videoUrl);

					//TODO：调用 POST /api/video/ , 写下数据库记录，返回video_id
					var _vm = _this;
					$.ajax({
						url: window.tokenize("/api/video/save?from=qcvod"),
						type: "POST",
						data: result,
						success: function(video) {
							console.log(video);
							//TODO: get video_id
							_vm.video_id = video.id;
						}
					});
				}
			});
		},
		deleteImg(image) {
			image.selected = 0;
			this.imgItems = this.selectedImgs;
			this.filesCount--;
			if (this.imgItems.length < 1) {
				this.fileFormat = true;
			}
		},
		deleteVideo() {
			this.videoPath = null;
			this.qcvod_id = null;
			this.bool = true;
			if (!this.videoPath) {
				this.fileFormat = true;
			}
		}
	},

	data() {
		return {
			video_id: null,
			progress: 0,
			counter: 1,
			balance: window.user.balance,
			query: null,
			description: "",
			filesCount: 0,
			qcvod_id: null,
			videoPath: null,
			videoObj: null,
			bool: true,
			fileFormat: true,
			imgItems: []
		};
	}
};
</script>


<style lang="scss">
.modal-backdrop {
	position: static !important;
}
.modal-post {
	@media (max-width: 1366px){
		.video {
			height: 270px !important;
		}
	}
	.modal-dialog {
		padding-bottom: 20px;
		max-width: 720px !important;
		top: 42%;
		.modal-content {
			.modal-body {
				padding: 5px 40px 0px;
				max-height: 660px;
				overflow: auto;
				.input-question {
				    margin: 10px 0;
				}
				.textarea-box {
				    position: relative;
				    margin-bottom: 20px;
				}
				.img-selector {
					.img-preview {
						position: relative;
						margin-bottom: 10px;
						.modal-video-box{
							position:relative;
							display: inline-block;
							.video {
								height: 300px;
								opacity: 0.2;
							}
							.progress_box {
								position: absolute;
								top: 50%;
								left: 50%;
								transform: translate(-50%, -50%);
							}
						}
						.video-del {
							position: absolute;
							top: -22px;
							right: 0;
							cursor: pointer;
						}
					}
				}
			}
		}
	}
}
</style>
