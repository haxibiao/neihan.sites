<template>
	<div id="profile">
		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="information">
				<tr>
					<td class="top_line setting_title">性别</td>
					<td class="top_line">
						<div>
							<input type="radio" name="gender" value="male" checked />
							<span>男</span>
						</div>
						<div>
							<input type="radio" name="gender" value="female" />
							<span>女</span>
						</div>
						<div>
							<input type="radio" name="gender" value="secrecy" />
							<span>保密</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="setting_title pull-left">个人简介</td>
					<td>
						<form>
							<textarea placeholder="填写你的个人简介" cols="30" rows="10" class="form-control"></textarea>
						</form>
					</td>
				</tr>
				<tr>
					<td class="setting_title pull-left">个人网站</td>
					<td>
						<form>
							<input type="text" placeholder="http://你的网址" class="form-control" />
							<p class="pull-right">填写后会在个人主页显示图标</p>
						</form>
					</td>
				</tr>
				<tr>
					<td class="setting_title">微信二维码</td>
					<td class="weixin_qrcode">
						<a href="javascript:;" class="btn_base btn_hollow btn_hollow_sm">
							<input type="file" class="hide" unselectable="on" />
							更换图片
						</a>
						<p class="pull-right">上传后会在个人主页显示图标</p>
					</td>
				</tr>
                <tr>
                    <td class="setting_title pull-left">社交账号</td>
                    <td class="social_bind">
                        <p>你可以通过绑定的社交帐号登录爱你城。出于安全因素, 你最初用来创建账户的社交帐号不能移除。</p>
                        <ul class="social_bind_list">
                            <li>
                                <div class="bind_name">
                                    <i class="iconfont icon-weixin1"></i>
                                    <a href="#">
                                        绑定微信
                                        <i class="iconfont icon-youbian"></i>
                                    </a>
                                </div>
                            </li>
                            <!-- <li>
                                <div class="bind_name">
                                    <i class="iconfont icon-sina"></i>
                                    <a href="#">
                                        绑定微博
                                        <i class="iconfont icon-youbian"></i>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="bind_name">
                                    <i class="iconfont icon-qq2"></i>
                                    <a href="#">
                                        绑定QQ
                                        <i class="iconfont icon-youbian"></i>
                                    </a>
                                </div>
                            </li> -->
                        </ul>
                    </td>
                </tr>
			</tbody>
		</table>
		<input type="submit" value="保存" class="btn_base btn_follow" />
	</div>
</template>

<script>
export default {

  name: 'Profile',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#profile {
        table {
            thead {
                tr {
                    .setting_head {
                        width: 200px;
                        @media screen and (max-width: 992px) {
                            width: 140px;
                        }
                        @media screen and (max-width: 768px) {
                            width: 85px;
                        }
                    }
                }
            }
            .information {
                tr {
                    p {
                        padding: 8px 0 0;
                        color: #969696;
                        clear: both;
                    }
                    td {
                        form {
                            textarea {
                                height: 100px;
                                padding: 8px 10px;
                                resize: none;
                            }
                            input[type="text"] {
                                width: 100%;
                            }
                        }
                    }
                    .top_line {
                        padding-top: 0;
                        div {
                            display: inline-block;
                            span {
                                margin: 0 20px 0 6px;
                                font-size: 15px;
                                vertical-align: middle;
                            }
                        }
                    }
                    .setting_title {
                        font-size: 15px;
                        color: #969696;
                    }
                    .weixin_qrcode {
                        .btn_hollow {
                            position: relative;
                            padding: 4px 12px;
                            font-size: 14px;
                            input {
                                position: absolute;
                                display: block!important;
                                top: -1px;
                                left: 1px;
                                width: 85px;
                                height: 30px;
                                opacity: 0;
                            }
                        }
                    }
                    .social_bind {
                        .social_bind_list {
                            li {
                                line-height: 50px;
                                border-bottom: 1px solid #f0f0f0;
                                .bind_name {
                                    display: inline-block;
                                    i {
                                        width: 30px;
                                        font-size: 20px;
                                        display: inline-block;
                                        vertical-align: middle;
                                    }
                                    .icon-sina {
                                        color: #e05244;
                                    }
                                    .icon-weixin1 {
                                        color: #42c02e;
                                    }
                                    .icon-qq2 {
                                        color: #2B89CA;
                                    }
                                    a {
                                        margin: 0 0 0 20px;
                                        font-size: 14px;
                                        vertical-align: middle;
                                        i {
                                            font-size: 12px;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
</style>