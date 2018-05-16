<template>
	<div>
		<div class="menu">全部投稿请求</div>
		<ul class="requests-list">
			<li>
				<router-link to="/pending_submissions">
					<a href="javascript:;" class="all-request">
						<div class="avatar-category"><i class="iconfont icon-tougaoguanli"></i></div>
						<div class="info">全部未处理请求</div>
					</a>
				</router-link>
			</li>
			<li v-for="category in categories">
				<router-link :to="'/submissions/'+category.id"　@click="clickName(category.name)">
					<div class="single-media">
						<a href="javascript:;" class="avatar-category">
							<img :src="category.logo" alt="">
							<span v-if="category.new_requests" class="badge">{{ category.new_requests }}</span>
						</a>
						<div class="info">
							{{ category.name }}
							<span>
				        		<p>有新投稿《{{ category.new_request_title }}》</p>
				      		</span>
						</div>
					</div>
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: "Requests",

	created() {
		var api = window.tokenize("/api/categories/new-requested");
		var _this = this;
		window.axios.get(api).then(function(response) {
			_this.categories = response.data;
		});
	},

	methods: {
		clickName(category_name) {
			window.category_name = category_name;
		}
	},

	data() {
		return {
			categories: []
		};
	}
};
</script>

<style lang="scss" scoped>
</style>
