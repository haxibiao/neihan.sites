<template>
<li class="feed-info" v-if="is_follow">
    <div class="content">
            <div class="author">
                <a class="avatar" target="_blank" href="javascript:;">
                    <img :src="item.user.avatar" alt="">
                </a>
                <div class="info">
                    <a class="nickname" target="_blank" :href="/user/+item.user.id">{{ item.user.name }}</a>
                    <img class="badge-icon" src="/images/signed.png" data-toggle="tooltip" data-placement="top" :title="appName+'签约作者'" alt="">
                    <span class="time" v-if="item.actionable.followed_type == 'users'"> 关注了作者 · {{ item.time }}</span>
                    <span class="time" v-else> 关注了专题 · {{ item.time }}</span>
                </div>
            </div>
            <div class="follow-card">
                <div class="note-info">
                    <a class="avatar" href="javascript:;">
                        <img :src="actionTargetImage" alt="">
                    </a>
                    <follow 
                        :type="item.actionable.followed_type" 
                        :id="item.actionable.followed.id" 
                        :user-id="item.user.id" 
                        :followed="item.actionable.is_follow">            
                    </follow>
                    <div class="title">
                        <a class="name" :href="actionTargetFollowUrl">{{ item.actionable.followed.name }}</a>
                    </div>
                    <div class="info">
                        <p v-if="actionTargetFollowType == 'users'">写了 {{ item.actionable.followed.count_words }} 字，被 {{ item.actionable.followed.count_follows }} 人关注，获得了 {{ item.actionable.followed.count_likes }} 个喜欢</p>
                        <p v-else><a :href="actionTargetFollowUrl">{{ item.actionable.followed.user.name }}</a> 编辑，{{ item.actionable.followed.count }} 篇作品，{{ item.actionable.followed.count_follows }} 人关注</p>
                    </div>
                </div>
                <p class="signature">
                    {{ item.actionable.followed.introduction }}
                </p>
            </div>
        </div>
</li>
</template>

<script>
export default {
	name: "ActionFollowItem",

	props: ["item","appName"],

    computed:{
        is_follow(){
            return this.item.actionable !=null && this.item.actionable.hasOwnProperty('followed');
        },
        actionTargetImage(){
            if(this.item.actionable.followed_type == 'categories'){
                return this.item.actionable.followed.logo;
            }
            return this.item.actionable.followed.avatar;
        },
        actionTargetFollowUrl(){
            if(this.item.actionable.followed_type == 'categories'){
                return '/'+this.item.actionable.followed.name_en;
            }
            return '/user/'+this.item.actionable.followed.id
        },
        actionTargetFollowType(){
            return this.item.actionable.followed_type;
        }
    },
};
</script>

<style lang="css" scoped>
</style>

