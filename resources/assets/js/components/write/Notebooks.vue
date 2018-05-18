<template>
	<div style='height:100%'  @key.esc="closePublished">	
		<div class="note-books">
			<div class="books-side col-xs-2">
				<div class="home">
					<a href="/">回首页</a>
				</div>
				<div class="create-notebook">
					<div class="handle" @click="toggleCreator">
						<i class="iconfont icon-icon20"></i>
						<span>新建文集</span>
					</div>
					<div class="create-notebook-box" v-show="new_notebook">
						<div>
							<input type="text" class="input-style" placeholder="请输入文集名..." v-model="new_notebook_name">
							<a class="submit btn-base btn-hollow btn-md" @click="creatednotebook">提 交</a>
							<a class="cancel" @click="toggleCreator">取 消</a>
						</div>
					</div> 
				</div>
				<ul class="notebook-list">
					<router-link :to="'/notebooks/'+notebook.id" tag="li" active-class="active"  v-for="notebook in collections" :key="notebook.id">
						<div class="setting pull-right">
							<i class="iconfont icon-shezhi" tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true"
	      						:data-content="dataContent"></i>
						</div>
						<span class="single-line">{{ notebook.name }}</span>
					</router-link>
				</ul>
				<div class="side-bottom col-xs-2">
					<div class="settings pull-left dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							<i class="iconfont icon-fenlei"></i>
							<span>设置</span>
						</a>
						<ul class="dropdown-menu">
							<li><router-link to="/recycle" class="link"><i class="iconfont icon-lajitong"></i><span>回收站</span></router-link></li>
							<li><a href="javascript:;" class="report link"><i class="iconfont icon-bangzhu"></i><span>帮助与反馈</span></a></li>
						</ul>
					</div>
					<a class="doubt pull-right" data-target=".FAQ" data-toggle="modal">
						<span>遇到问题</span>
						<i class="iconfont icon-bangzhu"></i>
					</a>
				</div>
			</div>
			<div class="books-main col-xs-10">
				<notes @article-added="articleAdded"></notes>
			</div>
			<modification-name></modification-name>
			<delete-notebook></delete-notebook>
			<frequently-asked-questions></frequently-asked-questions>
		</div>
		<published :show="published"/>
        <modal-images></modal-images>
	</div>
</template>

<script>
export default {
	name: "Notebooks",

	props: ["collectionId"],

	created() {
		this.getCurrentPathParams();
		this.$store.dispatch("getCollections");
	},

	computed: {
		published() {
			return this.$store.state.published;
		},
		collections() {
			return this.$store.state.collections;
		},
		dataContent() {
			return `<ul class='notebook-setting-menu'>
	              <li class="modification">
	              	<a data-target=".modification-name" data-toggle="modal"><i class='iconfont icon-wendangxiugai'></i>修改文集</a>
	              </li>
	              <li class="delete">
	              	<a data-target=".delete-notebook" data-toggle="modal"><i class='iconfont icon-lajitong'></i>删除文集</a>
	              </li>
  	          </ul>`;
		}
	},

	watch: {
		$route(to, from) {
			this.getCurrentPathParams();
			this.updateCurrentPathInfo();
			this.$store.state.published = false;
		}
	},
	methods: {
		closePublished() {
			this.$store.state.published = false;
		},
		getCurrentPathParams() {
			this.$store.state.articleId = parseInt(this.$router.currentRoute.params.articleId);
			this.$store.state.collectionId = parseInt(this.$router.currentRoute.params.collectionId);
		},
		updateCurrentPathInfo() {
			let { articleId, collectionId } = this.$store.state;
			this.$store.dispatch("updateCurrentPathInfo", { collectionId, articleId });

			if (!articleId) {
				this.$store.dispatch("goCollection", { collectionId });
			}
		},
		articleAdded(data) {
			var notebook = _.find(this.notebook_list, { id: data.notebookId });
			notebook.unshift(data.article);
			this.$router.push(`/notebooks/${data.notebookId}/notes/${data.article.id}`);
		},
		toggleCreator() {
			this.new_notebook = !this.new_notebook;
		},
		goDefaultBook() {
			//默认选第一个
			var notebook_id = this.notebook_list[0].id;
			this.goNotebook(notebook_id);
		},
		goNotebook(notebook_id) {
			this.current_notebook_name = _.find(this.notebook_list, { id: notebook_id }).name;
			if (!this.note_id) {
				this.note_id = this.getDefaultNoteId(notebook_id);
			}
			if (this.note_id) {
				this.$router.push(`/notebooks/${notebook_id}/notes/${this.note_id}`);
			} else {
				this.$router.push(`/notebooks/${notebook_id}`);
			}
		},
		getDefaultNoteId(notebook_id) {
			var defaul_note_id = 0;
			var find = _.filter(this.notebook_list, ["id", notebook_id]);
			if (find.length) {
				this.notes = find[0].articles;
				if (this.notes.length) {
					defaul_note_id = this.notes[0].id;
				}
			}
			return defaul_note_id;
		},
		creatednotebook() {
			this.$store.dispatch("addCollection", { name: this.new_notebook_name });

			// 创建完之后清空
			this.new_notebook_name = null;
			this.new_notebook = !this.new_notebook;
		}
	},

	data() {
		return {
			new_notebook: false,
			new_notebook_name: null
		};
	}
};
</script>
