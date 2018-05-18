<template>
	<div v-if="!articles.length > 0">
		    什么都没有搜索到
	</div>


	<div v-else class="note_main">
		<div class="top">
			<div class="relevant">
				<div class="litter_title">
					<span>相关用户</span>
					<a href="/user">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="user in users">
						<div class="author">
							<a :href="'/user/'+user.id" class="avatar avatar_sm">
								<img :src="user.avatar" />
							</a>
							<div class="info_meta">
								<a :href="'/user/'+user.id" class="headline nickname">
									<span class="single_line">{{ user.name }}</span>
								</a>
								<p class="meta">写了 {{ user.count_words }} 字・0 喜欢</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="split_line"></div>
			<div class="relevant">
				<div class="litter_title">
					<span>相关专题</span>
					<a href="/categories">
						查看全部
						<i class="iconfont icon-youbian"></i>
					</a>
				</div>
				<div class="list clearfix">
					<div class="col-xs-12 col-sm-4" v-for="category in categories">
						<div class="author">
							<a :href="'/'+category.name_en" class="avatar avatar_sm avatar_collection">
								<img :src="category.logo" />
							</a>
							<div class="info_meta">
								<a :href="'/'+category.name_en" class="headline nickname">
									<span class="single_line">{{ category.name }}</span>
								</a>
								<p class="meta">{{ category.count }} 文章・{{ category.count_follows }} 关注</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="search_content">
			<div class="sort_type">
				<a id='all' href="javascript:;" class="active" @click="fetchData()">综合排序</a>
				<em>·</em>
				<a id="title" href="javascript:;" @click="fetchData(1,order='title')" >按照标题排序</a>
				<em>·</em>
				<a id="body" href="javascript:;" @click="fetchData(1,order='body')" >按照内容排序</a>
				<em>·</em>
				<a id="hits" href="javascript:;" @click="fetchData(1,order='hits')" >热门文章</a>
				<em>·</em>
				<a id="created_at" href="javascript:;" @click="fetchData(1,order='created_at')" >最新发布</a>
				<em>·</em>
				<a id="updated_at" href="javascript:;" @click="fetchData(1,order='updated_at')" >最新评论</a>
				
				<span class="result">{{ this.article_count }}个结果</span>
			</div>

			<div v-if="!is_empty" class="article_list">
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

			
			<div v-if="this.is_empty">
				什么都没有搜索到
			</div>

			<ul class="pagination" v-if="this.lastPage > 1">
	            <li v-for="page in this.lastPage" @click="order?loadMore(page,order):loadMore(page)">
	                <a :class="page==currentPage ?'active':''">
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
  	fetchData(page=null,order=null){
        var vm=this;
        var api= page ?'/api/v2/search/note?page='+page :'/api/v2/search/note';
        var formdata =new FormData();
        formdata.append('query',this.query);
        formdata.append('user_id',window.current_user_id);

        if(order){
        	$('.sort_type a').attr('class','');
        	$('#'+order).addClass('active');
        	formdata.append('order',order);
        }else{
        	$('.sort_type a').attr('class','');
        	$('#all').addClass('active');
        }

        window.axios.post(api,formdata).then(function(response){
        	// console.log(!response.data);
        	if(response.data){ 
	        	vm.articles=response.data.articles.data;
	        	vm.article_count =response.data.articles.total;
	        	vm.lastPage=response.data.articles.last_page;

	        	vm.users =response.data.users;
	        	vm.categories=response.data.categories;
	        	vm.is_empty=false;
            }else{
			    vm.is_empty=true;
            }
 			
        });
  	},

  	loadMore(page,order){
        order?this.fetchData(page,order):this.fetchData(page);
		this.currentPage=page;
  	},

  },

  data () {
    return {
    	id:null,
    	article_count:null,
    	lastPage:null,
    	articles:[],
    	users:[],
    	categories:[],
    	currentPage:1,
    	is_empty:false,
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

		.pagination {
			margin: 20px 0;
		}
	}
}
</style>