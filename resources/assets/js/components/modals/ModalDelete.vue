<template>
	    <div class="modal fade delete-modal">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h4 class="modal-title">
									      删除专题
		    							</h4>
	                </div>
	                <div class="modal-body">
	                  <p>该专题目前收录了0篇文章，删除专题操作不可逆，请慎重！</p>
	                  <input type="text" class="input-style" placeholder="确认删除请输入专题名称">
	                </div>
	                <footer class="clearfix">
	                	<button class="submit btn-base btn-hollow btn-sm">确认</button>
	                	<a class="cancel" data-dismiss="modal" aria-label="Close" href="javascript:;">取消</a>
	                </footer>
	            </div>
	        </div>
	    </div>
</template>

<script>
export default {

  name: 'ModalDelete',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>