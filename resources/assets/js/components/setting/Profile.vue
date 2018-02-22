<template>
	<div id="profile">
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
    			<tbody class="information">
    				<tr>
    					<td class="top_line setting_title">性别</td>
    					<td class="top_line">
    						<div>
    							<input type="radio" name="gender" value="男" v-model="user.gender"/>
    							<span>男</span>
    						</div>
    						<div>
    							<input type="radio" name="gender" value="女" v-model="user.gender"/>
    							<span>女</span>
    						</div>
    						<div>
    							<input type="radio" name="gender" value="保密" v-model="user.gender"/>
    							<span>保密</span>
    						</div>
    					</td>
    				</tr>
    				<tr>
    					<td class="setting_title pull-left">简介</td>
    					<td>
    							<textarea :placeholder="user.introduction" cols="30" rows="10" class="form-control" v-model="user.introduction"></textarea>
    					</td>
    				</tr>
    				<!-- <tr>
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
		    <input type="submit" value="保存" class="btn_base btn_follow" />
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

         var formdata={
              gender:this.user.gender,
              introduction:this.user.introduction,
         };

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
    }
  }
}
</script>

<style lang="scss" scoped>
	#profile {
        table {
            thead {
                tr {
                    .setting_head {
                        width: 200px;
                        @media screen and (max-width: 992px) {
                            width: 140px;
                        }
                        @media screen and (max-width: 768px) {
                            width: 40px;
                        }
                    }
                }
            }
            .information {
                tr {
                    p {
                        padding: 8px 0 0;
                        color: #969696;
                        clear: both;
                    }
                    td {
                        form {
                            textarea {
                                height: 100px;
                                padding: 8px 10px;
                                resize: none;
                            }
                            input[type="text"] {
                                width: 100%;
                            }
                        }
                    }
                    .top_line {
                        padding-top: 0;
                        div {
                            display: inline-block;
                            span {
                                margin: 0 20px 0 6px;
                                font-size: 15px;
                                vertical-align: middle;
                            }
                        }
                    }
                    .setting_title {
                        font-size: 15px;
                        color: #969696;
                    }
                    .weixin_qrcode {
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
                    .social_bind {
                        .social_bind_list {
                            li {
                                line-height: 50px;
                                border-bottom: 1px solid #f0f0f0;
                                .bind_name {
                                    display: inline-block;
                                    i {
                                        width: 30px;
                                        font-size: 20px;
                                        display: inline-block;
                                        vertical-align: middle;
                                    }
                                    .icon-sina {
                                        color: #e05244;
                                    }
                                    .icon-weixin1 {
                                        color: #42c02e;
                                    }
                                    .icon-qq2 {
                                        color: #2B89CA;
                                    }
                                    a {
                                        margin: 0 0 0 20px;
                                        font-size: 14px;
                                        vertical-align: middle;
                                        i {
                                            font-size: 12px;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
</style>