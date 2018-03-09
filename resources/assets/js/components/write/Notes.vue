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
			<success @hideSuccess="publishHide" :publish-show="publishShow" :article-id="article?article.id:''"></success>
			<div class="note-item-wrap">
				<div class="note-item" v-if="article">
					<p class="note-status">{{ article.saved ? '已保存' : '未保存' }}</p>
					<div class="note-content">
						<editor :value="article.body" :focus="article.focus" @valuechanged="changeBody" @editorsaved="save" @editorpublished="publish" write>
							<input type="text" v-model="article.title" class="note-title single-line" @input="changeTitle">
						</editor>
					</div>
				</div>
				<div class="blank-brand">
					<span>爱你城</span>
				</div>
			</div>
		</div>
		<scroll-top target=".simditor-body"></scroll-top>
		<delete-note></delete-note>
	</div>
</template>

<script>
export default {

  name: 'Notes',

  watch: {
  	'$route' (to,from) {
  		//切换文章的时候，保存前一篇文章正文到store...
  		var { previewArticle, currentArticle } = this.$store.state;
  		if(previewArticle) {
  			previewArticle.body = this.body;
  		}
  		this.publishShow = false;
  	}
  },
  
  updated() {
  	this.$nextTick(function () {
  	   $('[data-toggle="popover"]').popover();
  	});

  },

  computed: {
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
  		let subMenu = '';
  		var _this = this;
  		this.$store.state.collections.forEach(function(ele){
  			if(_this.$store.state.currentCollection.id != ele.id)
  				subMenu += `<li><a href='javascript:;' data-id='${ele.id}'><span>${ele.name}</span></a></li>`
  		});
  		return subMenu;
  	},
  	dataContent() {
  	  //文章状态不同对应不同的popover
  	  return `<ul class='note-setting-menu'>
	              <li><a href='javascript:;'><i class='iconfont ${this.article.status ? 'icon-renzheng1':'icon-icon-feixingmanyou'}'></i><span>${this.article.status?'已发布':'直接发布'}</span></a></li>
	              <li class="move">
	              	<a href='javascript:;'><i class='iconfont icon-wenjianjia'></i><span>移动文章</span></a>
	              	<ul class='note-setting-menu sub-menu'>
						${this.notebookListName}
	              	</ul>
	              </li>
	              <li class='${this.article.status ? '' : 'hidden'}'><a href='/article/${this.article.id}' target='_blank'><i class='iconfont icon-774bianjiqi_yulan'></i><span>新窗口打开</span></a></li>
	              <li class='${this.article.status ? '' : 'hidden'}'><a href='javascript:;'><i class='iconfont icon-suo2'></i><span>设为私密</span></a></li>
	              <li><a data-target=".delete-note" data-toggle="modal"><i class='iconfont icon-lajitong'></i><span>删除文章</span></a></li>
  	          </ul>`;
  	},
  },

  methods:{
  	publishHide() {
  		this.publishShow = false;
  	},
  	showSetting() {
  	   var _this = this;
  	   $('.note-setting-menu i.icon-icon-feixingmanyou').parent().click(function() {
  	   		_this.publish();
  	   });
  	   $('.note-setting-menu i.icon-suo2').parent().click(function() {
  	   		_this.unpublish();
  	   });
  	   $('.note-setting-menu .sub-menu a').click(function() {
  	   		let collectionId = $(this).attr('data-id');
  	   		_this.moveArticle(collectionId);
  	   });
  	   $('.note-setting-menu .icon-774bianjiqi_yulan').parent().click(function() {
  	   		var href = $(this).attr('href');
  	   		window.open(href);
  	   });
  	},
  	moveArticle(collectionId) {
  		this.$store.dispatch('moveArticle', collectionId);
  	},
  	save() {
  		console.log('saved...');
  		this.article.body = this.body;

  		//乐观更新
  		this.article.saved = true;
  		this.article.status = 0;
  		this.$set(this.ui, 'updated_at', Date.now());

  		this.$store.dispatch('saveArticle');
  	},
  	publish() {
  		this.article.body = this.body;

  		//乐观更新
  		this.article.saved = true;
  		this.article.status = 1; 
  		this.$set(this.ui, 'updated_at', Date.now());

  		this.$store.dispatch('publishArticle');
  		if (this.article.status == 1) {
  			this.publishShow = true;
  		}
  	},
  	unpublish() {
  		this.article.body = this.body;

  		//乐观更新
  		this.article.saved = true;
  		this.article.status = 0; 
  		this.$set(this.ui, 'updated_at', Date.now());

  		this.$store.dispatch('unpublishArticle');
  	},
  	createNote() {
  		this.body = '';
  		this.$store.dispatch('addArticle');
  	},
  	changeBody(value) {
  		//暂存编辑的改动，避免:value无限触发编辑器的valuechanged事件
  		this.body = value;

  		//尝试触发保存状态更新为未保存
  		this.article.saved = false;
  		this.$set(this.ui, 'updated_at', Date.now());
  	},
  	changeTitle(e) {
  		//改动标题的时候，保存编辑器的改动到store
  		this.$store.state.currentArticle.body = this.body;
  		this.$store.state.currentArticle.title = e.target.value;

  		//更新ui状态
  		this.article.saved = false;
  	}
  },

  data () {
    return {
    	ui: {},
    	body: '',
    	publishShow: false
    }
  }
}
</script>

<style lang="scss">
.notes {
	height: 100%;
	.notes-side {
		height: 100%;
		border-right: 1px solid #d9d9d9;
		padding: 0;
		.side-wrap {
		    overflow-y: scroll;
		    height: 100%;
			.create-note {
				position: relative;
				line-height: 20px;
			    font-size: 16px;
			    padding: 20px 0 20px 48px;
			    cursor: pointer;
			    color: #595959;
			    i {
			    	position: absolute;
			    	left: 24px;
			    	font-size: 20px;
			    	font-weight: 400;
			    	vertical-align: bottom;
			    }
			}
			.note-list {
				position: relative;
				margin-bottom: 0;
				background-color: #efe9d9;
				border-top: 1px solid #d9d9d9;
				&>li {
					position: relative;
					height: 90px;
					color: #595959;
					background-color: #fff;
					margin-bottom: 0;
					padding: 15px 10px 15px 60px;
					box-shadow: 0 0 0 1px #d9d9d9;
					border-left: 5px solid transparent;
					line-height: 60px;
					cursor: pointer;
					user-select: none;
					&:hover {
						background-color: #e6e6e6;
					}
					&.active {
					    background-color: #e6e6e6;
					    border-left-color: #FF9D23;
					    .setting,.abstract,.word-count {
					    	display: block;
					    }
					}
					.status-icon {
						position: absolute;
						top: 30px;
						left: 17.5px;
						width: 30px;
						height: 30px;
						font-size: 26px;
						line-height: 30px;
						&.icon-fabuxiaoxi {
							font-size: 28px;
							color: #B5AE5F;
						}
						&.icon-icon_article {
							color: #969696;
						}
					}
					.setting {
						font-size: 16px;
						width: 40px;
						text-align: center;
						position: relative;
						min-height: 30px;
						max-height: 50px;
						i {
							outline: none;
						}
					}
					.title,.abstract {
						display: block;
					    height: 30px;
					    line-height: 30px;
					    margin-right: 40px;
					}
					.title {
						font-size: 18px;
					    color: #333;
					    font-weight: 400;
					}
					.word-count {
						position: absolute;
						bottom: 2px;
						left: 5px;
						font-size: 9px;
						line-height: 16px;
						color: #595959;
					}
					.setting,.abstract,.word-count {
						display: none;
					}
					.popover {
						top: 43px !important;
						left: -78px !important;
						padding: 0;
						width: 120px;
						.arrow {
							left: 82% !important;
						}
						.popover-content {
							padding: 0;
							.note-setting-menu {
								&.sub-menu {
									position: absolute;
									z-index: 1;
									left: 0;
									top: 0px;
									transform: translate(-100%, 0);
									display: none;
									box-shadow: 0 0 2px 1px #ccc;
									border-radius: 4px;
								}
								li {
									height: 42px;
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
										&>a {
											color: #fff;
										}
									}
									&.move {
										&::after {
											position: absolute;
											content: '';
											left: 4px;
											top: 15px;
											border-width: 5px;
											border-style: solid;
											border-color: transparent #ccc transparent transparent;
										}
										&:hover {
											.sub-menu {
												display: block;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	.note-item-container {
		height: 100%;
		padding: 0;
		.note-item-wrap {
			height: 100%;
			.note-item {
				position: relative;
				height: 100%;
				padding-top: 20px;
				.note-status {
					position: absolute;
					top: 2px;
					right: 5px;
				}
				.note-content {
					height: 100%;
					.simditor-box {
						height: 100%;
						.note-title {
							padding: 0 80px 10px 40px;
							border: none;
							font-size: 30px;
							line-height: 30px;
							color: #515151;;
							background-color: transparent;
							width: 100%;
						}
						.simditor {
							border: none;
							height: 100%;
							.simditor-wrapper {
								height: 100%;
								.simditor-toolbar {
									border-bottom: 1px solid #ccc;
									background: #d9d9d9;
									&>ul {
										&>li {
											.toolbar-item-publish {
												width: 100px;
												.icon-icon-feixingmanyou {
													font-size: 15px;
													&::before {
														font-size: 20px;
														margin-right: 3px;
													}
												}
											}
											&>.toolbar-item {
												&:hover {
													background-color: #666;
													color: #fff;
												}
												&>span {
													opacity: 0.9;
												}
											}
										}
									}
								}
								.simditor-body {
									width: 100%;
									height: calc(100% - 93px);
									padding: 20px 13px 80px;
									color: #333;
									background-color: transparent;
									font-size: 18px;
									font-weight: 400;
									line-height: 30px;
									overflow: auto;
								}
							}
						}
					}
				}
			}
			.blank-brand {
			    position: relative;
			    height: 100%;
			    background-color: #f2f2f2;
			    text-align: center;
			    &::before {
			    	content: "";
			    	height: 100%;
			    	display: inline-block;
			    	vertical-align: middle;
			    }
			    span {
			    	font-size: 64px;
		    	    color: #e6e6e6;
		    	    text-shadow: 0 1px 0 #fff;
			    }
			}
		}
	}
}
</style>