<template>
	<div class="question-delete">
    <div v-if="question.deleted">
      <a  class="delete deleted"  disabled="disabled">
        <i class="iconfont icon-lajitong"><span class="text hidden-xs">已删除</span></i>
      </a>
        <span class="tips" v-if="question.message">{{question.message}}</span>
    </div>
    <div v-else>
      <a  class=" delete "  v-on:click="deleteClick()" ref="delete">
        <i class="iconfont icon-lajitong"><span class="text hidden-xs">删除</span></i>
           
      </a>
    </div>  
  </div>
</template>

<script>
export default {

  name: 'DeleteButton',
  
  props: ['id','api'],

  mounted() {
		
  },

  methods: {
     postApiUrl(){
        return   window.tokenize(this.api+this.id);
    },
    deleteClick:function(){
      this.deleted = !this.deleted;
      var el = $(this.$refs.delete);
      var $queation = el.parents('.question-item');
      $queation.css({"opacity": ".5"});
      $queation.find('a').click(function(){
        return false;
      });
     var _this=this;
     window.axios.get(this.postApiUrl()).then(function(response) { 
        _this.question=response.data;
    });
      var $answer = el.parents('.answer-item');
      $answer.css({"opacity": ".5"});
      $answer.find('a').click(function(){
        return false;
      });
    }
  },

  data () {
    return {
        deleted:false,
        question:{message:null,}
    }
  }
}
</script>

<style lang="scss" scoped>
.delete{
  .text{
    margin-left:5px;
    font-size:14px;
  } 
}
.deleted{
  &:hover{
    color:#969696;
  }
}
</style>