<template>
	<div class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<em>·</em>
			<a href="javascript:;">最近更新</a>
			<em>·</em>
			<a href="javascript:;">热门专题</a>
			<span class="result">5个结果</span>
		</div>
		<ul class="user_list">
            <li v-for="list in 5">
                <div class="author">
                    <a class="avatar avatar_in avatar_collection" href="">
                        <img src="/images/category_06.jpg" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" href="">
                            <span class="single_line">旅行青蛙爆红背后的秘密</span>
                        </a>
                        <p class="meta">
                            收录了 119 篇文章，146 人关注
                        </p>
                    </div>
                </div>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'Collection',

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