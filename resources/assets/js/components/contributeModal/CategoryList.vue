<template>
	<!-- 首页小屏幕显示的专题分类 -->
	<div class="s_s_classification">
		<div class="litter_title">
            <span>
                热门专题
            </span>
            <a href="javascript:;">
                <i class="iconfont icon-shuaxin">
                </i>
                换一批
            </a>
        </div>
	    <a class="collection" href="javascript:;" target="_blank">
	        <div class="name">
	        	动漫女生头像
	        </div>
	    </a>
	    <a class="collection" href="" target="_blank">
	        <div class="name">
	        	韩文昵称
	        </div>
	    </a>
	    <a class="collection" href="" target="_blank">
	        <div class="name">
	        	熊本熊表情包
	        </div>
	    </a>
	    <a class="collection" href="" target="_blank">
	        <div class="name">
	        	无所畏惧的少女
	        </div>
	    </a>
	</div>
</template>

<script>
export default {

  name: 'CategoryList',

  data () {
    return {

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