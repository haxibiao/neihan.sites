<template>
	<div>
	    <table class="form-menu">
	        <tbody class="information">
	            <tr>
	                <td class="top-line setting-title setting-verticle">
	                    性别
	                </td>
	                <td class="top-line">
	                    <input type="radio" value="男" v-model="user.gender"> <span>男</span>
	                    <input type="radio" value="女" v-model="user.gender"> <span>女</span>
	                    <input type="radio" value="保密" v-model="user.gender"> <span>保密</span></td>
	            </tr>
	            <tr>
	                <td class="setting-title pull-left">个人简介</td>
	                <td>
	                    <form>
	                        <textarea placeholder="填写你的个人简介" v-model="user.introduction"></textarea>
	                    </form>
	                </td>
	            </tr>
	            <tr>
	                <td class="setting-title pull-left">个人网站</td>
	                <td>
	                    <form>
	                        <input type="text" class="input-style" name="website" placeholder="http://你的网址" v-model="user.website">
	                        <p class="pull-right">填写后会在个人主页显示图标</p>
	                    </form>
	                </td>
	            </tr>
	            <!-- <tr>
	                <td class="setting-title">微信二维码</td>
	                <td class="weixin-qrcode">
	                    <input type="file" class="hide">
	                    <a class="btn-base btn-hollow btn-sm btn-file ">
	                        <input type="file"> 更换图片
	                    </a>
	                    <p class="pull-right">上传后会在个人主页显示图标</p>
	                </td>
	            </tr>
	            <tr class="social">
	                <td class="setting-title pull-left">社交帐号</td>
	                <td class="social-bind">
	                    <p>你可以通过绑定的社交帐号登录简书。出于安全因素, 你最初用来创建账户的社交帐号不能移除。</p>
	                    <ul class="social-bind-list">
	                        <li class="has-bind">
	                            <div class="bind-name"><i class="iconfont icon-sina weibo"></i> <i class="iconfont icon-weibiaoti12"></i> <span>已绑定</span> <a class="cancel-bind" href="">解除绑定</a></div>
	                            <div class="pull-right">
	                                <input type="checkbox" checked="checked"> <span>在个人主页显示</span></div>
	                        </li>
	                        <li>
	                            <div class="bind-name"><i class="iconfont icon-weixin1 weixin"></i> <a href="javascript:;">绑定微信<i class="iconfont icon-youbian"></i></a></div>
	                        </li>
	                        <li>
	                            <div class="bind-name"><i class="iconfont icon-qq2 qq"></i> <a href="javascript:;">绑定 QQ<i class="iconfont icon-youbian"></i></a></div>
	                        </li>
	                    </ul>
	                </td>
	            </tr> -->
	        </tbody>
	    </table>
	    <input type="button" class="btn-base btn-handle btn-bold" value="保存" @click="save">

	    <div class="alert alert-success" v-if="saved">
	    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    	<strong>提示！</strong> 个人资料保存成功...
	    </div>
	</div>
</template>

<script>
export default {
	name: "Profile",

	created() {
		this.fetchData();
	},

	methods: {
		api() {
			return window.tokenize("/api/user");
		},
		fetchData() {
			var _this = this;
			window.axios.get(this.api()).then(function(response) {
				_this.user = response.data;
			});
		},
		save() {
			var formData = {
				gender: this.user.gender,
				introduction: this.user.introduction,
				website: this.user.website
			};
			var _this = this;
			window.axios.post(this.api(), formData).then(function(response) {
				_this.user = response.data;
				_this.saved = true;
			});
		}
	},

	data() {
		return {
			saved: null,
			user: {}
		};
	}
};
</script>

<style lang="scss" scoped>
.information {
	input[name="website"] {
		width: 100%;
		margin-bottom: 10px;
	}
	.social-bind {
		padding-bottom: 10px;
		p {
			padding: 0;
		}
		.social-bind-list {
			li {
				border-bottom: 1px solid #f0f0f0;
				line-height: 50px;
				font-size: 0;
				.bind-name {
					display: inline-block;
					i {
						width: 30px;
						font-size: 20px;
						vertical-align: middle;
						display: inline-block;
						&.icon-weibiaoti12 {
							width: auto;
							margin-left: 20px;
							color: #969696;
							font-size: 14px;
						}
						&.icon-youbian {
							font-size: 12px;
						}
					}
					a {
						margin: 0 0 0 20px;
						font-size: 14px;
						vertical-align: middle;
					}
					span {
						margin: 0 0 0 3px;
						font-size: 14px;
						color: #969696;
					}
				}
				.pull-right {
					font-size: 16px;
					span {
						color: #969696;
						margin: 0 0 0 10px;
						font-size: 12px;
					}
				}
				.cancel-bind {
					margin: 0 0 0 3px;
					font-size: 14px;
					color: #969696;
					margin-left: 8px;
					display: none;
					&:hover {
						color: #252525;
					}
				}
				&:hover {
					.cancel-bind {
						display: inline-block;
						vertical-align: middle;
						font-size: 12px;
					}
				}
			}
		}
	}
}
</style>
