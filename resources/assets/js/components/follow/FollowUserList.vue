<template>
	<div>
		<li v-for="user in users" class="user-info info-md">
			    <a class="avatar" :href="'/user/'+user.user.id">
			         <img :src="user.user.avatar" alt=""> 
			    </a>        			  
			    <follow 
			      type="users" 
			      :id="user.user.id" 
			      :user-id="currentUserId" 
			      :followed="user.user.is_follow">
			    </follow>
			    <div class="title">
			      <a :href="'/user/'+user.user.id" class="name">{{ user.user.name }}</a>
			    </div>
			    <div class="info">
			      <div class="meta">
					      <span>关注 {{ user.user.count_followings }}</span>
					      <span>粉丝 {{ user.user.count_follows }}</span>
					      <span>文章 {{ user.user.count_articles }}</span>
				  </div>
				  <div class="meta">
			          写了 {{ user.user.count_words }} 字，获得了 {{ user.user.count_likes }} 个喜欢
		          </div>
			    </div>
		</li>
	</div>
</template>

<script>
export default {
	name: "FollowUserList",

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
			this.users = [];
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
					m.users = m.users.concat(response.data.data);
					$('[data-toggle="tooltip"]').tooltip();
					//TODO:: loading done !!!
				});
		}
	},

	data() {
		return {
			apiDefault: "",
			page: this.startPage ? this.startPage : 1,
			lastPage: -1,
			users: []
		};
	}
};
</script>

<style lang="css" scoped>
</style>
