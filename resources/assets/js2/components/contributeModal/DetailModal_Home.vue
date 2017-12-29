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
		                <div class="search">
		                    <input placeholder="搜索专题投稿" type="text" class="form-control" />
		                    <i class="iconfont icon-sousuo">
		                    </i>
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
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn remove" style="display: block;">移除</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn push">收入</a>
		                        </div>
		                    </li>
		                </ul>
		                <ul class="clearfix">
		                	<div class="title">
		                		推荐专题
		                	</div>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn revoke" style="display: block;">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn revoke" style="display: block;">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn revoke" style="display: block;">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn push">投稿</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn revoke" style="display: block;">撤回</a>
		                        </div>
		                    </li>
		                    <li class="col-xs-12 col-sm-6 col-md-4">
		                        <a href="" class="avatar_collection">
		                    		<img src="/images/category_08.jpg" />
		                    	</a>
		                        <div>
		                            <div class="note_name">情缘未了</div>
		                            <span class="meta">10篇文章 · 50人关注</span>
		                            <a href="javascript:;" class="action_btn push">投稿</a>
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