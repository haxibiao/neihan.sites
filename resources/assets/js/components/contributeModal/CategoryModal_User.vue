<template>
	<!-- 用户页专题投稿 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="categoryModal_user" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    给该专题投稿
	                    <a href="/article/create" class="btn_font_new">
	                        写篇新文章
	                    </a>
	                </h4>
	                <span class="notice">
	                    每篇文章有总共有5次投稿机会
	                </span>
	                <div class="search_input">
	                	<div class="search_box">
		                    <input placeholder="搜索我的文章" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
		                </div>
	                </div>
	            </div>
	            <div class="modal-body">
	                <ul>
	                    <li v-for="article in articles">
	                        <div>
	                            <div class="note_name">{{ article.title }}</div>
	                            <a href="javascript:;" :class="['btn_base',getBtnClass(article)]" @click="submit(article)">{{ article.submit_status }}</a>
                                <span class="status" v-if="article.submit_status">
                                	{{ article.submited_status }}
                              	</span>
	                        </div>
	                    </li>

	                </ul>
	            </div>
	      
	        </div>
	    </div>
	</div>

</template>

<script>
export default {

  name: 'CategoryModal_User',
  
  props:['categoryId'],

  created(){
  	var m=this;
  	window.$bus.$on('modal_contrinute_clicked',function(id){
  		m.categoryId=id;;
  		m.clear();
  		m.fetchData();
  	});
  },

  mounted(){
         this.fetchData();
  },
 //TODO:加载更多的文章暂时还没有实现
  methods:{
         clear(){
         	this.articles=[];
         },

         loadMore(){
         	if(this.page <this.lastPage){
         		 this.page++;
         		 this.fetchData();
         	}
         },

         fetchData(){
         	var api=window.tokenize('/api/articles/check-category-'+this.categoryId);
         	var vm=this;
         	window.axios.get(api).then(function(response){
         		vm.articles =response.data.data;
         		vm.lastPage =response.data.last_page;
         	});
         },

         submit(article){
         	var api=window.tokenize('/api/article/'+article.id+'/submit-category-'+this.categoryId);
         	var vm=this;
         	window.axios.get(api).then(function(response){
         		article.submit_status=response.data.submit_status;
         		article.submited_status=response.data.submited_status;
         	});
         },

         getBtnClass(article){
         	return article.submit_status.indexOf("投稿")===-1?'btn_revoke' 	:  'btn_push';
         }
  },

  data () {
    return {
            lastPage: null,
            page: 1,
        	articles:[]
    }
  }
  
}
</script>

<style lang="scss" scoped>
	#categoryModal_user {
		.modal-dialog {
			transform: translate(-50%, -50%);
			.modal-header {
		        .notice {
		            font-size: 13px;
		            color: #969696;
		        }
		        .search_input {
		            margin: 20px 0 0;
		        }
		    }
		    .modal-body {
		        height: 460px;
		        ul {
		            li {
		                padding: 20px;
		                font-size: 15px;
		                border-bottom: 1px solid #e6e6e6;
		                position: relative;
		                div {
		                    display: inline-block;
		                    vertical-align: middle;
		                    .note_name {
		                        display: block;
		                    }
		                    .btn_base {
		                        position: absolute;
		                        top: 50%;
		                        right: 20px;
		                        margin-top: -11px;
		                    }
		                    .status {
		                        color: #969696;
		                        font-size: 13px;
		                        display: none;
		                    }
		                    .waiting {
		                    	display: block
		                    }
		                    .cancel {
		                    	display: block
		                    }
		                    .add {
		                    	display: block
		                    }
		                    .reject {
		                    	display: block
		                    }
		                }
		            }
		        }
		    }
		}
	}
</style>