<template>
	<div v-if="show" class="inquisitorial-bottom">
       <div class="container">
         <div class="inquisitorial-tool">
            <a href="javascript:;" class="btn-base btn-theme" @click="submit">这些回答对我有用 {{answered_ids.length+'/10'}}</a>
         </div>
       </div>
     </div>
</template>

<script>
export default {

  name: 'QuestionBottom',

  props: ['questionId'],

  created() {
  	var _this = this;
  	$bus.$on('clickAnswer', function(){
  		_this.answered_ids = $bus.state.answer.answerIds;
  	});
  },

  methods: {
  	submit() {
  		//乐观更新，直接关闭UI采纳状态
  		this.show = false;
  		$bus.$emit('questionAswered');
      
  		window.axios.post(tokenize('/api/question-'+this.questionId+'-answered'), { answered_ids: this.answered_ids}).then(function(response) {
          //重新加载网页来刷新回答的分得奖金状态
          window.location.reload();
      });
  	}
  },

  data () {
    return {
    	show: true,
		  answered_ids: []
    }
  }
}
</script>

<style lang="css" scoped>
</style>