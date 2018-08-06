<template>
		<div class="collection">
			 <!-- 分类信息 -->
			 <div class="note-info info-lg">
	      		<a class="avatar-category" href="/v2/collection">
	        		<img src="/images/collection.png" alt="">
				</a>
				<div class="btn-wrap">
					<a class="btn-base btn-hollow btn-md" :href="'/collection/'+collection.id">文集主页<i class="iconfont icon-youbian"></i></a>
				</div>  
		      <div class="title">
		        <a class="name" href="javascript:;">{{ collection.name }}</a>
		      </div>
		      <div class="info">
		        收录了{{ collection.count }}篇作品 · {{ collection.count_follows }}人关注
		      </div>
			 </div>
			 <!-- 内容 -->
			 <div class="content">
				 <!-- Nav tabs -->
				 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
				   <li role="presentation" class="active">
				   	<a href="#include" aria-controls="include" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新发布</a>
				   </li>
				   <li role="presentation">
				   	<a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
				   </li>
				   <!-- <li role="presentation">
				   	<a href="#catalogue" aria-controls="catalogue" role="tab" data-toggle="tab"><i class="iconfont icon-duoxuan"></i>目录</a>
				   </li> -->
				 </ul>
				 <!-- Tab panes -->
				 <div class="tab-content">
				   <ul role="tabpanel" class="fade in article-list  tab-pane active" id="include">
	 					<article-list :api="'/api/collection/'+collection.id+'/articles?collected=1'" />
				   </ul>
				   <ul role="tabpanel" class="fade article-list  tab-pane" id="comment">
 						<article-list :api="'/api/collection/'+collection.id+'/articles?commented=1'" />
				   </ul>
				   <!-- <ul role="tabpanel" class="fade article-list  tab-pane" id="catalogue">
				   		<article-list :api="'/'+collection.id+'?collected=1'" />				   		
				   </ul> -->
				 </div>
			 </div>
		</div>
</template>

<script>
export default {

  name: 'Collection',

  created() {
        this.fetchData();
    },

    watch: {
        // 如果路由有变化，会再次执行该方法
        '$route': 'fetchData'
    },

    methods: {
        fetchData() {
            this.id = this.$route.params.id;
            if(this.id){
                var api_url = window.tokenize('/api/collection/' + this.id);
                var vm = this;
                window.axios.get(api_url).then(function(response) {
                    vm.collection = response.data;

                    //标记关注的最后查看时间
                    var api_touch = window.tokenize('/api/follow/' + vm.id + '/collections');
                    window.axios.get(api_touch);
                });                
            }
        }

    },

    data() {
        return {
            id: null,
            collection: null
        }
    }
}
</script>

<style lang="css" scoped>
</style>