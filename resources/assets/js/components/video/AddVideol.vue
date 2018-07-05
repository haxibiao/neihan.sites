<template>
    <div>
		<div class="add-videol">
			<i class="iconfont icon-icon20"></i>
		    <p >点击此处上传视频</p>
		    <div class="input_box">
		    	<input type="file" @change="videlo_upload"  multiple accept=".mp4">
		    </div>
		</div>
		<table role="presentation" class="table table-striped">
			<tbody class="files">
				<tr class="template-upload fade in" v-for="videol in videols">
			        <td>
			            <video :src="videol.path" controls=""></video>
			        </td>
			        <td>
			            <p class="name">{{videol.name}}</p>
			        </td>
			        <td>
			            <p class="size">{{videol.size}}</p>
			            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
			        </td>
			        <td class="delete">
		                <button class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <span>Cancel</span>
		                </button>
			        </td>
			    </tr>

			</tbody>
		</table>
	</div>
</template>


<script>
export default {
	name: "AddVideol",
	props: [],

	methods: {
		videlo_upload(e) {
			this.videol = e.target.files[0];
			console.log(this.videol);
		}
	},

	data() {
		return {
			videols: [
				{
					name: "shipi.mp4",
					size: "1.65MB",
					path:
						"blob:https://blueimp.github.io/c2c35472-48aa-4e56-ab87-ddc5d85ceecf"
				},
				{
					name: "shipi.mp4",
					size: "1.65MB",
					path:
						"blob:https://blueimp.github.io/c2c35472-48aa-4e56-ab87-ddc5d85ceecf"
				},
				{
					name: "shipi.mp4",
					size: "1.65MB",
					path:
						"blob:https://blueimp.github.io/c2c35472-48aa-4e56-ab87-ddc5d85ceecf"
				}
			]
		};
	}
};
</script>

<style lang="scss">
.add-videol {
	width: 120px;
	height: 100px;
	text-align: center;
	position: relative;
	margin-left: 40%;
	i {
		font-size: 42px;
		color: #c4c4c4;
	}
	p {
		font-size: 14px;
		color: #2b89ca;
		margin-top: 10px;
	}
	.input_box {
		position: absolute;
		overflow: hidden;
		left: 0;
		top: 0;
		width: 120px;
		height: 100px;
		cursor: pointer;
		input {
			width: 100%;
			height: 100%;
			opacity: 0;
			cursor: pointer;
		}
	}
}
.delete {
	text-align: center;
}
</style>
