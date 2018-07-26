<template>
	<li class="article-item have-img" v-if="item.actionable"> 
    <a class="wrap-img" :href="item.actionable.commentable.contentUrl" target="_blank">
      <img :src="item.actionable.commentable.image_url" alt="">
    </a>
    <div class="content">
        <div class="author">
            <a class="avatar" target="_blank" :href="'/user/'+item.user.id">
        		<img :src="item.user.avatar" alt="">
      		</a>
            <div class="info">
                <a class="nickname" target="_blank" :href="'/user/'+item.user.id">{{ item.user.name }}</a>
                <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" :title="appName+'签约作者'" alt="">
                <span class="time"> 发表了评论 · {{ item.time }}</span>
            </div>
        </div>
        <div class="comment" ><p v-html="item.actionable.body"></p></div>  
        <blockquote>
            <a class="title" target="_blank" :href="item.actionable.commentable.contentUrl">
            	<span>{{ item.actionable.commentable.title }}</span>
            </a>
            <p class="abstract">
                {{ item.actionable.commentable.description }}
            </p>
            <div class="meta">
                <div class="origin-author">
                    <a target="_blank" :href="'/user/'+item.user.id">{{ item.user.name }}</a>
                </div>
                <a target="_blank" :href="'/article/'+item.actionable.commentable.id">
            		<i class="iconfont icon-liulan"></i> {{ item.actionable.commentable.hits }}
          		</a>
                <a target="_blank" :href="'/article/'+item.actionable.commentable.id">
            		<i class="iconfont icon-svg37"></i> {{ item.actionable.commentable.count_replies }}
          		</a>
                <span><i class="iconfont icon-03xihuan"></i> {{ item.actionable.commentable.count_likes }}</span>
            </div>
        </blockquote>
    </div>
</li>
</template>

<script>
export default {
	name: "ActionCommentItem",

	props: ["item","appName"],
};
</script>

<style lang="css" scoped>
</style>

