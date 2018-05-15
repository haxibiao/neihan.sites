<template>
	<a :data-content="dataContent" :data-placement="placement" class="share
    _button" data-html="true" data-original-title="" data-toggle="popover" data-trigger="focus" role="button" tabindex="0" title="">
        <i class="iconfont icon-duoxuan"></i>
    </a>
</template>

<script>
export default {

  name: 'Catalog',

  props:['placement','articleId'],

  mounted(){
      this.fetchData();
  },

  computed: {
    dataContent() {
       if(this.count_comment.length > 0){ 
        return `<ul class='popover_share_menu'>
                    ${this.comments_count}
                </ul>`;
       }else{
        return `作者没有在这篇文章评论哦`;
       }
    },

    comments_count(){
        var lou='';
        this.count_comment.forEach(function(lou){
                 lou+=`<li>
                            <a href="#author${lou.id}">
                                第${lou}楼
                            </a>
                        </li>`
        });

        
    }
  },

  methods:{
     fetchData(){
        var api='/api/comment/'+this.articleId+'/articles/author'
        var vm=this;
        window.axios.get(api).then(function(response){
             vm.count_comment =response.data;
        });
     }
  },

  data () {
    return {
       count_comment:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>