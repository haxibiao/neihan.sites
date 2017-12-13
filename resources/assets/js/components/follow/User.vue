<template>
	<!-- 关注的用户 -->
	<div id="user">
		<div class="main_top clearfix">
            <a class="avatar" href="/v1/user" target="_blank">
                <img src="/images/photo_03.jpg"/>
            </a>
            <a class="botm contribute" href="/v1/user" target="_blank">
                <span>
                	个人主页
                </span>
                <i class="iconfont icon-youbian">
                </i>
            </a>
            <a class="contribute" href="/v1/notification" target="_blank">
                <span>
                    发消息
                </span>
            </a>
            <div class="title">
	            <a class="name" :href="'/user/'+user.id" target="_blank">
	                <span>
	                    {{ user.name }}
	                </span>
	            </a>
        	</div>
            <p>
                写了{{ user.count_words }}字，获得了{{ user.count_favorite }}个喜欢
            </p>
        </div>
        <div>
            <!-- Nav tabs -->
            <ul class="trigger_menu" role="tablist">
            	<li class="active" role="presentation">
                    <a aria-controls="wenzhang" data-toggle="tab" href="#wenzhang" role="tab">
                        <i class="iconfont icon-wenji">
                        </i>
                        <span>最新发布</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="pinglun" data-toggle="tab" href="#pinglun" role="tab">
                        <i class="iconfont icon-svg37">
                        </i>
                        <span>最新评论</span>
                    </a>
                </li>
                <li role="presentation">
                    <a aria-controls="huo" data-toggle="tab" href="#huo" role="tab">
                        <i class="iconfont icon-huo">
                        </i>
                        <span>热门</span>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="wenzhang" role="tabpanel">
                    <ul class="article_list">
					    <li class="article_item have_img">
					        <a class="wrap_img" href="/v1/detail" target="_blank">
					            <img src="/images/details_01.jpeg"/>
					        </a>
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_02.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        空评
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip1.png"/>
					                    </a>
					                    <span class="time">
					                        2天前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
					            </a>
					            <p class="abstract">
					                5月17日下午，腾讯控股公布了2017年第一季度财报。财报显示，腾讯一季度营收495.52亿元，同比增长55%；网络游戏收入增长34%至228.11亿元。其中，就智能手机游戏而言，腾讯实现129亿元收入，同比增长57%，此乃受现有及新的游戏如（《王者荣耀》、《穿越火线：枪战王者》及《龙之谷》）所推动。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    717
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    6
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    13
					                </span>
					            </div>
					        </div>
					    </li>
					    <li class="article_item">
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_03.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        浮云笑此生
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip2.png"/>
					                    </a>
					                    <span class="time">
					                        3周前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                相亲与自由恋爱的比较
					            </a>
					            <p class="abstract">
					                这里的“相亲”主要指的是一种东亚现象。相亲有传统的因素，但它没有遭遇传统普遍的危机，反而是历久弥新。相亲是通过第三方来寻找配偶，第三方可以是父母辈的亲人，可以是同辈的亲友，也可以是中介机构。不管是经由谁介绍，所有相亲的共同特点是：它是一种挑选。由此，相亲也是一种面试，只不过是平等的、双向的面试。这种挑选分为三步进行，第一步是“初试”，这往往是由第三方完成，相亲者并不参与但可能会给第三方提供一些条件；第二步是“复试”，进入复试的往往有多人，此时相亲者互相见面并初步沟通，这也是相亲之所谓相亲的主要方面；第三步是“终试”，往往只有一人进入这个阶段，此时他们往往已经成为实际上的情侣关系，这也是最后考验，直到步入婚姻殿堂为止。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    1795
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    5
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    35
					                </span>
					                <span>
					                    <i class="iconfont icon-qianqianqian">
					                    </i>
					                    7
					                </span>
					            </div>
					        </div>
					    </li>

					</ul>
					<a class="load_more" href="javascript:;">
			            阅读更多
			        </a>
                </div>
                <div class="tab-pane fade" id="pinglun" role="tabpanel">
                    <ul class="article_list">
					    <li class="article_item have_img">
					        <a class="wrap_img" href="/v1/detail" target="_blank">
					            <img src="/images/details_01.jpeg"/>
					        </a>
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_02.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        空评
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip1.png"/>
					                    </a>
					                    <span class="time">
					                        2天前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
					            </a>
					            <p class="abstract">
					                5月17日下午，腾讯控股公布了2017年第一季度财报。财报显示，腾讯一季度营收495.52亿元，同比增长55%；网络游戏收入增长34%至228.11亿元。其中，就智能手机游戏而言，腾讯实现129亿元收入，同比增长57%，此乃受现有及新的游戏如（《王者荣耀》、《穿越火线：枪战王者》及《龙之谷》）所推动。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    717
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    6
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    13
					                </span>
					            </div>
					        </div>
					    </li>
					    <li class="article_item">
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_03.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        浮云笑此生
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip2.png"/>
					                    </a>
					                    <span class="time">
					                        3周前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                相亲与自由恋爱的比较
					            </a>
					            <p class="abstract">
					                这里的“相亲”主要指的是一种东亚现象。相亲有传统的因素，但它没有遭遇传统普遍的危机，反而是历久弥新。相亲是通过第三方来寻找配偶，第三方可以是父母辈的亲人，可以是同辈的亲友，也可以是中介机构。不管是经由谁介绍，所有相亲的共同特点是：它是一种挑选。由此，相亲也是一种面试，只不过是平等的、双向的面试。这种挑选分为三步进行，第一步是“初试”，这往往是由第三方完成，相亲者并不参与但可能会给第三方提供一些条件；第二步是“复试”，进入复试的往往有多人，此时相亲者互相见面并初步沟通，这也是相亲之所谓相亲的主要方面；第三步是“终试”，往往只有一人进入这个阶段，此时他们往往已经成为实际上的情侣关系，这也是最后考验，直到步入婚姻殿堂为止。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    1795
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    5
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    35
					                </span>
					                <span>
					                    <i class="iconfont icon-qianqianqian">
					                    </i>
					                    7
					                </span>
					            </div>
					        </div>
					    </li>
					</ul>
					<a class="load_more" href="javascript:;">
			            阅读更多
			        </a>
                </div>
                <div class="tab-pane fade" id="huo" role="tabpanel">
                    <ul class="article_list">
					    <li class="article_item have_img">
					        <a class="wrap_img" href="/v1/detail" target="_blank">
					            <img src="/images/details_01.jpeg"/>
					        </a>
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_02.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        空评
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip1.png"/>
					                    </a>
					                    <span class="time">
					                        2天前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                为什么说被马化腾点赞的《王者荣耀》已成为全球最赚钱的游戏？
					            </a>
					            <p class="abstract">
					                5月17日下午，腾讯控股公布了2017年第一季度财报。财报显示，腾讯一季度营收495.52亿元，同比增长55%；网络游戏收入增长34%至228.11亿元。其中，就智能手机游戏而言，腾讯实现129亿元收入，同比增长57%，此乃受现有及新的游戏如（《王者荣耀》、《穿越火线：枪战王者》及《龙之谷》）所推动。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    717
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    6
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    13
					                </span>
					            </div>
					        </div>
					    </li>
					    <li class="article_item">
					        <div class="content">
					            <div class="author">
					                <a class="avatar" href="/v1/user" target="_blank">
					                    <img src="/images/photo_03.jpg"/>
					                </a>
					                <div class="info">
					                    <a href="/v1/user" target="_blank">
					                        浮云笑此生
					                    </a>
					                    <a href="/v1/detail" target="_blank">
					                        <img src="/images/vip2.png"/>
					                    </a>
					                    <span class="time">
					                        3周前
					                    </span>
					                </div>
					            </div>
					            <a class="title" href="/v1/detail" target="_blank">
					                相亲与自由恋爱的比较
					            </a>
					            <p class="abstract">
					                这里的“相亲”主要指的是一种东亚现象。相亲有传统的因素，但它没有遭遇传统普遍的危机，反而是历久弥新。相亲是通过第三方来寻找配偶，第三方可以是父母辈的亲人，可以是同辈的亲友，也可以是中介机构。不管是经由谁介绍，所有相亲的共同特点是：它是一种挑选。由此，相亲也是一种面试，只不过是平等的、双向的面试。这种挑选分为三步进行，第一步是“初试”，这往往是由第三方完成，相亲者并不参与但可能会给第三方提供一些条件；第二步是“复试”，进入复试的往往有多人，此时相亲者互相见面并初步沟通，这也是相亲之所谓相亲的主要方面；第三步是“终试”，往往只有一人进入这个阶段，此时他们往往已经成为实际上的情侣关系，这也是最后考验，直到步入婚姻殿堂为止。
					            </p>
					            <div class="meta">
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-liulan">
					                    </i>
					                    1795
					                </a>
					                <a href="/v1/detail" target="_blank">
					                    <i class="iconfont icon-svg37">
					                    </i>
					                    5
					                </a>
					                <span>
					                    <i class="iconfont icon-03xihuan">
					                    </i>
					                    35
					                </span>
					                <span>
					                    <i class="iconfont icon-qianqianqian">
					                    </i>
					                    7
					                </span>
					            </div>
					        </div>
					    </li>
					</ul>
					<a class="load_more" href="javascript:;">
			            阅读更多
			        </a>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
export default {

  name: 'User',

  created(){
     this.fetchData();
  },

  watch:{
  	//路由如果变化会再次执行该方法
  	'$route':'fetchData'
  },

  methods:{
      fetchData() {
           var api_url=window.tokenize('/api/user/'+this.$route.params.id);
           var vm=this;
           window.axios.get(api_url).then(function(response){
           	  vm.user=response.data.user;
           });
      },

  },

  data () {
    return {
         user:null
    }
  }
}
</script>

<style lang="css" scoped>
</style>