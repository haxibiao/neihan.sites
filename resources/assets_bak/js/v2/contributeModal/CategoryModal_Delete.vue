<template>
	<!-- 个人页专题删除 -->
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
</template>

<script>
export default {

  name: 'CategoryModal_Delete',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	#categoryModal_Delete {
	    .modal-dialog {
	        max-width: 50%;
	        .modal-body {
	            padding: 20px;
	            font-size: 15px;
	            p {
	                margin-bottom: 10px;
	                color: #333;
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