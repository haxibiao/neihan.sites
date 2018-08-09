<template>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">
							上传限制
						</h4>
					</div>
					<div class="modal-body">
						视频最大为50Mb
					</div>
					<div class="modal-footer">
				<button type="button" class="btn btn-default" @click="hideModal">关闭</button>
			</div>
		</div>
	</div>
</div>
</template>

<script>
export default{
	name:"ModalUploadCheck",
	methods:{
		hideModal(){
			$('#myModal').modal('hide');
		}
	}
};
</script>