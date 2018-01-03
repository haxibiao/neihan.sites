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
	                    <a href="#" class="btn_font_new">
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
	                    <li>
	                        <div>
	                            <div class="note_name">利用 react native 框架开发app</div>
	                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
	                        </div>
	                    </li>
	                    <li>
	                        <div>
	                            <div class="note_name">利用 react native 框架开发app</div>
	                            <a href="javascript:;" class="btn_base btn_revoke">撤回</a>
	                            <span class="status waiting">待审核</span>
	                        </div>
	                    </li>
	                    <li>
	                        <div>
	                            <div class="note_name">利用 react native 框架开发app</div>
	                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
	                            <span class="status cancel">已撤回</span>
	                        </div>
	                    </li>
	                    <li>
	                        <div>
	                            <div class="note_name">利用 react native 框架开发app</div>
	                            <a href="javascript:;" class="btn_base btn_remove">移除</a>
	                            <span class="status add">已加入</span>
	                        </div>
	                    </li>
	                    <li>
	                        <div>
	                            <div class="note_name">利用 react native 框架开发app利用 react native 框架开发app</div>
	                            <a href="javascript:;" class="btn_base btn_push btn_follow_xs">再次投稿</a>
	                            <span class="status reject">未通过</span>
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

  name: 'CategoryModal_User',

  data () {
    return {
    	
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