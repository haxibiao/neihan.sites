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

  name: 'Recycle',

  props:['recycleId'],

  created() {
  	this.$store.dispatch('getTrash');
  },

  computed: {
  	items() {
  		return this.$store.state.trash
  	},
  	currentItem() {
  		var found = this.$store.state.trash.find( item => item.id == this.recycleId);
  		return found ? found : {};
  	}
  },

  methods: {
  	restore() {
  		if(this.recycleId) {
  			this.$store.dispatch('restoreArticle',this.recycleId);
  		}
  	},
  },

  data () {
    return {
    	
    }
  }
}
</script>

<style lang="scss">
	.recycle {
		height: 100%;
		overflow: hidden;
		.recycle-side {
			position: fixed;
			top: 0;
			left: 0;
			width: 300px;
			height: 100%;
			border-right: 1px solid #d9d9d9;
			overflow-y: auto;
			.recycle-count {
				padding: 5px 10px;
				border-bottom: 1px solid #d9d9d9;
				font-size: 20px;
				font-weight: 400;
				i {
					font-size: 20px;
					margin-right: 2px;
				}
			}
			.recycle-list {
				li {
					border-left: 3px solid transparent;
					&.active {
						background-color: #f2f2f2;
						border-color: #FF9D23;
						.count-down {
							display: none;
						}
						.destroy-date {
							display: block;
						}
					}
					&:hover {
						background-color: #f2f2f2;
					}
					&>a {
						display: block;
						position: relative;
						padding: 25px 10px;
						line-height: 20px;
						border-bottom: 1px solid #d9d9d9;
						color: #333;
						font-size: 12px;
						.destroy-date,.count-down {
							display: none;
							float: right;
							line-height: 1.3;
							color: #b3b3b3;
						}
						.count-down {
							display: block;
						}
						h5 {
							margin: 0;
							width: 155px;
							font-weight: 400;
							font-size: 13px;
							i {
								font-size: 13px;
							}
						}
					}
				}
			}
		}
		.recycle-content {
			height: 100%;
			margin: 0 0 0 300px;
			overflow-x: auto;
			.title {
			    padding: 25px 0;
			    background: #e6e6e6;
			    min-width: 620px;
			    .title-warp {
					position: relative;
					max-width: 800px;
					margin: 0 auto;
					padding: 0 40px 0 25px;
					h3 {
						font-size: 24px;
						line-height: 30px;
						color: #333;
						margin: 0;
					}
					a {
						position: absolute;
						top: -6px;
						right: 15px;
						font-size: 30px;
						color: #595959;
						cursor: pointer;
					}
			    }
			}
			.content-body-wrap {
				padding: 30px 0 50px;
				height: calc(100% - 140px);
				min-width: 620px;
				overflow-x: hidden;
				.content-body {
					max-width: 800px;
					margin: 0 auto;
					padding: 0 25px;
					color: rgba(0,0,0,0.7) !important;
				}
			}
			.content-footer {
				padding: 8px 0;
				text-align: center;
				min-width: 620px;
				&>a {
					cursor: pointer;
				    display: inline-block;
				    padding: 9px 32px 7px;
				    margin: 0 10px;
				    font-size: 16px;
				    color: #fff;
				    border-radius: 4px;
				    &.recover {
						border-color: rgba(#42c02e,0.9);
						background: rgba(#42c02e,0.9);
						&:hover {
							border-color: rgba(#42c02e,1);
							background: rgba(#42c02e,1);
						}
				    }
				    &.delete {
						border-color: rgba(#ec7259,0.9);
						background: rgba(#ec7259,0.9);
						&:hover {
							border-color: rgba(#ec7259,1);
							background: rgba(#ec7259,1);
						}
				    }
				}
			}
		}
	}
</style>