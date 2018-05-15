<template>
    <div v-if="!users.length > 0">
            什么都没有搜索到
    </div>

	<div v-else class="search_content">
		<div class="sort_type">
			<a href="javascript:;" class="active">综合排序</a>
			<span class="result">{{ this.count_user }}个结果</span>
		</div>
		<ul class="user_list">
            <li v-for="user in users">
                <div class="author">
                    <a class="avatar avatar_in" href="">
                        <img :src="user.avatar" />
                    </a>
                    <follow>
                    </follow>
                    <div class="info_meta">
                        <a class="headline nickname" href="">
                            <span class="single_line">旅行青蛙</span>
                        </a>
                        <div class="meta">
                            <span>
                                关注 {{ user.count_followings }}
                            </span>
                            <span>
                                粉丝 {{ user.count_follows }}
                            </span>
                            <span>
                                文章 {{ user.count_articles }}
                            </span>
                        </div>
                        <p class="meta">
                            写了 {{ user.count_words }} 字，获得了 {{ user.count_likes }} 个喜欢
                        </p>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="pagination" v-if="this.lastPage > 1">
            <li v-for="page in this.lastPage">
                <a href="javascript:;" @click="loadMore(page)">
                    {{ page }}
                </a>
            </li>
        </ul>
	</div>
</template>

<script>
export default {

  name: 'User',

  props:['query'],

  mounted(){
    this.fetchData();
  },

  methods:{
      fetchData(page=null){
        var vm=this;
        var api= page ? '/api/v2/search/user?page='+page:'/api/v2/search/user';

        var formdata = new FormData();
        formdata.append('query',this.$route.params.query);
        formdata.append('user_id',window.current_user_id);

        window.axios.post(api,formdata).then(function(response){
                vm.users=response.data;
                vm.count_user=response.data.total;
        });
      },

      loadMore(page){
        this.fetchData(page);
      },

      isSelf(userId){
        return window.current_user_id ==userId;
      }
  },


  data () {
    return {
            lastPage:null,
            users:[],
            count_user:null
    }
  }
}
</script>

<style lang="scss" scoped>
.search_content {
	.user_list {
		.info_meta {
			.meta {
				line-height: normal;
				padding-top: 3px;
			}
		}
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