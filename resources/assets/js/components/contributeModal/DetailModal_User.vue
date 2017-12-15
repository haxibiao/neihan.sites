<template>
	<!-- 用户详情页模态框 -->
	<div>
		<!-- 文章投稿 -->
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
		                    <a href="#" class="new_note_btn">
		                        新建专题
		                    </a>
		                </h4>
		                <div>
		                    <input placeholder="搜索我管理的专题" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
		                </div>
		            </div>
		            <div class="modal-body">
		                <ul>
		                    <li>
		                    	<a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <span class="meta">菇凉大大 编</span>
		                            <a href="javascript:;" class="action_btn push">收入</a>
		                        </div>
		                    </li>
		                    <li>
		                    	<a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">利用 react native 框架开发app</div>
		                            <span class="meta">菇凉大大编</span>
		                            <span class="status has_add">已加入</span>
		                            <a href="javascript:;" class="action_btn remove" style="display: block;">移除</a>
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

  name: 'DetailModal_User',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>