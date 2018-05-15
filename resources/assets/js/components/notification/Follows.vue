<template>
	<div>
		<div class="menu">全部关注</div>
		<ul class="follows-list" v-if="notifications.length">
			<li v-for="notification in notifications">
				<div class="follow-item">
					<div class="user-info info-xs">
  					
            <follow type="users" :id="notification.user_id" :user-id="current_user_id" :followed="notification.is_followed"></follow>

  					<a :href="'/user/'+notification.user_id" class="avatar"><img :src="notification.user_avatar" alt=""></a>
  					<div class="title">
  						<a :href="'/user/'+notification.user_id" class="nickname">{{ notification.user_name }}</a>
  						<span>关注了你</span>
  					</div>
  					<div class="info">{{ notification.time }}</div>
          </div>
				</div>
			</li>
		</ul>
    <div v-else class="unMessage">
      <blank-content></blank-content>
    </div>
	</div>
</template>

<script>
export default {

  name: 'Follows',

  computed: {
  	current_user_id() {
  		return window.user.id;
  	}
  },

  created() {
  	var api_url = window.tokenize('/api/notifications/follow');
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

<style lang="css" scoped>
</style>