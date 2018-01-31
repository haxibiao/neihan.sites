<template>
	<a :data-content="dataContent" :data-placement="placement" class="share
    _button" data-html="true" data-original-title="" data-toggle="popover" data-trigger="focus" role="button" tabindex="0" title="">
        <i class="iconfont icon-yonghu01"></i>
    </a>
</template>

<script>
export default {

  name: 'Writer',

  props:['placement','articleId'],

  created(){
    this.fetchData();
  },

  computed: {
    dataContent() {
        return `<ul class='popover_share_menu'>
                    <li>
                   	 	<div class='author'>
                   	 		<a href='javascript:;' class='avatar avatar_xx'>
	                            <img :src='user.avatar' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		{{ user.name }}
	                        	</a>
	                        </div>
                   	 	</div>
                   	</li>
                </ul>`;
    }
  },

  methods:{
  	 fetchData(){
  	 	var api ='/user/?article_id='+this.articleId;
  	 	var vm =this;
  	 	window.axios.get(api).then(function(response){
  	 		 vm.users =response.data;
  	 	});
  	 }
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>