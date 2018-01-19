<template>
	<div id="reward_setting">
    <form @submit.prevent="updateing">
		<table>
			<thead>
				<tr>
					<th class="setting_head"></th>
					<th></th>
				</tr>
			</thead>
			<tbody class="setting_pay">
				<tr>
					<td class="top_line setting_title">赞赏功能</td>
					<td class="top_line">
						<div>
							<input type="radio" name="is_tips" value="1"  v-model="user.is_tips"  checked />
							<span>开启</span>
						</div>
						<div>
							<input type="radio" name="is_tips" value="0"  v-model="user.is_tips" />
							<span>关闭</span>
						</div>
						<p>开启后赞赏按钮将出现在你的文章底部</p>
					</td>
				</tr>
				<tr>
					<td class="setting_title pull-left">赞赏描述</td>
					<td>
						<textarea cols="30" rows="10" class="form-control" placeholder="如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！" v-model="user.introduction_tips">{{ this.user.introduction_tips }}</textarea>					
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

  name: 'Reward-Setting',

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
              is_tips:this.user.is_tips,
              introduction_tips:this.user.introduction_tips,
         };

         window.axios.post(api,formdata).then(function(response){
               response.data==0 ? vm.fail=true : vm.updated=true;
         });
       }
  },

  data () {
    return {
        user:[]
    }
  }
}
</script>

<style lang="scss" scoped>
	#reward_setting {
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
            .setting_pay {
                tr {
                    td {
                        textarea {
                            height: 100px;
                            padding: 8px 10px;
                            resize: none;
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
                        p {
                            padding: 8px 0 0;
                            color: #969696;
                            clear: both;
                        }
                    }
                    .setting_title {
                        font-size: 15px;
                        color: #969696;
                    }
                }
            }
        }
    }
</style>