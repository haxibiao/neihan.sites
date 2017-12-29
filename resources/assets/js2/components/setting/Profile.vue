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
						<a href="javascript:;" class="btn_hollow">
							<input type="file" class="hide" unselectable="on" />
							更换图片
						</a>
						<p class="pull-right">上传后会在个人主页显示图标</p>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="保存" class="btn_success" />
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

<style lang="css" scoped>
</style>