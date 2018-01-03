<template>
	<!-- 用户模态框 -->
	<div>
		<!-- 用户专题投稿 -->
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
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="btn_base btn_push" style="display: none;">投稿</a>
		                            <a href="javascript:;" class="btn_base btn_revoke" style="display: block;">撤回</a>
		                            <span class="status waiting" style="display: block;">待审核</span>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="btn_base btn_push">投稿</a>
		                            <span class="status reject" style="display: block;">已撤回</span>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="btn_base btn_remove" style="display: block;">移除</a>
		                            <span class="status add" style="display: block;">已加入</span>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="btn_base btn_push btn_follow_xs" style="display: block;">再次投稿</a>
		                            <span class="status reject" style="display: block;">未通过</span>
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

  name: 'CategoryModal_User',

  data () {
    return {
    	
    }
  }
  
}
</script>

<style lang="css" scoped>
</style>