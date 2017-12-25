<template>
	<div id="profile">
	 <div v-if="updated" class="alert alert-success">
 	     更新成功!
      </div>

       <div v-if="fail" class="alert alert-danger">
        更新失败！请检查您的输入是否有错误
      </div>
			<form @submit.prevent="updateing">
		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>

			<tbody class="information">
				<tr>
					<td class="top_line setting_title">性别(此处只可以修改哦)</td>
					<td class="top_line">
						<div>
							<input type="radio" name="gender" value="男" v-model="inputtext.gender"/>
							<span>男</span>
						</div>
						<div>
							<input type="radio" name="gender" value="女" v-model="inputtext.gender"/>
							<span>女</span>
						</div>
						<div>
							<input type="radio" name="gender" value="保密" v-model="inputtext.gender"/>
							<span>保密</span>
						</div>
					</td>
				</tr>

				<tr>
					<td class="setting_title pull-left">个人简介</td>
					<td>
							<textarea :placeholder="user.introduction" cols="30" rows="10" class="form-control" v-model="inputtext.introduction"></textarea>
					</td>
				</tr>

<!-- 				<tr>
					<td class="setting_title pull-left">个人网站</td>
					<td>
						<form>
							<input type="text" placeholder="http://你的网址" class="form-control" />
							<p class="pull-right">填写后会在个人主页显示图标</p>
						</form>
					</td>
				</tr> -->

			</tbody>

		</table>
		<input type="submit" value="保存" class="btn_success" />
		 </form>
	</div>
</template>

<script>
export default {

  name: 'Profile',

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
         var formdata=new FormData();


         formdata.append('gender',this.inputtext.gender);
         formdata.append('introduction',this.inputtext.introduction);

         window.axios.post(api,formdata).then(function(response){
         	   response.data==0 ? vm.fail=true : vm.updated=true;
         });
  	   }
  },

  data () {
    return {
        fail:false,
        updated:false,
        user:[],
        inputtext:{}
    }
  }
}
</script>

<style lang="css" scoped>
</style>