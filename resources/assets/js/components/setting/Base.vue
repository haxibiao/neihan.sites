<template>
	<div>
		<table class="form-menu">
		    <tbody class="base">
		        <tr>
		            <td class="top-line">
		                <div class="avatar">
		                	<img :src="user&&user.avatar ? user.avatar : '/images/xbx.jpg'" id="previewImage">
		                </div>
		            </td>
		            <td class="top-line">
		                <a class="btn-base btn-hollow btn-md btn-file">
		                    <input unselectable="on" type="file" @change="previewImage"> 更改头像
		                </a>
		            </td>
		        </tr>
		        <tr>
		            <td class="setting-title">
		                昵称
		            </td>
		            <td>
		                <input type="text" class="input-style" placeholder="请输入昵称" v-model="user.name">
		            </td>
		        </tr>
		    </tbody>
		</table>
		<input type="button" class="btn-base btn-handle btn-md btn-bold" value="保存" @click="save">

	    <div class="alert alert-success" v-if="saved">
	    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    	<strong>提示！</strong> 基本设置保存成功...
	    </div>
	</div>
</template>

<script>
export default {

  name: 'Base',

  created() {
  	this.fetchData();
  },

  methods: {
	previewImage(e) {
		var input = e.target;
	    if (input.files && input.files[0]) {
	    	var fileObj = input.files[0];
	        var reader = new FileReader();
	        reader.onload = function (e) {
            	$('#previewImage').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(fileObj);

	        //upload ...
		    this.upload({
		    	url : window.tokenize('/api/user/save-avatar'),
		    	fileKey: 'avatar',
		    	obj: fileObj
		    });
	    }
	},
  	api() {
  		return window.tokenize('/api/user');
  	},
	fetchData() {
		var _this = this;
		window.axios.get(this.api()).then(function(response){
			_this.user = response.data;
		});
	},
	refreshAvatars() {
		$('img.avatar').each(function(){
			$(this).attr('src', $(this).attr('src') + '?t=' + Date.now());
		});
	},
	save() {
		var formData = {
			name: this.user.name
		};
		var _this = this;
		window.axios.post(this.api(), formData).then(function(response){
			_this.saved = true;
			_this.refreshAvatars();
		});
	},
	upload(file) {
	    var formData = new FormData();
	    formData.append(file.fileKey, file.obj);
	    var config = {
	        headers: {
	           'Content-Type': 'multipart/form-data'
	        }
	    }
	    var _this = this;
  	  	window.axios.post(file.url, formData, config);
  	}
  },

  data () {
    return {
    	saved: null,
    	user: {},
    }
  }
}
</script>

<style lang="scss" scoped>
	.base {
		.avatar {
			width: 100px;
			height: 100px;
		}
	}
</style>