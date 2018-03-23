<template>
	<div class="modal fade image-list-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="gridSystemModalLabel">选择最近图库里的图片</h4>
		    </div>
        <div class="modal-body">
        	<div class="col-md-12">
        		<image-list></image-list>
        	</div>
        </div>
	    </div>
	  </div>
	</div>
</template>

<script>
export default {

  name: 'ImageListModal',

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
.modal-body {
	max-height: 460px;
	overflow: auto;
}
</style>