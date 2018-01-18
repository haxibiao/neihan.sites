<template>
	<!-- 个人详情页将文章加入专题 -->
	<div aria-labelledby="myModalLabel" class="modal fade" id="detailModal_home" role="dialog" tabindex="-1">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
	                    <span aria-hidden="true">
	                        ×
	                    </span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">
	                    投稿
	                </h4>
	                <div class="search_input">
	                	<div class="search_box">
		                    <input placeholder="搜索专题投稿" type="text" class="form-control" v-model="q" @keyup.enter="search" />
	                    <i class="iconfont icon-sousuo">
	                    </i>
		                </div>
	                </div>
	            </div>
	            <div class="modal-body">
	                <ul class="clearfix">
	                	<div class="title">
	                		我管理的专题
	                		<a href="/category/create" target="_blank">
	                			<span>新建专题</span>
	                		</a>
	                	</div>
	                    <li v-for="category in categoryList" class="col-xs-12 col-sm-6 col-md-4">
	                        <a :href="'/'+category.name_en" class="avatar avatar_sm avatar_collection">
	                    		<img :src="category.logo" />
	                    	</a>
	                        <div>
	                            <div class="note_name">{{ category.name }}</div>
	                            <span class="note_meta">{{ category.count }}篇文章 · {{ category.follow }}人关注</span>
	                         <a :class="getBtnClass(category.submit_status)" 
                           @click="add(category)">
                            {{ category.submit_status }}
                          </a>
	                        </div>
	                    </li>
	                </ul>
	                <ul class="clearfix">
	                	<div class="title">
	                		推荐专题
	                	</div>
	                    <li v-for="category in recommendCategoryList" class="col-xs-12 col-sm-6 col-md-4">
	                        <a :href="'/'+category.name_en" class="avatar avatar_sm avatar_collection">
	                    		<img :src="category.logo" />
	                    	</a>
	                        <div>
	                            <div class="note_name">{{ category.name }}</div>
	                            <span class="note_meta">{{ category.count }}篇文章 · {{ category.follow }}人关注</span>
                                  <a :class="getBtn2Class(category.submit_status)" 
                           @click="submit(category)">
                            {{ category.submit_status }}
                          </a>
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

  name: 'DetailModal_Home',

  props:['articleId'],

  mounted(){
  	 this.fetchData();
  },

  methods:{
  	  apiUrl(){
  	  	  var api='/api/admin-categories-check-article-'+this.articleId;
  	  	  if(this.q){
  	  	  	api = api+'?q='+this.q;
  	  	  }
  	  	  return window.tokenize(api);
  	  },

  	  apiUrl2(){
  	  	 var api='/api/recommend-categories-check-article-'+this.articleId;
  	  	 return window.tokenize(api);
  	  },

  	  getBtnClass(status){
           switch(status){
           	  case '收录':
           	    return "btn_base btn_revoke";
           }
           return "btn_base btn_push";
  	  },

      getBtn2Class(status) {
        switch(status) {
          case '投稿':
            return "btn-base btn-hollow btn-sm";
          case '再次投稿':
            return "btn-base btn-hollow btn-sm";
        }
        return "btn-base btn-hollow theme-tag btn-sm";
    },
    search() {
      this.page = 1;
      this.fetchData();
    },
    add(category) {
      var api = window.tokenize('/api/article/'+this.articleId+'/add-category-'+category.id);
      window.axios.get(api).then(function(response){
          category.submit_status = response.data.submit_status;
          category.submited_status = response.data.submited_status;
      });
    },
    submit(category) {
      var api = window.tokenize('/api/article/'+this.articleId+'/submit-category-'+category.id);
      window.axios.get(api).then(function(response){
          category.submit_status = response.data.submit_status;
          category.submited_status = response.data.submited_status;
      });
    },
    fetchData() {
      var _this = this;
      window.axios.get(this.apiUrl()).then(function(response){
        if(_this.page == 1) {
          _this.categoryList  = response.data.data;
        } else {
          _this.categoryList = _this.categoryList.concat(response.data.data);
          _this.page = response.data.currentPage;
          _this.page_total = response.data.lastPage;
        }
      });
      window.axios.get(this.apiUrl2()).then(function(response){
        _this.recommendCategoryList = _this.recommendCategoryList.concat(response.data.data);
        _this.page2 = response.data.currentPage;
        _this.page2_total = response.data.lastPage;
      });
    }
  },

  data () {
    return {
      q: null,
      page: 1,
      page_total:1,
      page2: 1,
      page2_total: 1,
      categoryList:[],
      recommendCategoryList:[]

    }
  }
}
</script>

<style lang="scss" scoped>
	#detailModal_home {
	    .modal-dialog {
	        transform: translate(-50%, -50%);
	        width: 960px;
	        @media screen and (max-width: 1080px) {
	            width: 750px;
	        }
	        @media screen and (max-width: 768px) {
	            width: 350px;
	        }
	        .modal-header {
	            .notice {
	                font-size: 13px;
	                color: #969696;
	            }
	            .search_input {
	                position: absolute;
	                top: 15px;
	                right: 80px;
	                margin: 0;
	                input {
	                    width: 240px;
	                }
	                .icon-sousuo {
	                    top: 6px;
	                    right: 12px;
	                }
	            }
	        }
	        .modal-body {
	            padding-bottom: 30px;
	            height: 460px;
	            overflow: auto;
	            ul {
	                .title {
	                    padding: 20px 0 10px 20px;
	                    font-size: 15px;
	                    background-color: #f4f4f4;
	                    border-bottom: 1px solid #f0f0f0;
	                    a {
	                        font-size: 13px;
	                        color: #969696;
	                        padding-left: 10px;
	                    }
	                }
	                li {
	                    position: relative;
	                    font-size: 15px;
	                    padding: 25px 20px;
	                    border-bottom: 1px solid #e6e6e6;
	                    border-right: 1px solid #f0f0f0;
	                    border-bottom: 1px solid #f0f0f0;
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
	                        .note_meta {
	                            font-size: 12px;
	                            color: #969696;
	                            display: inline-block;
	                        }
	                    }
	                }
	            }
	        }
	    }
	}
</style>