<template>
       <div class="recommend_list col-xs-12 col-sm-4 col-lg-3" >
       	 <div v-for="category in categories">
            <div  class="collection_wrap" >
                <a :href="'/'+category.name_en " target="_blank">
                    <img class="avatar_collection" :src="category.logo">
                    <h4 class="name">
                        {{ category.name }}
                    </h4>
                    <p class="collection_description">
                        欢迎来到爱你城的推荐专题
                        专题公告与特色:
                        {{ category.description }}
                    </p>
                </a>
                <!--    <follow
                    type="categories"
                    id="{{ $category->id }}"
                    user-id="{{ Auth::check() ? Auth::user()->id : false }}"
                    followed="{{ Auth::check() ? Auth::user()->isFollow('categories', $category->id) : false }}">
                  </follow> -->
                <a class="button btn_follow"><span>＋ 关注</span></a>
                <hr/>
                <div class="count">
                    <a href="'/'+category.name_en" target="_blank">
                       {{ category.count }}篇文章
                    </a>
                   {{ category.count_follows }}人关注
                </div>
            </div>
          </div>
        </div>
</template>

<script>
export default {

  name: 'CategoryList',
  
  props:['api','startPage'],

  computed:{
      apiUrl(){
      	var page=this.page;
      	var api=this.api?this.api :this.apiDefault;
      	var api_url=api.indexOf('?')!== -1 ?api +'&page='+page :api +'?page='+page;
      	return api_url;
      }
  },

  mounted(){
  	 this.fetchData();
  	 this.listenScrollBottom();
  },

  methods:{
  	 listenScrollBottom(){
  	 	var m=this;
  	 	$(window).on("scroll",function(){
  	 		var aheadMount=5;
  	 		var is_scroll_to_bottom=$(this).scrollTop() >=$('body').height() -$(window).height() -aheadMount;
  	 		if(is_scroll_to_bottom){
  	 			m.fetchMore();
  	 		}
  	 	});
  	 },

  	 fetchMore(){
  	 	++this.page;
  	 	if(this.lastPage >0 && this.page >this.lastPage){
  	 	    console.log('我是有底线的');

  	 	    return;
  	 	}

  	 	this.fetchData();
  	 },

  	 fetchData(){
  	 	var m=this;

  	 	window.axios.get(this.apiUrl).then(function(response){
  	 		m.categories =m.categories.concat(response.data.data);
  	 		m.lastPage =response.data.last_page;
  	 	});
  	 }
  },

  data () {
    return {
    	apiDefault: '',
    	page: this.startPage ? this.startPage : 1,
    	lastPage: -1,
    	categories:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>