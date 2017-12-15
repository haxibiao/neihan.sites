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
		                            <a href="javascript:;" class="action_btn push">收录</a>
		                        </div>
		                    </li>
		                    <li>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <a href="javascript:;" class="action_btn remove" style="display: block;">移除</a>
		                            <span class="status add" style="display: block;">已加入</span>
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

<style lang="css" scoped>
</style>