<template>
	<div class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<span class="result">1个结果</span>
		</div>
		<ul class="user_list">
            <li>
                <div class="author">
                    <a class="avatar avatar_in avatar_collection" href="">
                        <img src="/images/category_09.png" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" href="">
                            <span class="single_line">旅行青蛙-日志集</span>
                        </a>
                        <p class="meta">
                            2 篇文章，2 人关注
                        </p>
                    </div>
                </div>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'NoteBook',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
.search_content {
	.user_list {
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