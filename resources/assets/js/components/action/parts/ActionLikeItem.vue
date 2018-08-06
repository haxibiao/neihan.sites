<template>
<li class="article-item have-img">
        <a class="wrap-img"  :href="'/article/'+ actionTargetId" target="_blank">
            <img :src="actionTargetImage" alt="">
        </a>
        <div class="content">
            <div class="author">
                <a class="avatar" target="_blank" :href="'/user/'+item.user.id">
                    <img :src="item.user.avatar" alt="">
                </a>
                <div class="info">
                    <a class="nickname" target="_blank" :href="'/user/'+item.user.id">{{ item.user.name }}</a>
                    <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" :title="appName+'签约作者'" alt="">
                    <span class="time" v-if="item.actionable.liked_type == 'articles'"> 喜欢了作品 · {{ item.time }}</span>
                    <span class="time" v-else> 喜欢了作品的评论 · {{ item.time }}</span>
                </div>
            </div>
            <a class="title" target="_blank" :href="'/article/'+actionTargetId">
                <span>{{ actionTargetTitle }}</span>
            </a>
            <p class="abstract">
                {{ actionTargetBody }}
            </p>
            <div class="meta">
                <div class="origin-author">
                    <a target="_blank" :href="'/user/'+actionTargetUserId">{{ actionTargetUserName }}</a>
                </div>
                <a target="_blank" :href="'/article/'+actionTargetId">
                    <i class="iconfont icon-liulan"></i> {{ actionTargetHits }}
                </a>
                <a target="_blank" :href="'/article/'+actionTargetId">
                    <i class="iconfont icon-svg37"></i> {{ actionTargetReplise }}
                </a>
                <span><i class="iconfont icon-03xihuan"></i> {{ actionTargetLikes }}</span>
            </div>
        </div>
</li>
</template>

<script>
export default {
	name: "ActionLikeItem",

	props: ["item","appName"],

    computed: {
        actionTargetId() {
            if(this.item.actionable.liked_type == 'comments') {
                return this.item.actionable.liked.commentable_id;
            }
            return this.item.actionable.liked.id;
        },
        actionTargetImage(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.image_url;
            }
            return this.item.actionable.liked.image_url
        },
        actionTargetTitle(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.title;
            }
            return this.item.actionable.liked.title;
        },
        actionTargetBody(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.body
            }
            return this.item.actionable.liked.description;
        },
        actionTargetHits(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.hits;
            }
            return this.item.actionable.liked.hits;
        },
        actionTargetReplise(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.count_replies;
            }
            return this.item.actionable.liked.count_replies;
        },
        actionTargetLikes(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.count_likes;
            }
            return this.item.actionable.liked.count_likes;
        },
        actionTargetUserId(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.user_id;
            }
            return this.item.user.id;
        },
        actionTargetUserName(){
            if(this.item.actionable.liked_type == 'comments'){
                return this.item.actionable.liked.commentable.user_name;
            }
            return this.item.user.name;
        }
    }
};
</script>

<style lang="css" scoped>
</style>

