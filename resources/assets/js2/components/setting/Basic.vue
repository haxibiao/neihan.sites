<template>
	<div id="basic">
		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="base">
				<tr>
					<td class="top_line">
						<div class="avatar">
							<img src="/images/photo_04.png" />
						</div>
					</td>
					<td class="top_line">
						<a href="javascript:;" class="btn_hollow">
							<input type="file" class="hide" unselectable="on" />
							更换头像
						</a>
					</td>
				</tr>
				<tr>
					<td class="setting_title">昵称</td>
					<td>
						<input type="text" placeholder="请输入昵称" class="form-control" />
					</td>
				</tr>
				<tr>
					<td class="setting_title">电子邮件</td>
					<td>
						<form>
							<input type="email" placeholder="请输入你的常用邮箱" class="form-control" />
							<input type="button" value="发送" class="btn_hollow" />
						</form>
					</td>
				</tr>
				<tr>
					<td class="setting_title">手机</td>
					<td class="setted">
						<div>123456789101</div>
						<i class="iconfont icon-weibiaoti12"></i>
						<span>已验证</span>
						<a href="javascript:;" class="cancel_bind">解除绑定</a>
					</td>
				</tr>
				<tr>
					<td class="setting_title">语言设置</td>
					<td>
						<div>
							<input type="radio" name="language" value="zh_CN" checked />
							<span>中文简体</span>
						</div>
						<div>
							<input type="radio" name="language" value="zh_TW" />
							<span>中文繁体</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="setting_title">接受谁的私信</td>
					<td>
						<div>
							<input type="radio" name="letter" value="true" checked />
							<span>所有人</span>
						</div>
						<div>
							<input type="radio" name="letter" value="false" />
							<span>我关注的人、我发过私信的人</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="setting_title">提醒邮件通知</td>
					<td>
						<div>
							<input type="radio" name="mail" value="instantly" checked />
							<span>所有动态</span>
						</div>
						<div>
							<input type="radio" name="mail" value="later" />
							<span>每天未读汇总</span>
						</div>
						<div>
							<input type="radio" name="mail" value="none" />
							<span>不接受</span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="保存" class="btn_success" />
	</div>
</template>

<script>
export default {

  name: 'Basic',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>