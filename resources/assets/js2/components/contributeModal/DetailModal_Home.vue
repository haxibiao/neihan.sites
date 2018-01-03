<template>
	<!-- 个人详情页模态框 -->
	<div>
		<!-- 将文章加入专题 -->
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
			                    <input placeholder="搜索专题投稿" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
			                </div>
		                </div>
		            </div>
		            <div class="modal-body">
		                <ul class="clearfix">
		                	<div class="title">
		                		我管理的专题
		                		<a href="/v2/collections/new" target="_blank">
		                			<span>新建专题</span>
		                		</a>
		                	</div>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_remove">移除</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_push">收入</a>
		                        </div>
		                    </li>
		                </ul>
		                <ul class="clearfix">
		                	<div class="title">
		                		推荐专题
		                	</div>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_revoke">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_revoke">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_revoke">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_revoke">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar avatar_sm avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="note_meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                        </div>
		                    </li>
		                </ul>
		            </div>
		            <!-- <div class="modal-footer"></div> -->
		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
export default {

  name: 'DetailModal_Home',

  data () {
    return {

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