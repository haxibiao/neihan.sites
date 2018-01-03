<template>
	<!-- 个人专题模态框 -->
	<div>
		<!-- 专题收录 -->
		<div aria-labelledby="myModalLabel" class="modal fade" id="categoryModal_home" role="dialog" tabindex="-1">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
		                    <span aria-hidden="true">
		                        ×
		                    </span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel">
		                    收录文章到该专题
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
		                            <a href="javascript:;" class="btn_base btn_push">收录</a>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="btn_base btn_remove">移除</a>
		                            <span class="status add">已加入</span>
		                        </div>
		                    </li>
		                </ul>
		            </div>
		            <!-- <div class="modal-footer"></div> -->
		        </div>
		    </div>
		</div>
		<!-- 专题删除 -->
		<div aria-labelledby="myModalLabel" class="modal fade" id="categoryModal_Delete" role="dialog" tabindex="-1">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
		                    <span aria-hidden="true">
		                        ×
		                    </span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel">
		                    删除专题
		                </h4>
		            </div>
		            <div class="modal-body">
		                <p>该专题目前收录了10篇文章，删除专题操作不可逆，请慎重！</p>
		                <div class="input_box">
		                	<input placeholder="确认删除请输入专题名称" type="text" class="form-control" />
		                </div>
		            </div>
		            <div class="modal-footer">
		            	<div class="cancel" data-dismiss="modal">取消</div>
        				<div class="btn_base btn_remove btn_follow_xs">确认</div>
		            </div>

		        </div>
		    </div>
		</div>
	</div>
</template>

<script>
export default {

  name: 'CategoryModal_Home',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#categoryModal_home {
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
		                    .add {
		                    	display: block
		                    }
		                }
		            }
		        }
		    }
		}
	}
	#categoryModal_Delete {
	    .modal-dialog {
	        position: absolute;
	        left: 25%;
	        top: 25%;
	        max-height: 80%;
	        max-width: 50%;
	        @media screen and (min-width: 1080px) {
	            left: 35%;
	        }
	        .modal-body {
	            padding: 20px;
	            font-size: 15px;
	            p {
	                margin-bottom: 10px;
	            }
	        }
	        .modal-footer {
	            padding: 20px;
	            border: 0;
	            .cancel {
	                display: inline-block;
	                vertical-align: middle;
	                margin-top: 5px;
	                color: #999;
	                cursor: pointer;
	                &:hover {
	                    color: #333;
	                }
	            }
	            .btn_remove {
	                padding: 5px 4px;
	                margin-left: 30px;
	                cursor: pointer;
	            }
	        }
	    }
	}
</style>