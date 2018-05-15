<template>
	<!-- 收到的其他消息 -->
	<div id="others">
		<div class="notification_menu">其他消息</div>
		<!-- 没有消息 -->
		<!-- <div class="unMessage">
			<p>这里还木有内容哦~</p>
			<span class="iconfont icon-meiyouxiaoxi1"></span>
		</div> -->
		
		<!-- 有消息 -->
		<ul class="others_list">
			<li>
				<div class="info_meta">
					<div class="info">
						<i class="iconfont icon-paihang"></i>
						<span>你投稿的文章</span>
						<a href="javascript:;" class="title">《起风了，谁是下一个王者荣耀？》</a>
						<span>已被加入专题</span>
						<a href="javascript:;" class="title">《王者荣耀》</a>
					</div>
					<div class="time">2017.11.15  08:09</div>
				</div>
			</li>
			<li>
				<div class="info_meta">
					<div class="info">
						<i class="iconfont icon-ku"></i>
						<span>你投稿的文章</span>
						<a href="javascript:;" class="title">《为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？》</a>
						<span>未能选入专题</span>
						<a href="javascript:;" class="title">《王者荣耀》</a>
						<span>，请继续加油～</span>
					</div>
					<div class="time">2017.11.15  08:09</div>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Others',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#others {
        .others_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                .info_meta {
                    line-height: 1.7;
                    font-size: 15px;
                    .info {
                        i {
                            margin-right: 5px;
                            color: #d96a5f;
                            font-size: 20px;
                        }
                        .title {
                            color: #2B89CA;
                        }
                    }
                }
                @media screen and (max-width: 540px) {
	                padding: 20px 5px;
	            }
            }
        }
    }
</style>