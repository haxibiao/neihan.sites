<template>
	<!-- 个人页专题收录 -->
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
		        padding-bottom: 30px;
	            height: 460px;
	            overflow: auto;
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
</style>