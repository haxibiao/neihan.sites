<template>
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
						<a class="submit btn_base btn_hollow btn_follow_xs" @click="creatednotebook">提 交</a>
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
</template>

<script>
export default {

  name: 'Notebooks',

  props:['collectionId'],

  created() {
  	this.getCurrentPathParams();
  	this.$store.dispatch('getCollections');
  },

  computed:{
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
  	},
  },

  watch: {
  	'$route' (to,from) {
  		this.getCurrentPathParams();
  		this.updateCurrentPathInfo();
  	}
  },
  methods:{
  	getCurrentPathParams() {
  		this.$store.state.articleId = parseInt(this.$router.currentRoute.params.articleId);
  		this.$store.state.collectionId = parseInt(this.$router.currentRoute.params.collectionId);
  	},
  	updateCurrentPathInfo() {
  		let { articleId, collectionId } = this.$store.state; 
  		this.$store.dispatch('updateCurrentPathInfo', { collectionId, articleId });
  		
  		if(!articleId) {
  			this.$store.dispatch('goCollection', { collectionId });
  		}
  	},
  	articleAdded(data) {
  		var notebook = _.find(this.notebook_list, {'id': data.notebookId});
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
  		this.current_notebook_name = _.find(this.notebook_list, {'id': notebook_id }).name;
		if(!this.note_id){
			this.note_id = this.getDefaultNoteId(notebook_id);
		}
		if(this.note_id) {
			this.$router.push(`/notebooks/${notebook_id}/notes/${this.note_id}`);
		} else {
			this.$router.push(`/notebooks/${notebook_id}`);
		}
  	},
  	getDefaultNoteId(notebook_id) {
  		var defaul_note_id = 0;
		var find = _.filter(this.notebook_list,['id', notebook_id]);
		if(find.length) {
			this.notes = find[0].articles;
			if(this.notes.length) {
				defaul_note_id = this.notes[0].id;
			}
		}
		return defaul_note_id;
  	},
  	creatednotebook() {
  		this.$store.dispatch('addCollection', { name: this.new_notebook_name });

  		// 创建完之后清空
        this.new_notebook_name = null;
        this.new_notebook = !this.new_notebook;
  	}
  },

  data () {
    return {
    	new_notebook:false,
    	new_notebook_name:null,
    }
  }
}
</script>

<style lang="scss">
	.note-books {
		height: 100%;
		overflow: hidden;
		.books-side {
			position: relative;
		    height: 100%;
		    overflow-y: auto;
		    background-color: #404040;
		    color: #f2f2f2;
		    z-index: 100;
			font-weight: 500;
			padding: 0;
			.home {
				padding: 30px 18px 5px;
				text-align: center;
				a {
					display: block;
				    font-size: 15px;
				    padding: 9px 0;
				    color: #FF9D23;
				    border: 1px solid rgba(#FF9D23,.8);
				    border-radius: 20px;
				    transition: all .3s linear;
				    &:hover {
				    	border-color: #FF9D23;
				    	box-shadow: 0 0 2px 0 #FF9D23;
				    }
				}
			}
			.create-notebook {
				padding: 0 15px;
				margin-top: 20px;
				margin-bottom: 10px;
				.handle {
					cursor: pointer;
					i {
						font-size: 14px;
						font-weight: 700;
					}
					span {
						margin-left: 2px;
						font-size: 14px;
					}
				}
				.create-notebook-box {
					&>div {
						margin-top: 10px;
						input {
							margin-bottom: 10px;
							&.input-style {
							  width: 100%;
							  padding: 5px 10px;
							  font-size: 15px;
							  border: 1px solid #333;
							  border-radius: 4px;
							  background-color: #595959;
							}
						}
						&>a {
							display: inline-block;
							cursor: pointer;
							margin-left: 5px;
							background-color: transparent;
							&.submit {
								min-width: auto;
								padding: 2px 10px;
							}
							&.cancel {
								border: none;
								outline: none;
								margin-top: -2px;
								color: #969696;
								font-size: 14px;
								&:hover {
									color: unset;
								}
							}
						}
					}
				}
			}
			.notebook-list {
				li {
					position: relative;
					height: 40px;
					line-height: 40px;
					font-size: 15px;
					color: #f2f2f2;
					background-color: #404040;
					padding: 0 15px;
					cursor: pointer;
					user-select: none;
					&:hover {
						background-color: #666;
					}
					&.active {
						background-color: #666;
						border-left: 3px solid #FF9D23;
						padding-left: 12px;
						.setting {
							display: block;
						}
					}
					.setting {
						font-size: 16px;
						width: 40px;
						text-align: center;
						position: relative;
						min-height: 30px;
						max-height: 50px;
						display: none;
						i {
							outline: none !important;
						}
						.popover {
							top: 32px !important;
							left: -80px !important;
							padding: 0;
							.arrow {
								left: 82% !important;
							}
							.popover-content {
								padding: 0;
								.notebook-setting-menu {
									li {
										padding: 10px 20px;
										line-height: 20px;
										white-space: nowrap;
										text-align: left;
										position: relative;
										background-color: #FFF;
										border-bottom: 1px solid #d9d9d9;
										transition: all .3s linear;
										&>a {
											display: block;
											width: 100%;
											height: 100%;
											color: #515151;
										}
										i {
											margin-right: 4px;
										}
										&:first-child {
											border-radius: 4px 4px 0 0;
										}
										&:last-child {
											border-radius: 0 0 4px 4px;
											border-bottom: none;
										}
										&:hover {
											background-color: #666;
											a {
												color: #fff;
											}
										}
									}
								}
							}
						}
					}
					&>span {
						display: block;
						margin-right: 20px;
					}
				}
			}
			.side-bottom {
				position: fixed;
			    bottom: 0;
			    height: 50px;
			    line-height: 50px;
			    font-size: 15px;
			    user-select: none;
			    z-index: 150;
			    background-color: #404040;
			    .settings {
					>a {
						color: #969696;
						font-size: 14px;
						&:hover {
							color: unset;
						}
					}
				}
			    .settings,.doubt {
			    	display: inline-block;
			    	color: #999;
			    	cursor: pointer;
			    	transition: color .2s cubic-bezier(.645,.045,.355,1);
			    	&:hover {
			    		color: #fff;
			    	}
			    	span {
						margin: 0 0 0 4px;
			    	}
			    	.dropdown-menu {
			    		top: -180%;
			    		min-width: 140px;
			    		padding: 0;
			    		background-color: transparent;
			    		&>li {
			    			&:first-child {
			    				&>a {
			    					border-radius: 4px 4px 0 0;
			    				}
			    			}
			    			&:last-child {
			    				&>a {
			    					border-radius: 0 0 4px 4px;
			    				}
			    			}
			    			&>a {
			    				transition: all .3s linear;
			    				background-color: #fff;
					    		&:hover {
									background-color: #666;
									color: #fff;
									i {
										color: #fff;
									}
								}
			    			}
			    			&:hover {
			    				background-color: transparent;
			    			}
			    		}
			    	}
			    }
			    .doubt {
					span {
			    		margin: 0 4px 0 0;
					}
			    }
			}
		}
		.books-main {
			height: 100%;
			padding: 0;
		}
	}
</style>