<template>
	<div class="modal fade image-list-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="gridSystemModalLabel">选择最近图库里的图片</h4>
      		<a href="javascript:;" @click="fetchData" class="rotation">
            <i class="iconfont icon-shuaxin" ref="fresh">
            </i>
            换一批
          </a>
			<div class="search_input">
	          	<div class="search_box">
	              <input placeholder="搜索图片的关键字" type="text" class="form-control" v-model="query" />
	              <i class="iconfont icon-sousuo" @click="fetchData">
	              </i>
	            </div>
	         </div>
		    </div>
        <div class="modal-body">
        	<div class="col-md-12">
        		<image-list :images="this.images" ></image-list>
        	</div>
        </div>
	    </div>
	  </div>
	</div>
</template>

<script>
export default {
	name: "ModalImages",

	methods: {
		fetchData() {
			$(this.$refs.fresh).css("transform", `rotate(${360 * this.changes}deg)`);
			this.changes++;

			// var api = window.tokenize("/api/question/image?q=" + this.query);
			// var vm = this;

			// window.axios.get(api).then(function(response) {
			// 	vm.images = response.data;
			// });
		}
	},

	data() {
		return {
			images: [],
			query: null,
			changes: 1
		};
	}
};
</script>

<style lang="scss" scoped>
.image-list-modal {
	.modal-header {
		max-height: 300px;
		overflow: auto;
		.search_input {
			margin-top: 15px;
			.search_box {
				position: relative;
				.icon-sousuo {
					position: absolute;
					top: 4px;
					right: 10px;
					font-size: 21px;
				}
			}
		}
		.rotation {
			float: right;
			position: absolute;
			top: 25px;
			right: 70px;
			color: #969696;
			font-size: 16px;
			i {
				font-size: 16px;
			}
		}
	}
	.modal-body {
		max-height: 460px;
		overflow: auto;
	}
}
</style>
