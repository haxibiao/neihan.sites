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

  mounted(){
    this.fetchData();
    this.author_each();
  },

  computed: {
  	authorUsers() {
  		var users = '';
  		this.users.forEach(function(ele){
	        users+=`<li id="author-users">
	       	 	<div class='author'>
	       	 		<a href='javascript:;' class='avatar avatar_xx'>
	                    <img src='${ele.avatar}' />
	                </a>
	                <div class='info_meta'>
	                	<a href='' class='nickname'>
	                		${ele.name}
	                	</a>
	                </div>
	       	 	</div>
	       	</li>`
  		});
  		return users;
  	},
    dataContent() {
        return `<ul class='popover_share_menu'>
                    ${this.authorUsers}
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
  	 },
     
     author_each(){
     	$('#author-users').each(function(){
     		 console.log(this.users);
     	});
     	
     }
  },

  data () {
    return {
       users:[]
       ,
    }
  }
}
</script>

<style lang="css" scoped>
</style>