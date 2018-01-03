<template>
	<!-- 收到的投稿请求 -->
	<div id="requests">
		<div class="notification_menu">全部投稿请求</div>
		<ul class="requests_list">
			<li>
				<router-link to="/pending_submissions">
					<div class="all_push">
						<i class="iconfont icon-tougaoguanli"></i>
					</div>
					<div class="name">全部未处理请求</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/1/submissions">
					<div class="avatar avatar_sm avatar_collection">
						<img src="/images/category_01.jpeg" />
						<span class="badge">5</span>
					</div>
					<div class="name">
						王者荣耀
						<p class="new_abstract">有新投稿《起风了，谁是下一个王者荣耀？》</p>
					</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/2/submissions">
					<div class="avatar avatar_sm avatar_collection">
						<img src="/images/category_04.jpeg" />
					</div>
					<div class="name">热恋</div>
				</router-link>
			</li>
			<li>
				<router-link to="/collections/3/submissions">
					<div class="avatar avatar_sm avatar_collection">
						<img src="/images/category_02.jpg" />
						<span class="badge">2</span>
					</div>
					<div class="name">
						暗恋
						<p class="new_abstract">有新投稿《为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？》</p>
					</div>
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Requests',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#requests {
        .requests_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                a {
                    display: block;
                    .all_push {
                    	width: 48px;
                    	height: 48px;
                        border: 1px solid #dcdcdc;
                        border-radius: 10%;
                        text-align: center;
                        display: inline-block;
                        vertical-align: middle;
                        line-height: 48px;
                        margin-right: 10px;
                        i {
                            font-size: 28px;
                            color: #fd5b78;
                            font-weight: 700;
                        }
                    }
                    .name {
                        font-size: 15px;
                        display: inline-block;
                        vertical-align: middle;
                        max-width: 640px;
                        width: 70%;
                        @media screen and (max-width: 540px) {
                            width: 50%;
                        }
                    }
                    .avatar {
                        position: relative;
                        .badge {
                            position: absolute;
                            top: -5px;
                            right: -5px;
                        }
                    }
                }
                @media screen and (max-width: 450px) {
                    padding: 20px 5px;
                }
            }
        }
    }
</style>