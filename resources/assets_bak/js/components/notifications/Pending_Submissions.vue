<template>
	<div id="pending_submissions">
		<div class="push_top">
			<router-link to="/requests" class="back_list">
				<i class="iconfont icon-zuobian"></i>
				返回投稿请求列表
			</router-link>
		</div>
		<ul class="article_list">
		    <li v-for="notification in notifications" class="article_item have_img">
		        <a class="wrap_img" :href="'/article/'+notification.article_id " target="_blank">
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
		                    <a :href="'/user/'+notification.article_user_id" target="_blank">
		                        <img src="/images/vip1.png" data-toggle="tooltip" data-placement="top" title="爱你城签约作者" class="badge_icon_xs" />
		                    </a>
		                    <span class="time">
		                        {{ notification.created_at }}
		                    </span>
		                </div>
		            </div>
		            <a class="headline paper_title" href="/v2/detail" target="_blank">
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
		        	<a href="javascript:;" class="btn_base btn_push">接受</a>
		        	<a href="javascript:;" class="btn_base btn_revoke">拒绝</a>
		        	<span class="push_time">{{ notification.created_at }} 投稿</span>
		        </div>
		    </li>
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Pending_Submissions',

   created() {
  	var api_url = window.tokenize('/api/notifications/category_request');
  	var vm = this;
  	window.axios.get(api_url).then(function(response) {
  		vm.notifications = response.data;
  	});

  },

  data () {
    return {
          notifications:[]
    }
  }
}
</script>

<style lang="scss" scoped>
	#pending_submissions {
        .push_top {
            position: fixed;
            z-index: 1;
            width: 780.01px;
            min-height: 35px;
            text-align: center;
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            &::before {
                content: "";
                position: absolute;
                top: -81px;
                left: 0;
                width: 100%;
                height: 81px;
                background-color: #fff;
            }
            .back_list {
                font-size: 14px;
                color: #969696;
                float: left;
                i {
                    font-size: inherit;
                }
                &:hover {
                    color: #333;
                }
            }
            @media screen and (max-width: 1200px) {
                width: 580.01px;
            }
            @media screen and (max-width: 992px) {
                width: 489.99px;
            }
        }
        .article_list {
            padding-top: 50px;
        }
    }
</style>