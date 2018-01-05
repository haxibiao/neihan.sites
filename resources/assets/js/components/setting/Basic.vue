<template>
	 <div id="basic">
 		 <div v-if="updated" class="alert alert-success">
 	     更新成功!
      </div>

       <div v-if="fail" class="alert alert-danger">
        更新失败！请检查您的输入是否有错误
      </div>

		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
    <form @submit.prevent="updateing">
			<tbody class="base">
				<tr>
					<td v-if="!avatar" class="top_line">
						<div class="avatar avatar_sp">
							<img :src="user.avatar" />
						</div>
					</td>

					<td v-else class="top_line">
						<div class="avatar">
							<img :src="avatar" />
						</div>
					</td>

					<td class="top_line">
						<a href="javascript:;" class="btn_base btn_hollow btn_hollow_sm" >
							<input type="file" class="hide" unselectable="on" @change="update_avatar($event)"  />
							更换头像
						</a>
					</td>

				</tr>
        
      
  				<tr>
  					<td class="setting_title">昵称</td>
  					<td>
  						<input type="text" :placeholder="user.name" class="form-control" v-model="inputtext.name" />
  					</td>
  				</tr>
    

			</tbody>
             <input type="submit" value="保存" class="btn_base btn_follow" />
       </form>

		</table>
		

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
          var formdata =new FormData();

          formdata.append('name',this.inputtext.name);
  	  	  window.axios.post(api,formdata).then(function(response){
             response.data==0 ? vm.fail=true : vm.updated=true;
  	  	  });
  	  },

  	  //update avatar
  	  update_avatar(event){
  	  	  var api=window.tokenize('/api/user/'+window.current_user_id+'/avatar');
  	  	  var vm=this;
  	  	  var file=event.target.files[0];
  	  	  let formdata=new FormData();

            // formdata.append('name', this.file.name);
            // formdata.append('age', this.file.age);
            // formdata.append('file', this.file.file);
          formdata.append('file',file);

  	  	  let config = {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
           }

  	  	  window.axios.post(api,formdata,config).then(function(response){
  	  	  	   vm.avatar =response.data;
               if(response.status==200){
                  vm.updated=true;
               }
  	  	  });
  	  }
  },

  data () {
    return {
       avatar:false,
       updated:false,
       user:[],
       fail:false,
       inputtext:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>