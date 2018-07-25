<template>
	<div>
		<li v-for="category in categories" class="note-info">
		    <a class="avatar-category" :href="'/'+category.followed.name_en">
		    	<img :src="category.followed.logo" alt="">
		    </a>                      
		    <follow 
		   		type="categories" 
		   		:id="category.followed.id" 
		   		:user-id="currentUserId" 
		   		:followed="category.followed.is_follow">
		    </follow>
			<div class="title">
			    <a :href="'/'+category.followed.name_en" class="name">{{ category.followed.name }}</a>
			</div>
			<div class="info">
		     	<p>
		     	<a :href="'/'+category.followed.name_en" class="name">{{ category.followed.name }}</a>
		     		收录了{{ category.followed.count }}篇文章，{{ category.followed.count_follows }}人关注
		     	</p>
		    </div>
		</li>
	</div>
</template>

<script>
export default {
	name: "FollowCategoriesList",

	props: ["api", "startPage", "notEmpty", "isDesktop", "currentUserId"],

	watch: {
		api(val) {
			this.clear();
			this.fetchData();
		}
	},

	computed: {
		apiUrl() {
			var page = this.page;
			var api = this.api ? this.api : this.apiDefault;
			var api_url =
				api.indexOf("?") !== -1
					? api + "&page=" + page
					: api + "?page=" + page;
			return api_url;
		}
	},

	mounted() {
		this.listenScrollBottom();
		this.fetchData();
	},

	methods: {
		clear() {
			this.categories = [];
		},
		listenScrollBottom() {
			var m = this;
			$(window).on("scroll", function() {
				var aheadMount = 5; //sometimes need ahead a little ...
				var is_scroll_to_bottom =
					$(this).scrollTop() >=
					$("body").height() - $(window).height() - aheadMount;
				if (is_scroll_to_bottom) {
					m.fetchMore();
				}
			});
		},
		fetchMore() {
			++this.page;
			if (this.lastPage > 0 && this.page > this.lastPage) {
				//TODO: ui 提示  ...
				return;
			}
			this.fetchData();
		},
		fetchData() {
			var m = this;
			//TODO:: loading ....
			window.axios
				.post(window.tokenize(this.apiUrl))
				.then(function(response) {
					m.lastPage = response.data.last_page;
					m.categories = m.categories.concat(response.data.data);
				});
		}
	},

	data() {
		return {
			apiDefault: "",
			page: this.startPage ? this.startPage : 1,
			lastPage: -1,
			categories: []
		};
	}
};
</script>

<style lang="css" scoped>
</style>
