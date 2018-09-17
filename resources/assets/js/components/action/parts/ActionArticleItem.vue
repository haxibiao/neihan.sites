<template>
	<li class="article-item have-img" v-if="item.actionable">
	  <a class="wrap-img" :href="'/article/'+item.actionable.id" target="_blank">
	      <img :src="item.actionable.image_url" alt="">
	  </a>
	  <div class="content">
	    <div class="author">
	      <a class="avatar" target="_blank" :href="'/user/'+ item.user.id">
	        <img :src="item.user.avatar" alt="">
	      </a> 
	      <div class="info">
	        <a class="nickname" target="_blank" :href="'/user/'+item.user.id">{{ item.user.name }}</a>
	        <!-- <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" :title="appName+'签约作者'" alt=""> -->
	        <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">发表了作品 {{ item.time }}</span>
	      </div>
	    </div>
	    <a class="title" target="_blank" :href="'/article/'+item.actionable.id"><span>{{ item.actionable.title }}</span></a>
	    <p class="abstract">
	      {{ item.actionable.description }}
	    </p>
	    <div class="meta">
	      <a target="_blank" :href="'/article/'+item.actionable.id">
	        <i class="iconfont icon-liulan"></i> {{ item.actionable.hits }}
	      </a>        
	      <a target="_blank" :href="'/article/'+item.actionable.id+'/#comments'">
	        <i class="iconfont icon-svg37"></i> {{ item.actionable.count_replies }}
	      </a>      
	      <span><i class="iconfont icon-03xihuan"></i> {{ item.actionable.count_likes }}</span>
	    </div>
	  </div>
	</li>
</template>

<script>
export default {
	name: "ActionArticleItem",

	props: ["item", "appName"]
};
</script>

<style lang="css" scoped>
</style>
