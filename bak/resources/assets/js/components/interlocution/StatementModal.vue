<template>
<!-- 问答细则及责任声明 -->
<div aria-labelledby="myModalLabel" class="modal fade" id="statementModal" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal" type="button">×</button>
				<h4 class="modal-title" id="myModalLabel">
				爱你城网站问答细则和责任声明
				</h4>
			</div>
			<div class="modal-body">
				<div>
					<p>“爱你城”是一个公开的提供娱乐游戏休闲生活服务的社交类网站，立志于通过爱你城这个社交平台为用户提高交友效率和更好的娱乐游戏体验感。</p>
					<p>感谢使用“爱你城”网站的问答功能</p>
					<p><strong>1. 问题</strong></p>
					<p>必填项目。</p>
					<p>你可以选择文字、图片等方式发布问题，文字限制500字，图片限制9张，满足多数情况下的需求。</p>
					<p><strong>2. 赏金</strong></p>
					<p>非必填项目。</p>
					<p>可以使用免费问答，其默认设置为0，并不收费。但为了能快速获取帮助，尊重知识和劳动，您可以给问题支付赏金，以便快速高效的获得解答。赏金最低¥ 1.00元。</p>
					<p><strong>3. 赏金支付方式</strong></p>
					<p>赏金在问题提交的时候支付，优先从账户余额支付，如果账户余额不足，则扣除余额后，剩下的部分由支付宝钱包支付。如果支付不成功，则无法发布问题。你需要修改赏金重新发布。</p>
					<p><strong>4. 截止时间</strong></p>
					<p>非必填项目。</p>
					<p>问题要求必须设定截止时间，可以设定在1-3天内，系统默认截止时间是24小时。</p>
					<p><strong>5. 编辑和删除</strong></p>
					<p>编辑：问题在无人回答时，在问题主页点击“编辑”选项，可以对问题的内容（除赏金外）进行编辑。</p>
					<p>如果问题已经有人回答，则无法对问题进行编辑。</p>
					<p>删除：问题在无人回答时，在问题主页点击“删除”选项，可以问题。如果问题已经有人回答，则无法对问题进行删除。</p>
					<p><strong>6. 匿名</strong></p>
					<p>你可以匿名发布问题，你的用户名和头像将用“匿名用户”代替，而发布的文字和图片是可见的。</p>
					<p><strong>7. 采纳</strong></p>
					<p>如果你发布问题时，支付了赏金，那么当你采纳答案后，平台自动将赏金支付给被采纳的用户。采纳答案后不可取消和更改，且不能退款。</p>
					<p><strong>8. 退款</strong></p>
					<p>发布问题时支付了赏金，截止时间到，</p>
					<p>(1) 如果没人回答，赏金将在24小时内自动退回到您的账户。</p>
					<p>(2) 如果有人回答，您没有采纳答案，那么您需要忽略所有的答案，赏金将在24小时内自动退回到您的账户。</p>
					<p>(3) 采纳和忽略答案没有时间限制。</p>
					<p><strong>9. 回答</strong></p>
					<p>(1) 你可以选择文字、图片等方式回答问题，文字限制500字，图片限制9张，满足大多数情况下的需求。</p>
					<p>(2) 被采纳前，可以进行编辑和删除。被采纳后，无法编辑和删除。</p>
					<p><strong>10. 责任声明</strong></p>
					<p>“爱你城”是一个公开的提供娱乐游戏休闲情感生活服务的社交类网站，立志于通过爱你城这个社交平台为用户提高交友效率和更好的娱乐游戏体验感。“爱你城”网站的问答功能仅限于提供娱乐游戏休闲情感生活服务方面的解决方案。</p>
				</div>
			</div>
		</div>
	</div>
</div>
</template>
	<script>
export default {
	name: "StatementModal",
	data() {
		return {};
	}
};
</script>
	<style lang="scss" scoped>
#statementModal {
	.modal-dialog {
		transform: translate(-50%, -50%);
		width: 1098px;
		@media screen and (max-width: 1200px) {
			width: 920px;
		}
		@media screen and (max-width: 992px) {
			width: 680px;
		}
		@media screen and (max-width: 768px) {
			width: 100%;
		}
		.modal-body {
			padding: 20px;
			max-height: 800px;
			overflow: auto;
			& > div {
				font-size: 17px;
				line-height: 40px;
			}
		}
	}
}
</style>
