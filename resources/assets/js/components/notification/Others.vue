<template>
	<div class="other-message">
		<div class="menu">其他消息</div>
		<div v-if="notifications.length" class="hasMessage">
			<ul class="other-list">
				<li v-for="notification in notifications">
				    <div class="info"><i :class="'iconfont icon-'+iconClass(notification)"></i>
				    	<span v-html="notification.message"></span>
				    </div>
				    <div class="time">{{ notification.time }}</div>
				</li>
			</ul>
		</div>
		<div v-else class="unMessage">
			<blank-content></blank-content>
		</div>
	</div>
</template>

<script>
export default {

  name: 'Others',

  methods: {
  	iconClass(notification) {
  		if(notification.subtype == 'article_approve' && notification.message.indexOf('已收录') > 0) {
  			return 'paihang';
  		}
  		if(notification.subtype == 'article_approve' && notification.message.indexOf('已拒绝') > 0) {
  			return 'ku';
  		}
  		return 'shoucang';
  	}
  },

  created() {
  	var api_url = window.tokenize('/api/notifications/other');
  	var vm = this;
  	window.axios.get(api_url).then(function(response) {
  		vm.notifications = response.data;
  	});

  },

  data () {
    return {
    	notifications: []
    }
  }
}
</script>

<style lang="scss">
	.other-message {
		.hasMessage {
			li {
				.info {
					font-size: 15px;
					line-height: 1.7;
					i {
						color: #FF9D23;
						margin-right: 5px;
						font-size: 20px;
					}
					a {
						color: #2B89CA;
						margin: 0 0 0 5px;
						&:first-of-type {
							margin: 0 5px 0 0;
						}
						&:nth-of-type(n+2) {
							&:before {
								content:'《'
							}
							&:after {
								content:'》'
							}
						}
					}
					span {
						margin: 0 5px;
					}
				}
				.time {
					margin-top: 2px;
			    font-size: 12px;
			    color: #969696;
			    line-height: normal;
				}
			}
		}
	}
</style>