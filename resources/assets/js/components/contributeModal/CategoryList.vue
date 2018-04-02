<template>
	<!-- 首页小屏幕显示的专题分类 -->
	<div class="s_s_classification">
		<div class="litter_title">
            <span>
                热门专题
            </span>
            <a href="javascript:;" @click="fetchData" class="rotation">
                <i class="iconfont icon-shuaxin" ref="fresh">
                </i>
                换一批
            </a>
        </div>
    	    <a v-for="category in categories" class="collection" :href="'/'+category.name_en" target="_blank">
    	        <div class="name">
    	        	{{ category.name }}
    	        </div>
    	    </a>
	</div>
</template>

<script>
export default {

  name: 'CategoryList',

  mounted(){
        this.fetchData();
  },

  methods:{
      fetchData(){
         var api='/api/category/commend';
         var vm =this;
         this.counter ++;
         $(this.$refs.fresh).css('transform',`rotate(${360*this.counter}deg)`);
         window.axios.get(api).then(function(response){
             vm.categories=response.data;
         });
      },

      loginCheck(){
        return window.loginCheck;
      }
  },

  data () {
    return {
        categories:[],
        counter:1
    }
  }
}
</script>

<style lang="scss" scoped>
.s_s_classification {
    margin-bottom: 10px;
    display: none;
    .litter_title {
        margin: 10px 0;
        span {
            font-weight: 700;
            color: #333;
        }
    }
    .collection {
    	border-color: #d96a5f;
        color: #d96a5f;
        background-color: inherit;
        &:hover {
            color: #d96a5f;
            background-color: #fff5f1;
        }
    }
}
</style>