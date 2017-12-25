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
		                    <li v-for="category in categoryList">
		                    	<a href="" class="avatar_collection">
		                    		<img :src="category.imgUrl" />
		                    	</a>
		                        <div>
		                            <div class="note_name">{{ category.categoryName }}</div>
		                            <span class="meta">{{ category.author }} 编</span>
                                    <span class="status has-add" v-if="category.status">
                                {{category.status=='push'?'已收入':''}}
                                     </span>
		                            <a href="javascript:;" class="['action-btn',category.status=='push',?'btn_primary':'btn_hollow']" v-if="category.status">{{category.status=='push'?'移除':'收录'}}</a>
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

  methods:{
  	 actionToggle(){
  	 	var status=this.categoryList[id-1].status=='push'?'reject':'push';
  	 	this.categoryList[id-1].status=status;
  	 }
  },

  data () {
    return {
        categoryList:[
    		{'id':1,'author':'peng','imgUrl':'/images/category_08.jpg','categoryName':'front','status':''},
    		{'id':3,'author':'peng','imgUrl':'/images/category_06.jpg','categoryName':'程序员的自我修养','status':''}
    	]
    }
  }
}
</script>

<style lang="css" scoped>
</style>