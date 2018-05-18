<template>
	<div class="modal fade image-list-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="gridSystemModalLabel">选择最近图库里的图片</h4>
      		<a href="javascript:;" @click="change" class="rotation">
	            <i class="iconfont icon-shuaxin" ref="fresh"></i>换一批
	        </a>
			<div class="search_input">
	          	<div class="search_box">
	              <input placeholder="搜索图片的关键字" type="text" class="form-control" v-model="query" @keyup.enter="search" />
	              	<i class="iconfont icon-sousuo" @click="search"></i>
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

	mounted() {
		this.fetchData();
	},

	methods: {
		search(e) {
			e.preventDefault();
			this.page = 1;
			this.fetchData();
		},
		change(){
			this.counter ++;
			$(this.$refs.fresh).css("transform", `rotate(${360 * this.counter}deg)`);
			console.log(this.last_page);
			this.page = this.counter%this.last_page;
			this.fetchData();
		},
		fetchData() {
			var api = window.tokenize("/api/image?page="+this.page+"&q=" + this.query);
			var _this = this;
			window.axios.get(api).then(function(response) {
				_this.images = response.data.data;
				_this.page = response.data.current_page;
				_this.last_page = response.data.last_page
			});
		}
	},

	data() {
		return {
			images: [],
			query: '',
			page: 1,
			last_page: 1,
			counter: 1
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
			font-size: 14px;
			i {
				font-size: 14px;
	            display: inline-block;
	            vertical-align: middle;
	            margin: -2px 5px 0 0;
	            &.icon-shuaxin {
	                transform: rotate(360deg);
	                transition: all 0.5s ease-out;
	            }
			}
		}
	}
	.modal-body {
		max-height: 460px;
		overflow: auto;
	}
}
</style>
