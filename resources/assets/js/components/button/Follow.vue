<template>
    <a v-if="isLogin" :class="isFollowed ? ['btn-base','btn-toggle-followed',sizeClass] : ['btn-base','btn-follow', sizeClass]" @click="toggleFollow">
        <span v-if="!isFollowed"><i class="iconfont icon-icon20"></i>关注</span>
        <span v-else><i class="gougou iconfont icon-weibiaoti12"></i><i class="chacha iconfont icon-cha"></i></span>
    </a>
    <a v-else="!isLogin" :class="['btn-base','btn-follow',sizeClass]" href="/login"><span><i class="iconfont icon-icon20"></i>关注</span></a>
</template>

<script>
    export default {
        props: ['type','id', 'userId', 'followed', 'sizeClass'],

        computed: {
            isLogin() {
                return this.userId > 0;
            },
            isFollowed() {
                return this.is_followed !== null ? this.is_followed : this.followed;
            }
        },

        methods: {
            toggleFollow(){
                //乐观更新UI
                this.is_followed = !this.is_followed;
                
                var _this = this;
                var api_url = window.tokenize('/api/follow/'+ this.id + '/'+ this.type);
                window.axios.post(api_url).then(function(response){
                    _this.is_followed = response.data;
                });
            }
        },

        data() {
            return {
                is_followed: null
            }
        }
    }
</script>
