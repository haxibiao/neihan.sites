<template>

    <div v-if="!total > 0">
         什么都没有搜索到
    </div>

	<div v-else class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<span class="result">{{ this.total }}个结果</span>
		</div>
		<ul class="user_list">
            <li v-for="collection in collections">
                <div class="author">
                    <a class="avatar avatar_in avatar_collection" :href="'/collection/'+collection.id">
                        <img :src="collection.logo" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" :href="'/collection/'+collection.id">
                            <span class="single_line">{{ collection.name }}</span>
                        </a>
                        <p class="meta">
                            {{ collection.count_words }} 篇文章，{{ collection.count_follows }} 人关注
                        </p>
                    </div>
                </div>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'NoteBook',

  props:['query'],

  mounted(){
    this.fetchData();
  },

  methods:{
     fetchData(page=null){
        var vm=this;
        var api= '/api/v2/search/notebook';

        var formdata = new FormData();
        formdata.append('query',this.$route.params.query);

        window.axios.post(api,formdata).then(function(response){
              vm.collections=response.data.data;
              vm.total=response.data.total;
        });
     }
  },

  data () {
    return {
         collections:[],
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