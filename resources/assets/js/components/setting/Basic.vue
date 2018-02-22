<template>
	 <div id="basic">
        <transition name="alert">
            <div v-if="updated" class="alert alert-success">
                保存成功!
            </div>
            <div v-if="fail" class="alert alert-danger">
                保存失败！请检查您的输入是否有误!
            </div>
        </transition>
        <form @submit.prevent="updateing">
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
    						<div class="avatar avatar_sp">
    							<img :src="user.avatar+'?t='+dataNow()" id="avatar" />
    						</div>
    					</td>
    					<td v-else class="top_line">
    						<div class="avatar avatar_sp">
    							<img :src="avatar" id="avatar" />
    						</div>
    					</td>
    					<td class="top_line">
    						<a  class="btn_base btn_hollow btn_hollow_sm" >
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
            </table>
            <input type="submit" value="保存" class="btn_base btn_follow" />
       </form>
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

      dataNow(){
          return Date.now();
      },

  	  updateing(){
  	  	  var api=window.tokenize('/api/user/'+window.current_user_id+'/update');
  	  	  var vm=this;
          var formdata =new FormData();

          formdata.append('name',this.inputtext.name);
  	  	  window.axios.post(api,formdata).then(function(response){
            vm.refreshAvatars();
            response.data==0 ? vm.updated=true : vm.fail=true;
               if(response.status==200){
                 // window.location.href = location.href + (location.href.indexOf("?")>-1?"&":"?") + "time="+(new Date()).getTime();
               }
  	  	  });
  	  },


      //refresh avatar
      refreshAvatars() {
          $('#avatar').each(function(){
            $(this).attr('src', $(this).attr('src') + '?t=' + Date.now());
          });
      },

  	  //update avatar
  	  update_avatar(event){
          if(event.target && event.target.files[0]){
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#avatar').attr('src', e.target.result);
                }
                reader.readAsDataURL(event.target.files[0]);
          }

  	  	  var api=window.tokenize('/api/user/'+window.current_user_id+'/avatar');
  	  	  var vm=this;
  	  	  var file=event.target.files[0];
  	  	  let formdata=new FormData();

          formdata.append('file',file);

  	  	  let config = {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
           }

  	  	  window.axios.post(api,formdata,config).then(function(response){      
  	  	  	   vm.avatar =response.data;
  	  	  });
  	  }
  },

  data () {
    return {
       avatar:false,
       user:[],
       inputtext:[],
       fail:false,
       updated:false
    }
  }
}
</script>

<style lang="scss" scoped>
  #basic {
      table {
          thead {
              tr {
                  .setting_head {
                      width: 200px;
                      @media screen and (max-width: 992px) {
                          width: 140px;
                      }
                      @media screen and (max-width: 768px) {
                          width: 85px;
                      }
                  }
              }
          }
          .base {
              tr {
                  td {
                      input[type="text"] {
                          width: 216px;
                          @media screen and (max-width: 768px) {
                              width: 120px;
                          }
                      }
                      form {
                          .btn_follow_xs {
                              padding: 4px;
                              margin-bottom: 14px;
                              @media screen and (max-width: 768px) {
                                  display: block;
                                  margin: 10px 0 0 0;
                              }
                          }
                          input[type="email"] {
                              width: 270px;
                              display: inline-block;
                              @media screen and (max-width: 768px) {
                                  width: 166px;
                              }
                          }
                      }
                      div {
                          display: inline-block;
                          span {
                              margin: 0 20px 0 6px;
                              font-size: 15px;
                              vertical-align: middle;
                          }
                      }
                  }
                  .top_line {
                      padding-top: 0;
                      .avatar {
                          @media screen and (max-width: 768px) {
                              width: 60px;
                              height: 60px;
                          }
                      }
                      .btn_hollow {
                          position: relative;
                          padding: 4px 12px;
                          font-size: 14px;
                          input {
                              position: absolute;
                              display: block!important;
                              top: -1px;
                              left: 1px;
                              width: 85px;
                              height: 30px;
                              opacity: 0;
                          }
                      }
                  }
                  .setting_title {
                      font-size: 15px;
                      color: #969696;
                  }
                  .setted {
                      div {
                          font-size: 15px;
                          display: inline-block;
                          @media screen and (max-width: 768px) {
                              display: block;
                          }
                      }
                      i {
                          font-size: 12px;
                          color: #969696;
                          font-weight: 700;
                          margin: 0 0 0 10px;
                          vertical-align: middle;
                      }
                      span,
                      .cancel_bind {
                          font-size: 14px;
                          color: #969696;
                          margin: 0 0 0 3px;
                          vertical-align: middle;
                      }
                      .cancel_bind {
                          margin: 0 0 0 8px;
                          display: none;
                          &:hover {
                              color: #333;
                          }
                      }
                      &:hover {
                          .cancel_bind {
                              display: inline;
                          }
                      }
                  }
                  &:first-child {
                      border: none;
                  }
              }
          }
      }
  }
</style>