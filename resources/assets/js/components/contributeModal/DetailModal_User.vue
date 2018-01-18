<template>
	<!-- 用户详情页文章投稿 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="detailModal_user" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    收入到我管理的专题
	                    <a href="/category/create" class="new_note_btn">
	                        新建专题
	                    </a>
	                </h4>
	                <div class="search_input">
		                <div class="search_box">
		                    <input placeholder="搜索我管理的专题" type="text" class="form-control" v-model="q" 
		                    @keyup.enter="search" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
		                </div>
		            </div>
	            </div>
	            <div class="modal-body">
	                <ul>
	                    <li v-for="category in categoryList">
	                    	<a :href="'/'+category.name_en" class="avatar avatar_sm avatar_collection">
	                    		<img :src="category.logo" />
	                    	</a>
	                        <div>
	                            <div class="note_name">{{ category.name }}</div>
	                            <span class="note_meta">{{ category.user.name }} 编</span>

	                            <span class="btn_base btn_push" v-if="category.status">{{ $category.submited_status }}</span>
	                            <a :class="getBtnClass(category.submit_status)" @click="add(category)">{{ category.submit_status }}</a>
	                        </div>
	                    </li>
	                </ul>
	            </div>
	            <!-- <div class="modal-footer"></div> -->
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'DetailModal_User',

  props:['articleId'],

  mounted(){
      this.fetchData();
  },

  methods:{
  	  apiUrl(){
          var api='/api/admin-categories-check-article-' + this.articleId;
          if(this.q){
          	  api=api+'?q='+this.q;
          }
        return window.tokenize(api);
  	  },

  	  getBtnClass(status){
           switch(status){
           	 case '收录':
           	 return "btn_base btn_push";
           }
           return "btn_base btn-hollow";
  	  },

  	  search(){
  	  	 this.page=1;
  	  	 this.fetchData();
  	  },

  	  add(category){
  	  	  // console.log(this.articleId);
  	  	  var api=window.tokenize('/api/article/'+this.articleId+'/add-category-'+category.id);
  	  	  window.axios.get(api).then(function(response){
  	  	  	    category.submit_status=response.data.submit.status;
  	  	  	    category.submited_status =response.data.submited_status;
  	  	  });
  	  },

  	  fetchData(){
  	  	 var vm=this;
  	  	 window.axios.get(this.apiUrl()).then(function(response){
  	  	 	   if(vm.page==1){
  	  	 	   	  vm.categoryList =response.data.data;
  	  	 	   }else{
  	  	 	   	  vm.categoryList =vm.categoryList.concat(response.data.data);
  	  	 	   }
  	  	 });
  	  }
  },

  data () {
    return {
          q:null,
          page:1,
          categoryList:[]
    }
  }
}
</script>

<style lang="scss" scoped>
	#detailModal_user {
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
	                        .note_meta {
	                            font-size: 12px;
	                            color: #969696;
	                            display: inline-block;
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
	                        .has_add {
	                            font-size: 12px;
	                            color: #42c02e;
	                            display: inline-block;
	                            vertical-align: middle;
	                        }
	                    }
	                }
	            }
	        }
	    }
	}
</style>