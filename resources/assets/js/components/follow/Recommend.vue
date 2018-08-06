<template>
    <div v-if="!loaded" class="loading"></div>
    <div v-else="loaded" class="recommends">
        <!-- Nav tabs -->
        <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#entire" aria-controls="entire" role="tab" data-toggle="tab"><i class="iconfont icon-duoxuan"></i>全部推荐</a>
            </li>
            <li role="presentation">
                <a href="#author" aria-controls="author" role="tab" data-toggle="tab"><i class="iconfont icon-yonghu01"></i>推荐作者</a>
            </li>
            <li role="presentation">
                <a href="#category" aria-controls="category" role="tab" data-toggle="tab"><i class="iconfont icon-zhuanti1"></i>推荐专题</a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="note-wrap tab-content">
            <ul role="tabpanel" class="fade in active note-list tab-pane" id="entire">
                <div　v-for="recommend in recommends">
                    <li　v-if="recommend.type=='users'" class="note-info info-md">
                        <a class="avatar" :href="'/user/'+recommend.id"><img :src="recommend.avatar" alt=""></a>
                        
                        <follow type="users" :id="recommend.id" :user-id="user.id" :followed="recommend.is_followed"></follow>

                        <div class="title">
                            <a :href="'/user/'+recommend.id" class="name">{{ recommend.name }}</a>
                        </div>
                        <div class="info">
                            <p class="preview">{{ recommend.introduction }}</p>
                            <p class="preview">
                                <span v-for="collection in recommend.collections"><i class="iconfont icon-wenji"></i>{{ collection.name }}</span>
                            </p>
                            <p class="preview"><a :href="'/user/'+recommend.followed_user_id">{{ recommend.followed_user }}</a>关注了作者</p>
                        </div>
                    </li>
                    <li v-if="recommend.type=='categories'" class="note-info info-md">
                        <a class="avatar-category" :href="'/'+recommend.name_en"><img :src="recommend.logo" alt=""></a>
                        
                        <follow type="categories" :id="recommend.id" :user-id="user.id" :followed="recommend.is_followed"></follow>

                        <div class="title">
                            <a :href="'/'+recommend.name_en" class="name">{{ recommend.name }}</a>
                        </div>
                        <div class="info">
                            <p class="preview">{{ recommend.description }}</p>
                            <p class="preview"><span><i class="iconfont icon-quanbu"></i>{{ recommend.count }}篇作品 · {{ recommend.count_follows }}人关注</span></p>
                            <p class="preview"><a :href="'/user/'+recommend.followed_user_id">{{ recommend.followed_user }}</a>关注了该专题</p>
                        </div>
                    </li>
                </div>
            </ul>
            <ul role="tabpanel" class="fade note-list tab-pane" id="author">
                <li v-for="recommended_user in recommended_users" class="note-info info-md">
                    <a class="avatar" :href="'/user/'+recommended_user.id"><img :src="recommended_user.avatar" alt=""></a>

                    <follow type="users" :id="recommended_user.id" :user-id="user.id" :followed="recommended_user.followed"></follow>

                    <div class="title">
                        <a :href="'/user/'+recommended_user.id" class="name">{{ recommended_user.name }}</a>
                    </div>
                    <div class="info">
                        <p class="preview">{{ recommended_user.introduction }}</p>
                        <p class="preview">
                            <span v-for="collection in recommended_user.collections"><i class="iconfont icon-wenji"></i>{{ collection.name }}</span>
                        </p>
                    </div>
                </li>
            </ul>
            <ul role="tabpanel" class="fade note-list tab-pane" id="category">
                <li v-for="category in recommended_categories" class="note-info info-md">
                    <a class="avatar-category" :href="'/'+category.name_en"><img :src="category.logo" alt=""></a>

                    <follow type="categories" :id="category.id" :user-id="user.id" :followed="category.followed"></follow>

                    <div class="title">
                        <a :href="'/'+category.name_en" class="name">{{ category.name }}</a>
                    </div>
                    <div class="info">
                        <p class="preview">{{ category.description }}</p>
                        <p class="preview"><span><i class="iconfont icon-quanbu"></i>{{ category.count }}篇作品 · {{ category.count_follows }}人关注</span></p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    name: "Recommend",

    created() {
        this.fetchData();
    },

    methods: {
        fetchData() {
            var api_url = window.tokenize("/api/follow/recommends");
            var vm = this;
            window.axios.get(api_url).then(function(response) {
                vm.user = response.data.user;
                vm.recommends = response.data.recommends;
                vm.recommended_users = response.data.recommended_users.data;
                vm.recommended_categories =
                    response.data.recommended_categories.data;
                vm.loaded = true;
            });
        }
    },

    data() {
        return {
            loaded: false,
            user: null,
            recommends: [],
            recommended_users: [],
            recommended_categories: []
        };
    }
};
</script>
<style lang="scss" scoped>
.recommends {
    #trigger-menu {
        li {
            padding: 0 !important;
            border-top: none !important;
        }
    }
    .note-wrap {
        .note-list {
            .note-info {
                margin: 0 0 20px 0;
                padding: 0 0 20px 0;
                border-bottom: 1px solid #f0f0f0;
                p {
                    margin-bottom: 4px;
                }
            }
        }
    }
}
</style>
