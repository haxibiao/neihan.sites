<template>
	<div id="reward_setting">
		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="setting_pay">
				<tr>
					<td class="top_line setting_title">赞赏功能</td>
					<td class="top_line">
						<div>
							<input type="radio" name="appreciate" value="open" checked />
							<span>开启</span>
						</div>
						<div>
							<input type="radio" name="appreciate" value="shut" />
							<span>关闭</span>
						</div>
						<p>开启后赞赏按钮将出现在你的文章底部</p>
					</td>
				</tr>
				<tr>
					<td class="setting_title pull-left">赞赏描述</td>
					<td>
						<form>
							<textarea cols="30" rows="10" class="form-control" placeholder="如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！"></textarea>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="保存" class="btn_base btn_follow" />
	</div>
</template>

<script>
export default {

  name: 'Reward-Setting',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>