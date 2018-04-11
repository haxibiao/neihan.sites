<template>
	<div class="modal fade image-list-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="gridSystemModalLabel">选择最近图库里的图片</h4>
	             <div class="row">
					  <div class="col-lg-6">
					    <div class="input-group">
					      <input type="text" class="form-control" placeholder="搜索图片的关键字" v-model="query">
					      <span class="input-group-btn">
					        <button class="btn btn-default" type="button" @click="fetchData">搜索</button>
					      </span>
					    </div>
					  </div>

				      <span class="input-group-btn">
					        <button class="btn btn-info" type="button" @click="fetchData">换一批</button>
				      </span>
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

  name: 'ImageListModal',

  methods:{

  	fetchData(){
  		var api=window.tokenize('/api/question/image?q='+this.query);
  		var vm=this;

  		window.axios.get(api).then(function(response){
  			 vm.images=response.data;
  		});
  	}
  },

  data () {
    return {
    	images:[],
    	query:null,
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