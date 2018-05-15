<template>
<div>
  <li v-for="question in questions" class="article_item question  question.relateImage?'have_img' :'' ">
    <div v-if="question.bonus >0" class="pay_logo">
        <a href="#" target="_blank">
            <img src="/images/pay_question.png" data-toggle="tooltip" data-placement="top" title="付费问题" class="badge_icon_md"/>
        </a>
    </div>
    
    <div class="question_title">
        <a class="headline paper_title" :href="'/question/'+question.id" target="_blank">
            <span>{{ question.title }}</span>
        </a>

        <div v-if="question.bonus >0" class="question_info"> 
          
           <div v-if="question.deadline">
                  <span>还剩 {{ question.deadline }}</span>
                  <span class="question_follow_num">{{ question.count_answers }} 人已抢答</span>
           </div>
            
            <span v-else>抢答已经结束</span>

            <span class="money">
                <i class="iconfont icon-jinqian1"></i>
                {{ question.bonus }}元
            </span>
        </div>
  
        <div v-else class="question_info">
            <span>{{ question.count_answers }} 回答</span>
            <span class="question_follow_num">{{ question.count_favorites }} 收藏</span>
        </div>
    </div>
    <div class="question_answers">

        <a v-if="question.relateImage" class="wrap_img" href="'/question/'+question.id" target="_blank">
            <img :src="question.relateImage"/>
        </a>


        <div class="content">
            <div class="author">

                <a v-if="question.latestAnswer" class="avatar" :href="'/user/'+question.latestAnswer.user.id " target="_blank">
                    <img :src="question.latestAnswer.user.avatar"/>
                </a>

                <a v-if="question.is_anonymous" class="avatar" href="" target="_blank">
                    <img src="/images/photo_user.png"/>
                </a>

                <a v-else class="avatar" :href="'/user/'+question.user.id" target="_blank">
                    <img :src="question.user.avatar "/>
                </a>

                <div class="info_meta">


                  <div v-if="question.latestAnswer">
                    <a :href="'/user/'+ question.latestAnswer.user.id" target="_blank" class="nickname">
                        {{ question.latestAnswer.user.name }}
                    </a>
                    <a :href="'/question/'+question.id " target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                  </div>
                  
                  <div v-if="question.is_anonymous">
                    <a href="javascript:;" target="_blank" class="nickname">
                        匿名用户
                    </a>
                  </div>

                  <div v-else>
                    <a :href="'/user/'+ question.user.id " target="_blank" class="nickname">
                        {{ question.user.name }}
                    </a>
                    <a :href="'/question/'+question.id" target="_blank">
                        <img src="/images/verified.png" data-toggle="tooltip" data-placement="top" title="爱你城认证" class="badge_icon_xs"/>
                    </a>
                  </div>
                </div>
            </div>
                <p class="abstract">
                   <p v-if="question.latestAnswer">
                    {{ question.latestAnswer.answer  }}
                   </p>
                  <p>
                     {{ question.backgorund }}
                  </p>
        
               </p>
            <div class="meta">
                <a href="/question" target="_blank" class="count count_link">
                    <i class="iconfont icon-liulan">
                    </i>
                    {{ question.hits }}
                </a>
                <a href="/question" target="_blank" class="count count_link">
                    <i class="iconfont icon-svg37">
                    </i>
                    {{ question.count_answers }}
                </a>
                <span class="count">
                    <i class="iconfont icon-03xihuan">
                    </i>
                    {{ question.count_likes }}
                </span>
            </div>
        </div>
    </div>
  </li>
  
   <a class="load_more" href="javascript:;">{{ page >= lastPage ? '已经到底了':'正在加载更多' }}...</a>
</div>
</template>

<script>
export default {

  name: 'QuestionList',

  props: ['api'],

  mounted(){
  	  this.listenScrollBottom();
  },

  methods:{
      apiUrl(){
         return this.api ?this.api+'&page='+this.page:'/question?page='+this.page
      },


  	  fetchData(){
  	  	 var api =this.apiUrl();
  	  	 var vm = this;
  	  	 window.axios.get(api).then(function(response){
             vm.questions =response.data.data;
             vm.lastPage = response.data.last_page;
             
  	  	 });
  	  },

	  listenScrollBottom() {
  		var m = this;
  		$(window).on("scroll", function() {
        var aheadMount =5;
  			var is_scroll_to_bottom=$(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
  			if(is_scroll_to_bottom){
  				m.fetchMore();
  			}
  		});
  	  },

	  fetchMore() {
  		++this.page;
	  	if(this.lastPage > 0 && this.page > this.lastPage) {
	  		console.log('已经没有更多的问题了');
	  		//TODO: ui 提示  ...
	  		return;
	  	}
      this.fetchData();
  	  },
  },

  data () {
    return {
       questions:[],
       page: this.startPage ? this.startPage : 1,
       lastPage: 2,
    }
  }
}
</script>

<style lang="css" scoped>
</style>