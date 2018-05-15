<template>
	<!-- 收到的赞赏 -->
	<div id="tip">
		<div class="notification_menu">收到的赞赏</div>
		<ul class="tip_list">
			<li>
				<div class="author">
					<a href="#" class="avatar avatar_xs">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="info_meta">
						<div class="info">
							<a href="javascript: ;" class="user">中南工大留级生</a>
							<span>向你的文章</span>
							<a href="javascript: ;" class="title">《在web项目中添加自定义的font图标》</a>
							<span>送了 1 颗糖（赞赏 ¥ <i class="money">1.00</i>）</span>
						</div>
						<div class="time">2017.11.15  08:09</div>
					</div>
				</div>
				<a class="btn_base btn_hollow btn_followed_xs">消息回复</a>
			</li>
			<li>
				<div class="author">
					<a href="#" class="avatar avatar_xs">
						<img src="/images/photo_02.jpg" />
					</a>
					<div class="info_meta">
						<div class="info">
							<a href="javascript: ;" class="user">中南工大留级生</a>
							<span>向你的文章</span>
							<a href="javascript: ;" class="title">《在web项目中添加自定义的font图标》《在web项目中添加自定义的font图标》</a>
							<span>送了 1 颗糖（赞赏 ¥ <i class="money">1.00</i>）</span>
						</div>
						<div class="time">2017.11.15  08:09</div>
					</div>
				</div>
				<a class="btn_base btn_hollow btn_followed_xs">消息回复</a>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Tip',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#tip {
	    .tip_list {
	        li {
	            padding: 20px;
	            border-top: 1px solid #f0f0f0;
	            display: inline-block;
	            .author {
	                font-size: 15px;
	                .info_meta {
	                    line-height: 1.7;
	                    padding: 0 0 0 50px;
	                    .info {
	                        .user {
	                            margin-right: 5px;
	                        }
	                        .title {
	                            color: #2B89CA;
	                        }
	                        .money {
	                            color: #d96a5f;
	                        }
	                    }
	                }
	            }
	            .btn_base {
	                font-size: 12px;
	                padding: 5px;
	                float: right;
	            }
	            @media screen and (max-width: 540px) {
	                padding: 20px 5px;
	            }
	        }
	    }
	}
</style>