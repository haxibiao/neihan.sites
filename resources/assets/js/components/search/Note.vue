<template>
	<div class="note_main">
		<div class="top">
			<div class="relevant">
				<div class="litter_title">
					<span>相关用户</span>
					<a href="/new_search#/user">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="list in 3">
						<div class="author">
							<a href="" class="avatar avatar_sm">
								<img src="/images/photo_02.jpg" />
							</a>
							<div class="info_meta">
								<a href="" class="headline nickname">
									<span class="single_line">旅行青蛙</span>
								</a>
								<p class="meta">写了 1168 字・0 喜欢</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="split_line"></div>
			<div class="relevant">
				<div class="litter_title">
					<span>相关专题</span>
					<a href="/new_search#/collection">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="list in 3">
						<div class="author">
							<a href="" class="avatar avatar_sm avatar_collection">
								<img src="/images/category_06.jpg" />
							</a>
							<div class="info_meta">
								<a href="" class="headline nickname">
									<span class="single_line">旅行青蛙爆红背后的秘密</span>
								</a>
								<p class="meta">119 文章・145 关注</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="search_content">
			<div class="sort_type">
				<a href="javascript:;" class="active">综合排序</a>
				<em>·</em>
				<a href="javascript:;">热门文章</a>
				<em>·</em>
				<a href="javascript:;">最新发布</a>
				<em>·</em>
				<a href="javascript:;">最新评论</a>
				<span class="result">{{ this.article_count }}个结果</span>
			</div>
			<div class="article_list">
				<li class="article_item" v-for="article in articles">
					<div class="content">
			            <div class="author">
			                <a class="avatar" :href="'/user/'+article.user.id" target="_blank">
			                    <img :src="article.user.avatar" />
			                </a>
			                <div class="info_meta">
			                    <a :href="'/user/'+article.user.id" target="_blank" class="nickname">
			                        {{ article.user.name }}
			                    </a>
			                    <span class="time">
			                        {{ article.created_at }}
			                    </span>
			                </div>
			            </div>
			            <a class="headline paper_title" :href="'/article/'+article.id" target="_blank">
			                <span v-html="article.title">
			                </span>
			            </a>
			            <p v-html="article.description" class="abstract">

			            </p>
			            <div class="meta">
			                <a :href="'/article/'+article.id" target="_blank" class="count count_link">
			                    <i class="iconfont icon-liulan">
			                    </i>
			                    {{ article.hits }}
			                </a>
			                <a :href="'/article/'+article.id" target="_blank" class="count count_link">
			                    <i class="iconfont icon-svg37">
			                    </i>
			                    {{ article.count_replies }}
			                </a>
			                <span class="count">
			                    <i class="iconfont icon-03xihuan">
			                    </i>
			                    {{ article.count_likes }}
			                </span>
			            </div>
			        </div>
				</li>
			</div>


			<ul class="pagination" v-if="this.lastPage > 1">
	            <li v-for="page in this.lastPage">
	                <a href="javascript:;" @click="loadMore(page)">
	                    {{ page }}
	                </a>
	            </li>
	        </ul>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Note',

  props:['query'],

  mounted(){
    this.query =this.$route.params.query;
    this.fetchData();
  },

  methods:{
  	fetchData(page=null){
        var vm=this;
        var api= page ?'/api/v2/search/note?page='+page :'/api/v2/search/note';
        var formdata =new FormData();
        formdata.append('query',this.query);
        window.axios.post(api,formdata).then(function(response){
        	vm.articles=response.data.data;
        	vm.article_count =response.data.total;
        	vm.lastPage=response.data.last_page;
               
        	vm.articles.forEach(function(article) {
                  vm.user =article;
        	});
        	console.log(vm.user);
        });
  	},

  	loadMore(page){
        this.fetchData(page);
  	}
  },

  data () {
    return {
    	id:null,
    	article_count:null,
    	lastPage:null,
    	articles:[],
    	user:null,
    	users:[]

    }
  }
}
</script>

<style lang="scss" scoped>
.note_main {
	.top {
		margin-bottom: 30px;
		background-color: hsla(0,0%,71%,.1);
		border: 1px solid #f0f0f0;
		border-radius: 4px;
		.relevant {
			padding: 20px 0;
			.litter_title {
				padding: 0 15px;
				span {
					color: #333;
				}
				a {
					color: #2B89CA;
					font-size: 13px;
					i {
						font-size: 8px;
						font-weight: 700;
					}
				}
			}
			.list {
				padding: 10px 15px 0;
				.col-xs-12 {
					padding: 0 3px;
					.author {
						.avatar_sm {
							margin-right: 5px;
						}
						.info_meta {
							padding: 3px 0 0 55px;
							.headline {
								font-size: 15px;
							}
							.meta {
								line-height: normal;
							}
						}
					}
					@media screen and (max-width: 768px) {
						margin-top: 10px;
					}
				}
			}
		}
		.split_line {
			margin: 0;
		}
	}
	.search_content {
		.article_list {
			.article_item {
				.content {
					.search_result_highlight {
						font-style: normal;
						color: #d96a5f;
					}
				}
			}	
		}
		.pagination {
			margin: 20px 0;
		}
	}
}
</style>