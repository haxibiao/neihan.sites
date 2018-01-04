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
	                    <a href="/v2/collections/new" class="btn_font_new" target="_blank">
	                        新建专题
	                    </a>
	                </h4>
	                <div class="search_input">
		                <div class="search_box">
		                    <input placeholder="搜索我管理的专题" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
		                </div>
		            </div>
	            </div>
	            <div class="modal-body">
	                <ul>
	                    <li>
	                    	<a href="" class="avatar avatar_sm avatar_collection">
	                    		<img src="/images/category_08.jpg" />
	                    	</a>
	                        <div>
	                            <div class="note_name">情缘未了</div>
	                            <span class="note_meta">菇凉大大 编</span>
	                            <a href="javascript:;" class="btn_base btn_push">收入</a>
	                        </div>
	                    </li>
	                    <li>
	                    	<a href="" class="avatar avatar_sm avatar_collection">
	                    		<img src="/images/category_08.jpg" />
	                    	</a>
	                        <div>
	                            <div class="note_name">情缘未了</div>
	                            <span class="note_meta">菇凉大大编</span>
	                            <span class="status has_add">已收入</span>
	                            <a href="javascript:;" class="btn_base btn_remove">移除</a>
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

  data () {
    return {

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