<template>
	<div class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<span class="result">1410个结果</span>
		</div>
		<ul class="user_list">
            <li v-for="list in 5">
                <div class="author">
                    <a class="avatar avatar_in" href="">
                        <img src="/images/photo_02.jpg" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" href="">
                            <span class="single_line">旅行青蛙</span>
                        </a>
                        <div class="meta">
                            <span>
                                关注 10
                            </span>
                            <span>
                                粉丝 5
                            </span>
                            <span>
                                文章 20
                            </span>
                        </div>
                        <p class="meta">
                            写了 444820 字，获得了 11 个喜欢
                        </p>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="pagination">
            <li>
                <a href="#">
                    1
                </a>
            </li>
            <li>
                <a href="#">
                    2
                </a>
            </li>
            <li>
                <a href="#">
                    3
                </a>
            </li>
            <li>
                <a href="#" class="active">
                    4
                </a>
            </li>
            <li>
                <a href="#">
                    5
                </a>
            </li>
            <li>
                <a href="#">
                    6
                </a>
            </li>
            <li>
                <a href="#">
                    <span>
                        尾页
                    </span>
                </a>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'User',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.search_content {
	.user_list {
		.info_meta {
			.meta {
				line-height: normal;
				padding-top: 3px;
			}
		}
		@media screen and (max-width: 768px) {
            .btn_base {
                width: 84px;
                padding: 4px;
            }
            .btn_followed {
                padding: 3px;
            }
            .info_meta {
            	padding-right: 90px;
            }
        }
	}
	.pagination {
		margin: 20px 0;
	}
}
</style>