<template>
	<div class="modal fade like-user">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" data-dismiss="modal" class="close">×</button>
	                <h4 class="modal-title">喜欢的用户</h4>
	            </div>
	            <div class="modal-body">
	                <ul>
	                    <li v-for="like in likes">
	                    	<a :href="'/user/'+like.user.id" target="_blank" class="avatar"><img :src="like.user.avatar"></a>
		                    <a :href="'/user/'+like.user.id" target="_blank" class="name">{{ like.user.name }}</a> <span class="time">{{ like.time }}</span>
	                    </li>
	                </ul>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'ModalLikeUsers',

  props: ['id', 'type', 'likes'],

  data () {
    return {
    }
  }
}
</script>

<style lang="css" scoped>
</style>