<template>
        <div class="comment">
                <div>
                    <div class="comment_wrap">
                        <div class="tool_group">
                            <a href="javascript:;">
                                <i class="iconfont icon-fabulous">
                                </i>
                                <span>
                                    {{ this.answer.count_likes }} 赞
                                </span>
                            </a>
                            <a href="/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-dianzan1">
                                </i>
                               {{ this.answer.count_likes }}  踩
                            </a>
                            <a href="javascript:;" @click="showComment">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                   {{ this.answer.count_comments }}  评论
                                </span>
                            </a>
                            <a class="report" href="javascript:;">举报</a>
                        </div>
                    </div>
                </div>
                <div class="sub_comment_list">
                	<div class="comment_wrap" v-if="isComent">
                		<comments  type="answers" :id="this.answerId" :is-login="this.isLogin"></comments>
                	</div>
                </div>
            </div>
</template>

<script>
export default {

  name: 'AnswerTool',

  props:['answerId','isLogin'],

  created(){
  	 this.fetchData();
  },

  methods:{
  	  fetchData(){
  	  	var vm=this;
  	  	var api= "/api/answer/info/"+vm.answerId;
  	  	window.axios.get(api).then(function(response){
  	  		  vm.answer=response.data;
  	  	});
  	  },

  	  showComment(){
  	  	this.isComent=this.isComent? false:true ;
  	  }
  },

  data () {
    return {
         answer:[],
         isComent:false
    }
  }
}
</script>

<style lang="css" scoped>
</style>