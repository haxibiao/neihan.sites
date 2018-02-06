<template>
	<div class="note_main">
		<div class="top">
			<div class="relevant">
				<div class="litter_title">
					<span>相关用户</span>
					<a href="/new_search#/user">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="list in 3">
						<div class="author">
							<a href="" class="avatar avatar_sm">
								<img src="/images/photo_02.jpg" />
							</a>
							<div class="info_meta">
								<a href="" class="headline nickname">
									<span class="single_line">旅行青蛙</span>
								</a>
								<p class="meta">写了 1168 字・0 喜欢</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="split_line"></div>
			<div class="relevant">
				<div class="litter_title">
					<span>相关专题</span>
					<a href="/new_search#/collection">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="list in 3">
						<div class="author">
							<a href="" class="avatar avatar_sm avatar_collection">
								<img src="/images/category_06.jpg" />
							</a>
							<div class="info_meta">
								<a href="" class="headline nickname">
									<span class="single_line">旅行青蛙爆红背后的秘密</span>
								</a>
								<p class="meta">119 文章・145 关注</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="search_content">
			<div class="sort_type">
				<a href="javascript:;" class="active">综合排序</a>
				<em>·</em>
				<a href="javascript:;">热门文章</a>
				<em>·</em>
				<a href="javascript:;">最新发布</a>
				<em>·</em>
				<a href="javascript:;">最新评论</a>
				<span class="result">244578个结果</span>
			</div>
			<div class="article_list">
				<li class="article_item" v-for="list in 5">
					<div class="content">
			            <div class="author">
			                <a class="avatar" href="" target="_blank">
			                    <img src="/images/photo_02.jpg" />
			                </a>
			                <div class="info_meta">
			                    <a href="" target="_blank" class="nickname">
			                        郭静
			                    </a>
			                    <span class="time">
			                        13 天前
			                    </span>
			                </div>
			            </div>
			            <a class="headline paper_title" href="" target="_blank">
			                <span>
			                	山寨版<em class="search_result_highlight">旅行青蛙</em>应用超20款，<em class="search_result_highlight">旅行青蛙</em>这款游戏还能火多久？
			                </span>
			            </a>
			            <p class="abstract">
			                抄袭、洗稿、伪原创深受业界的鄙视和唾弃，然而，这一“沉珂”始终无法得到治愈，有些人鸡贼+钻漏洞的速度比行业的管理速度要快的多，而外界除了谴责外竟无计可施。<em class="search_result_highlight">旅行青蛙</em>游戏的火爆非常出人意料，但最出人意料的应该是国内相关产业“蹭热点”的能力。有些企业以<em class="search_result_highlight">旅行青蛙</em>为原始IP，做出一些与公司业务/形象相关的宣传图，企图通过社交网络的传播，提升企业形象和印象口碑。
			            </p>
			            <div class="meta">
			                <a href="#" target="_blank" class="count count_link">
			                    <i class="iconfont icon-liulan">
			                    </i>
			                    375
			                </a>
			                <a href="#" target="_blank" class="count count_link">
			                    <i class="iconfont icon-svg37">
			                    </i>
			                    2
			                </a>
			                <span class="count">
			                    <i class="iconfont icon-03xihuan">
			                    </i>
			                    54
			                </span>
			            </div>
			        </div>
				</li>
			</div>
			<ul class="pagination">
	            <li>
	                <a href="#">
	                    首页
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    10
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    11
	                </a>
	            </li>
	            <li>
	                <a href="#" class="active">
	                    12
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    13
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    14
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    15
	                </a>
	            </li>
	            <li>
	                <a href="#">
	                    尾页
	                </a>
	            </li>
	        </ul>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Note',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.note_main {
	.top {
		margin-bottom: 30px;
		background-color: hsla(0,0%,71%,.1);
		border: 1px solid #f0f0f0;
		border-radius: 4px;
		.relevant {
			padding: 20px 0;
			.litter_title {
				padding: 0 15px;
				span {
					color: #333;
				}
				a {
					color: #2B89CA;
					font-size: 13px;
					i {
						font-size: 8px;
						font-weight: 700;
					}
				}
			}
			.list {
				padding: 10px 15px 0;
				.col-xs-12 {
					padding: 0 3px;
					.author {
						.avatar_sm {
							margin-right: 5px;
						}
						.info_meta {
							padding: 3px 0 0 55px;
							.headline {
								font-size: 15px;
							}
							.meta {
								line-height: normal;
							}
						}
					}
					@media screen and (max-width: 768px) {
						margin-top: 10px;
					}
				}
			}
		}
		.split_line {
			margin: 0;
		}
	}
	.search_content {
		.article_list {
			.article_item {
				.content {
					.search_result_highlight {
						font-style: normal;
						color: #d96a5f;
					}
				}
			}	
		}
		.pagination {
			margin: 20px 0;
		}
	}
}
</style>