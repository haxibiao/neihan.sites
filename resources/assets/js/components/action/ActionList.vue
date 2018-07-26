<template>
	<div>
		<div v-for="action in actions">
				<action-article-item v-if="action.actionable_type == 'articles'" 
				:item="action.actionable_type == 'articles' ? action : '' "
				:app-name="appName"></action-article-item>

				<action-comment-item v-if="action.actionable_type == 'comments'" 
				:item="action.actionable_type == 'comments' ? action : '' "
				:app-name="appName">
				</action-comment-item>

				<action-like-item v-if="action.actionable_type == 'likes'" 
				:item="action.actionable_type == 'likes' ? action : '' "
				:app-name="appName">
				</action-like-item>

				<action-follow-item v-if="action.actionable_type == 'follows'" 
				:item="action.actionable_type == 'follows' ? action : '' "
				:app-name="appName">
				</action-follow-item>

		</div>
		<loading-more  v-if="actions.length || notEmpty" :end="end"></loading-more>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>	
</template>

<script>
import ActionArticleItem from './parts/ActionArticleItem';
import ActionCommentItem from './parts/ActionCommentItem';
import ActionLikeItem from './parts/ActionLikeItem';
import ActionFollowItem from './parts/ActionFollowItem';
export default {
	name: "ActionList",

	props: ["api", "startPage", "notEmpty","appName"],

	components:{
		'action-article-item': ActionArticleItem,
		'action-comment-item': ActionCommentItem,
		'action-like-item': ActionLikeItem,
		'action-follow-item': ActionFollowItem,
	},

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
			var api_url = api.indexOf("?") !== -1 ? api + "&page=" + page : api + "?page=" + page;
			return api_url;
		}
	},

	mounted() {
		this.listenScrollBottom();
		this.fetchData();
	},
	methods: {
		clear() {
			this.actions = [];
		},
		listenScrollBottom() {
			var m = this;
			$(window).on("scroll", function() {
				var aheadMount = 5; //sometimes need ahead a little ...
				var is_scroll_to_bottom = $(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
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
			window.axios.get(this.apiUrl).then(function(response) {
				m.actions = m.actions.concat(response.data.data);
				m.lastPage = response.data.last_page;
				$('[data-toggle="tooltip"]').tooltip();
				if (m.page >= m.lastPage) {
					m.end = true;
				}
				//TODO:: loading done !!!
			});
		}
	},

	data() {
		return {
			apiDefault: "",
			page: this.startPage ? this.startPage : 1,
			lastPage: -1,
			actions: [],
			end: false
		};
	}
};
</script>

<style lang="css" scoped>
</style>
