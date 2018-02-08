<template>
	<div class="release_after">
		<div class="release_info">
			<div class="after_info">
				<div class="title">
					<a href="" class="single_line">
						<span>2018-02-08发布成功,点击查看文章发布成功</span>
					</a>
				</div>
				<a href="" class="note">发布成功,点击查看文章</a>
			</div>
			<ul class="release_share">
				<li class="btn_base btn_follow weibo">
					<i class="iconfont icon-sina"></i>
					微博
				</li>
				<li class="btn_base btn_follow weixin">
					<i class="iconfont icon-weixin1"></i>
					微信
				</li>
				<li class="btn_base btn_follow qq">
					<i class="iconfont icon-qq1"></i>
					空间
				</li>
				<li class="btn_base btn_follow link">
					复制链接
				</li>
			</ul>
		</div>
		<div class="close">x</div>
		<div class="arc"></div>
	</div>
</template>

<script>
export default {

  name: 'success',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.release_after {
	background-color: #f2f2f2;
	text-align: center;
	.release_info {
		padding: 0 13px 90px;
		.after_info {
			padding: 40px 0;
			.title {
				height: 40px;
				font-size: 28px;
				font-weight: 500;
				color: #333;
				margin-bottom: 24px;
				a {
					display: block;
					span {
						&:hover {
							text-decoration: underline;
						}
					}
				}
			}
			.note {
				font-size: 16px;
				font-weight: 600;
				color: #42c02e;
				&::before {
					content: "";
					display: inline-block;
					width: 18px;
					height: 10px;
					border: 3px solid #42c02e;
					border-width: 0 0 4px 4px;
					transform: rotateZ(-45deg);
					transition: 0s;
					position: relative;
					bottom: 4px;
					right: 8px;
				}
				&:hover {
					color: #3bab29;
				}
			}
		}
		.release_share {
			.btn_base {
				font-size: 14px;
				margin: 0 10px;
				cursor: pointer;
				@media screen and (max-width: 930px) {
			        margin-top: 10px;
			    }
			}
			.weibo {
				background-color: #e05244;
				&:hover {
					background-color: #e54333;
				}
			}
			.qq {
				background-color: #ff9d23;
				&:hover {
					background-color: #fb981d;
				}
			}
			.link {
				background-color: #2B89CA;
				&:hover {
					background-color: #1f84ca;
				}
			}
		}
	}
	.close {
		position: fixed;
		top: 5px;
		right: 20px;
		font-size: 30px;
		font-weight: 700;
		padding: 5px;
		cursor: pointer;
		opacity: 0.5;
		&:hover {
			opacity: 0.7;
		}
	}
	.arc {
		height: 40px;
		border-radius: 50% 50% 0 0/100% 100% 0 0;
		background-color: #fff;
		margin-top: -40px;
	}
}
</style>