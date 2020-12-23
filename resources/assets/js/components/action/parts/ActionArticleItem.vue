<template>
	<li :class="item.actionable.has_image ? 'content-item have-img' : 'content-item'" v-if="item.actionable">
	  <!-- <a class="wrap-img" :href="'/article/'+item.actionable.id"  >
	      <img :src="item.actionable.image_url" alt="">
	  </a> -->
	  <a v-if="item.actionable.has_image" class="wrap-img" :href="item.actionable.url"   >
		      <img :src="item.actionable.primary_image" :alt="item.actionable.title">
		      <span v-if="item.actionable.type =='video'" class="rotate-play">
		        <i class="iconfont icon-shipin"></i>
		      </span>
		      <i  v-if="item.actionable.type =='video'" class="duration">{{ item.actionable.duration }}</i>  <!--当为视频时,取出视频的时长 -->
	  </a>
	  <div class="content">
	    <div class="author">
	      <a class="avatar"   :href="'/user/'+ item.user.id">
	        <img :src="item.user.avatar" alt="">
	      </a> 
	      <div class="info">
	        <a class="nickname"   :href="'/user/'+item.user.id">{{ item.user.name }}</a>
	        	<img v-if="item.user.is_signed" class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" title="签约作者" alt="签约作者">
	    	    <img v-if="item.user.is_editor" class="badge-icon" src="/images/editor.png" data-toggle="tooltip" data-placement="top" title="小编" alt="小编">
	    	    <span class="time" data-shared-at="2017-11-06T09:20:28+08:00">发表了作品 {{ item.time }}</span>
	      </div>
	    </div>
	    <a class="title"   :href="'/article/'+item.actionable.id"><span>{{ item.actionable.title }}</span></a>
	    <p class="abstract">
	      {{ item.actionable.description }}
	    </p>
	    <div class="meta">
	      <a   :href="'/article/'+item.actionable.id">
	        <i class="iconfont icon-liulan"></i> {{ item.actionable.hits }}
	      </a>        
	      <a   :href="'/article/'+item.actionable.id+'/#comments'">
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

	props: ["item", "appName"],

};
</script>

<style lang="css" scoped>
</style>
