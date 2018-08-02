<template>
	<div class="notes">
		<div class="notes-side col-xs-4">
			<div class="side-wrap">
				<div>
					<div class="create-note" @click="createNote">
						<i class="iconfont icon-tianjia"></i>
						<span> 新建文章</span>
					</div>
					<ul class="note-list">
						<router-link :to="'/notebooks/'+collection.id+'/notes/'+article.id" tag="li" active-class="active" v-for="article in articles" :key="article.id">
							<i :class="['status-icon','iconfont', article.status > 0 ? 'icon-fabuxiaoxi' : 'icon-icon_article']"></i>
							<div class="setting pull-right"><i class="iconfont icon-shezhi" @click="showSetting" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true"
      						:data-content="dataContent"></i></div>
							<span class="title single-line">{{ article.title ? article.title : article.time }}</span>
							<span class="abstract single-line">{{ article.description }}</span>
							<span class="word-count">字数:{{ article.count_words }}</span>
						</router-link>
					</ul>
				</div>
			</div>
		</div>
		<div class="note-item-container col-xs-8">
			<div class="note-item-wrap">
				<div class="note-item" v-if="article">
					<p class="note-status">{{ article.saved ? '已保存' : '未保存' }}</p>
					<div class="note-content">
						<editor :value="article.body" @blur="onEditorBlur" @imgpicked="onImgPicked" @saved="save" @published="publish" @changed="changeBody" write>
							<input type="text" v-model="article.title" class="note-title single-line" @input="changeTitle">
						</editor>
					</div>
				</div>
				<div class="blank-brand">
					<span>{{ appName }}</span>
				</div>
			</div>
		</div>
		<scroll-top target=".simditor-body"></scroll-top>
		<delete-note></delete-note>
	</div>
</template>

<script>
export default {
	name: "Notes",

	watch: {
		// $route(to, from) {
		// 	//切换文章的时候，保存前一篇文章
		// 	var { previousArticle, currentArticle } = this.$store.state;
		// 	if (previousArticle && previousArticle.id) {
		// 		this.$store.dispatch("autoSavePreviousArticle");
		// 	}
		// }
	},

	updated() {
		this.$nextTick(function() {
			$('[data-toggle="popover"]').popover();
		});
	},

	computed: {
		appName() {
			return window.appName;
		},
		articles() {
			return this.$store.state.currentCollection.articles;
		},
		collection() {
			return this.$store.state.currentCollection;
		},
		article() {
			return this.$store.state.currentArticle;
		},
		notebookListName() {
			// 用于popover
			let subMenu = "";
			var _this = this;
			this.$store.state.collections.forEach(function(ele) {
				if (_this.$store.state.currentCollection.id != ele.id)
					subMenu += `<li><a href='javascript:;' data-id='${ele.id}'><span>${ele.name}</span></a></li>`;
			});
			return subMenu;
		},
		dataContent() {
			//文章状态不同对应不同的popover
			return `<ul class='note-setting-menu'>
	              <li><a href='javascript:;'><i class='iconfont ${this.article.status ? "icon-renzheng1" : "icon-icon-feixingmanyou"}'></i><span>${
				this.article.status ? "已发布" : "直接发布"
			}</span></a></li>
	              <li class="move">
	              	<a href='javascript:;'><i class='iconfont icon-wenjianjia'></i><span>移动文章</span></a>
	              	<ul class='note-setting-menu sub-menu'>
						${this.notebookListName}
	              	</ul>
	              </li>
	              <li class='${this.article.status ? "" : "hidden"}'><a href='/article/${
				this.article.id
			}' target='_blank'><i class='iconfont icon-774bianjiqi_yulan'></i><span>新窗口打开</span></a></li>
	              <li class='${
						this.article.status ? "" : "hidden"
					}'><a href='javascript:;'><i class='iconfont icon-suo2'></i><span>设为私密</span></a></li>
	              <li><a data-target=".delete-note" data-toggle="modal"><i class='iconfont icon-lajitong'></i><span>删除文章</span></a></li>
  	          </ul>`;
		}
	},

	methods: {
		showSetting() {
			var _this = this;
			$(".note-setting-menu i.icon-icon-feixingmanyou")
				.parent()
				.click(function() {
					_this.publish();
				});
			$(".note-setting-menu i.icon-suo2")
				.parent()
				.click(function() {
					_this.unpublish();
				});
			$(".note-setting-menu .sub-menu a").click(function() {
				let collectionId = $(this).attr("data-id");
				_this.moveArticle(collectionId);
			});
			$(".note-setting-menu .icon-774bianjiqi_yulan")
				.parent()
				.click(function() {
					var href = $(this).attr("href");
					window.open(href);
				});
		},
		moveArticle(collectionId) {
			this.$store.dispatch("moveArticle", collectionId);
		},
		save() {
			this.article.body = this.body;

			//乐观更新
			this.article.saved = true;
			this.$set(this.ui, "updated_at", Date.now());

			this.$store.dispatch("saveArticle");
		},
		publish() {
			this.article.body = this.body;
			this.$store.dispatch("publishArticle");
		},
		unpublish() {
			this.article.body = this.body;
			this.$store.dispatch("unpublishArticle");
		},
		createNote() {
			this.body = "";
			this.$store.dispatch("addArticle");
		},
		changeBody(value) {
			//暂存编辑的改动，避免:value无限触发编辑器的valuechanged事件
			this.body = value;

			//尝试触发保存状态更新为未保存
			this.article.saved = false;
			this.$set(this.ui, "updated_at", Date.now());
		},
		changeTitle(e) {
			//改动标题的时候，保存编辑器的改动到store
			this.article.body = this.body;
			this.article.title = e.target.value;

			//更新ui状态
			this.article.saved = false;
			this.$set(this.ui, "updated_at", Date.now());
		},
		onImgPicked(e) {
			//刚插入图库的图片,自动保存会re-render编辑器,会让创作编辑器不能连续成功插入图库的图片
		},
		onEditorBlur(e) {
			//失去光标的时候保存会触发编辑器re-render,刚插入的图片会异常
		}
	},

	data() {
		return {
			ui: {},
			body: ""
		};
	}
};
</script>
