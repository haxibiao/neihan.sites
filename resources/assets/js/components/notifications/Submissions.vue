<template>
	<div id="submissions">
		<div class="notification_top">
			<router-link to="/requests" class="back_list">
				<i class="iconfont icon-zuobian"></i>
				返回投稿请求列表
			</router-link>
			<div class="thematic">
				<a href="/v2/category/2" target="_blank">王者荣耀</a>
			</div>
			<div class="more_option">
				<input type="checkbox" checked />
				<span>只看未处理投稿</span>
			</div>
		</div>
		<ul class="article_list">
		    <li v-for="notification in notifications" class="article_item have_img">
		        <a class="wrap_img" :href="'/article/'+notification.article_id" target="_blank">
		            <img :src="notification.article_image_url"/>
		        </a>
		        <div class="content">
		            <div class="author">
		                <a class="avatar" :href="'/user/'+notification.article_user_id" target="_blank">
		                    <img :src="notification.article_user_avatar"/>
		                </a>
		                <div class="info_meta">
		                    <a :href="'/user/'+notification.article_user_id" target="_blank" class="nickname">
		                        {{ notification.article_user_name }}
		                    </a>
		                    <a href="javascript:;" target="_blank">
		                        <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs"/>
		                    </a>
		                    <span class="time">
		                        {{ notification.created_at }}
		                    </span>
		                </div>
		            </div>
		            <a class="headline paper_title" :href="'/article/'+notification.article_id" target="_blank">
		                <span>{{ notification.article_title }}</span>
		            </a>
		            <p class="abstract">
		                {{ notification.article_description }}
		            </p>
		            <div class="meta">
		                <a :href="'/article/'+notification.article_id" target="_blank" class="count count_link">
		                    <i class="iconfont icon-liulan">
		                    </i>
		                    {{ notification.article_hits }}
		                </a>
		                <a :href="'/article/'+notification.article_id" target="_blank" class="count count_link">
		                    <i class="iconfont icon-svg37">
		                    </i>
		                    {{ notification.article_count_replies }}
		                </a>
		                <span class="count">
		                    <i class="iconfont icon-03xihuan">
		                    </i>
		                    {{ notification.article_count_likes }}
		                </span>
		            </div>
		        </div>
		        <div class="push_action">
		        	<span class="push_status">
		        		 {{ notification.submited_status }}
		        		<a href="javascript:;" class="btn_font_remove" @click="remove(notification)">移除</a>
		        	
		        	</span>
		        	 	<a href="javascript:;" class="btn_base btn_push" @click="approve(notification)">接受</a>
			        	<a href="javascript:;" class="btn_base btn_revoke" @click="deny(notification)">拒绝</a>
		        	<span class="push_time">{{ notification.time }} 投稿</span>
		        </div>
		    </li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Submissions',

  computed:{
  	 categoryName(){
  	 	return window.category.name;
  	 }
  },

  created(){
  	  var api_url=window.tokenize('/api/notifications/category_request?category_id='+this.$route.params.id);
  	  var vm =this;
  	  window.axios.get(api_url).then(function(response){
  	  	 vm.notifications =response.data;
  	  });
  },

  methods:{
  	  requestApi(notification,api){
  	  	 var vm=this;
  	  	 window.axios.get(api).then(function(response){
  	  	 	 notification.submited_status =response.data.submited_status;
  	  	 });
  	  },

  	  approve(notification){
  	  	 var api=window.tokenize('/api/article/'+notification.article_id+'/approve-category-'+notification.category_id);
  	  	 this.requestApi(notification,api);
  	  },

      deny(notification){
      	 var api =window.tokenize('/api/article/'+notification.article_id+'/approve-category-'+notification.category_id+'?deny=1');
      	 this.requestApi(notification,api);
      },

      remove(notification){

      }
  },

  data () {
    return {
       notifications:[]
    }
  }
}
</script>

<style lang="scss" scoped>
	#submissions {
        .notification_top {
            @media screen and (max-width: 1200px) {
		        width: 580.01px;
		    }
		    @media screen and (max-width: 992px) {
		        width: 489.99px;
		    }
		    @media screen and (max-width: 768px) {
		        text-align: left;
		        .back_list {
		            float: none;
		        }
		        .thematic {
		            display: block;
		            margin-top: 5px;
		            margin-left: 30%;
		        }
		        .more_option {
		            float: none;
		            margin-top: 5px;
		        }
		    }
        }
        .article_list {
            padding-top: 50px;
            @media screen and (max-width: 768px) {
                padding-top: 95px;
            }
        }
    }
</style>