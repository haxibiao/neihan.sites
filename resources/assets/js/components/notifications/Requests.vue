<template>
	<!-- 收到的投稿请求 -->
	<div id="requests">
		<div class="notification_menu">全部投稿请求</div>
		<ul class="requests_list">
			<li>
				<router-link to="/pending_submissions">
					<div class="all_push">
						<i class="iconfont icon-tougaoguanli"></i>
					</div>
					<div class="name">全部未处理请求</div>
				</router-link>
			</li>
			<li v-for="category in categories">
				<router-link :to="'/collections/'+category.id+'/submissions'" @click="clickName(category.name)">
					<div class="avatar avatar_sm avatar_collection">
						   <img :src="category.logo" />
						<span class="badge">{{ category.new_requests }}</span>
					</div>
					<div class="name">
						{{ category.name }}
						<p class="new_abstract">
              {{ category.description }}
            </p>
					</div>
				</router-link>
			</li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Requests',

  created(){
       var api=window.tokenize('/api/new-request-categories');
       var vm=this;
       window.axios.get(api).then(function(response){
          vm.categories=response.data;
       });
  },

  methods:{
       clickname(category_name){
         window.category_name=category_name;
       }
  },

  data () {
    return {
         categories:[],
    }
  }
}
</script>

<style lang="scss" scoped>
	#requests {
        .requests_list {
            li {
                padding: 20px;
                border-top: 1px solid #f0f0f0;
                a {
                    display: block;
                    .all_push {
                    	width: 48px;
                    	height: 48px;
                        border: 1px solid #dcdcdc;
                        border-radius: 10%;
                        text-align: center;
                        display: inline-block;
                        vertical-align: middle;
                        line-height: 48px;
                        margin-right: 10px;
                        i {
                            font-size: 28px;
                            color: #fd5b78;
                            font-weight: 700;
                        }
                    }
                    .name {
                        font-size: 15px;
                        display: inline-block;
                        vertical-align: middle;
                        max-width: 640px;
                        width: 70%;
                        @media screen and (max-width: 540px) {
                            width: 50%;
                        }
                    }
                    .avatar {
                        position: relative;
                        .badge {
                            position: absolute;
                            top: -5px;
                            right: -5px;
                        }
                    }
                }
                @media screen and (max-width: 450px) {
                    padding: 20px 5px;
                }
            }
        }
    }
</style>