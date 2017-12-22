<template>
	 <div id="basic">
 		 <div v-if="updated" class="alert alert-success">
 	     更新成功!
        </div>

		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="base">
				<tr>
					<td v-if="!avatar" class="top_line">
						<div class="avatar">
							<img :src="user.avatar" />
						</div>
					</td>

					<td v-else class="top_line">
						<div class="avatar">
							<img src="/images/photo_04.png" />
						</div>
					</td>

					<td class="top_line">
						<a href="javascript:;" class="btn_hollow" >
							<input type="file" class="hide" unselectable="on" />
							更换头像
						</a>
					</td>

				</tr>

				<tr>
					<td class="setting_title">昵称</td>
					<td>
						<input type="text" placeholder="请输入昵称" class="form-control" :value="user.name" />
					</td>
				</tr>
			</tbody>
		</table>
		<input type="submit" value="保存" class="btn_success" @click="updateing" />
	</div>
</template>

<script>
export default {

  name: 'Basic',

  created(){
      this.fetchData();
  },

  methods:{
  	  fetchData(){
           var api='/api/user/'+window.current_user_id;
           var vm=this;
           window.axios.get(api).then(function(response){
           	    vm.user=response.data;
           });
  	  },

  	  updateing(){
  	  	  var api=window.tokenize('/api/user/'+window.current_user_id+'/update');
  	  	  var vm=this;
  	  	  window.axios.post(api).then(function(response){
  	  	  	    vm.updated=true;
  	  	  });
  	  },

  	  //update avatar
  	  update_avatar(){
  	  	  var api=window.tokenize('/api/user/'+window.current_user_id+'/avatar');
  	  	  var vm=this;
  	  	  window.axios.post(api).then(function(response){
  	  	  	   vm.avatar =response.data;
  	  	  });
  	  }
  },

  data () {
    return {
       avatar:false,
       updated:false,
       user:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>