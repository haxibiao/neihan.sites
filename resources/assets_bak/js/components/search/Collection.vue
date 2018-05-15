<template>
	<div class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<em>·</em>
			<a href="javascript:;">最近更新</a>
			<em>·</em>
			<a href="javascript:;">热门专题</a>
			<span class="result">{{ this.total }}个结果</span>
		</div>
		<ul class="user_list">
            <li v-for="category in categories">
                <div class="author">
                    <a class="avatar avatar_in avatar_collection" href="">
                        <img :src="category.logo" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" :href="'/'+category.name_en">
                            <span class="single_line">{{ category.name }}</span>
                        </a>
                        <p class="meta">
                            收录了 {{ category.count }} 篇文章，{{ category.count_follows }} 人关注
                        </p>
                    </div>
                </div>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'Collection',

  props:['query'],

  mounted(){
    this.fetchData();
  },

  methods:{
     fetchData(page=null){
        var vm=this;
        var api= '/api/v2/search/collection';

        var formdata = new FormData();
        formdata.append('query',this.$route.params.query);
        formdata.append('user_id',window.current_user_id);

        window.axios.post(api,formdata).then(function(response){
              vm.categories=response.data.data;
              vm.total=response.data.total;
        });
     }
  },

  data () {
    return {
        categories:[],
        total:null
    }
  }
}
</script>

<style lang="scss" scoped>
.search_content {
	.user_list {
		@media screen and (max-width: 768px) {
            .btn_base {
                width: 84px;
                padding: 4px;
            }
            .btn_followed {
                padding: 3px;
            }
            .info_meta {
            	padding-right: 90px;
            }
        }
	}
	.pagination {
		margin: 20px 0;
	}
}
</style>