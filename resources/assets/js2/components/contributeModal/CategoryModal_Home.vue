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
		                    <a href="#" class="new_note_btn">
		                        写篇新文章
		                    </a>
		                </h4>
		                <span class="notice">
		                    每篇文章有总共有5次投稿机会
		                </span>
		                <div>
		                    <input placeholder="搜索我的文章" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
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
		                            <a href="javascript:;" class="btn_base btn_remove" style="display: block;">移除</a>
		                            <span class="status add" style="display: block;">已加入</span>
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
		                <input placeholder="确认删除请输入专题名称" type="text" class="form-control" />
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
	
</style>