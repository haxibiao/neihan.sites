<template>
    <div v-if="!category" class="loading">
        正在加载...
    </div>
    <div v-else="category" class="categorys">
        <!-- 分类信息 -->
        <div class="note-info info-lg">
            <a class="avatar-category" :href="'/'+category.name_en">
            <img :src="category.logo" alt="">
            </a>
            <div class="btn-wrap">
                <a class="btn-base btn-hollow btn-md" :href="'/'+category.name_en">专题主页<i class="iconfont icon-youbian"></i></a>
                <a class="btn-base btn-hollow btn-md" data-target=".modal-contribute" data-toggle="modal"  href="javascript:;" @click="showModal()">投稿</a>
            </div>
            <div class="title">
                <a class="name" :href="'/'+category.name_en">{{ category.name }}</a>
            </div>
            <div class="info">
                收录了{{ category.count }}篇文章 · {{ category.count_follows }}人关注
            </div>
        </div>
        <!-- 内容 -->
        <div class="content">
            <!-- Nav tabs -->
            <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#include" aria-controls="include" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新收录</a>
                </li>
                <li role="presentation">
                    <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
                </li>
                <li role="presentation">
                    <a href="#hot" aria-controls="hot" role="tab" data-toggle="tab"><i class="iconfont icon-huo"></i>热门</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="article_list tab-content">
                <ul role="tabpanel" class="fade in article-list tab-pane active" id="include">
                    <article-list :api="'/'+category.name_en+'?collected=1'" />
                </ul>
                <ul role="tabpanel" class="fade in article-list tab-pane" id="comment">
                    <article-list :api="'/'+category.name_en+'?commented=1'" />
                </ul>
                <ul role="tabpanel" class="fade in article-list tab-pane" id="hot">
                    <article-list :api="'/'+category.name_en+'?hot=1'" />
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    name: 'Category',

    created() {
        this.fetchData();
    },

    watch: {
        // 如果路由有变化，会再次执行该方法
        '$route': 'fetchData'
    },

    methods: {
        showModal() {
            //use vue 2.0 event bus ...
            $bus.$emit('showContribute',this.id);
        },

        fetchData() {
            this.id = this.$route.params.id;
            if(this.id){
                var api_url = window.tokenize('/api/category/' + this.id);
                var vm = this;
                window.axios.get(api_url).then(function(response) {
                    vm.category = response.data;

                    //标记关注的最后查看时间
                    var api_touch = window.tokenize('/api/follow/' + vm.id + '/categories');
                    window.axios.get(api_touch);
                });                
            }
        }

    },

    data() {
        return {
            id: null,
            category: null
        }
    }
}
</script>
<style lang="css" scoped>
</style>