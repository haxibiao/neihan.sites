<template>
	<div class="recycle">
		<div class="recycle-side">
			<div class="recycle-count">
				<i class="iconfont icon-lajitong"></i>
				回收站({{ items.length }})
			</div>
			<ul class="recycle-list">
				<router-link tag="li" :to="'/recycle/'+recycle_item.id" v-for="recycle_item in items" :key="recycle_item.id" active-class="active" replace>
					<a>
						<span class="destroy-date">将于{{ recycle_item.destroy_date }}清除</span>
						<span class="count-down">{{ recycle_item.count_down }}天后清除</span>
						<h5 class="single-line">
							<i class="iconfont icon-wenji"></i>
							{{ recycle_item.title }}
						</h5>
					</a>
				</router-link>
			</ul>
		</div>
		<div class="recycle-content">
			<div class="title">
				<div class="title-warp">
					<h3 class="single-line">{{ currentItem.title }}</h3>
					<router-link to="/notebooks" class="iconfont icon-cha"></router-link>
				</div>
			</div>
			<div class="content-body-wrap">
				<div class="content-body" v-html="currentItem.body">
				</div>
			</div>
			<div class="content-footer">
				<a class="recover" @click="restore">恢复文章</a>
				<a class="delete" data-target=".thorough-delete" data-toggle="modal">彻底删除</a>
			</div>
		</div>
		<thorough-delete :recycle-id="recycleId"></thorough-delete>
	</div>
</template>

<script>
export default {
	name: "Recycle",

	props: ["recycleId"],

	created() {
		this.$store.dispatch("getTrash");
	},

	computed: {
		items() {
			return this.$store.state.trash;
		},
		currentItem() {
			var found = this.$store.state.trash.find(item => item.id == this.recycleId);
			return found ? found : {};
		}
	},

	methods: {
		restore() {
			if (this.recycleId) {
				this.$store.dispatch("restoreArticle", this.recycleId);
			}
		}
	},

	data() {
		return {};
	}
};
</script>
