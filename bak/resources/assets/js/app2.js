/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding v2 to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//index
Vue.component('recommend-authors', require('./v2/contributeModal/RecommendAuthors.vue'));

Vue.component('editor', require('./components/Editor.vue'));
Vue.component('bar', require('./v2/Bar.vue'));
Vue.component('line-chart', require('./v2/Line.vue'));
Vue.component('single-list', require('./v2/SingleList.vue'));


Vue.component('my-image-list', require('./v2/MyImageList.vue'));
Vue.component('my-video-list', require('./v2/MyVideoList.vue'));
Vue.component('single-list-create', require('./v2/SingleListCreate.vue'));
Vue.component('single-list-select', require('./v2/SingleListSelect.vue'));

Vue.component('favorite', require('./v2/Favorite.vue'));
Vue.component('like', require('./v2/Like.vue'));
Vue.component('comment', require('./v2/Comment.vue'));
Vue.component('follow', require('./v2/Follow.vue'));
Vue.component('comments', require('./v2/Comments.vue'));
Vue.component('search', require('./v2/Search.vue'));
// Vue.component('article-tool', require('./v2/contributeModal/ArticleTool.vue'));


/* 模态框 */
// 专题页
Vue.component('categorymodal-home', require('./v2/contributeModal/CategoryModal_Home.vue'));
Vue.component('categorymodal-delete', require('./v2/contributeModal/CategoryModal_Delete.vue'));
Vue.component('categorymodal-user', require('./v2/contributeModal/CategoryModal_User.vue'));
// 详情页
Vue.component('detailmodal-home', require('./v2/detail/DetailModal_Home.vue'));
Vue.component('detailmodal-user', require('./v2/detail/DetailModal_User.vue'));
Vue.component('support-modal', require('./v2/detail/SupportModal.vue'));
// 钱包页
Vue.component('recharge-modal', require('./v2/wallet/RechargeModal.vue'));
Vue.component('period-modal', require('./v2/wallet/PeriodModal.vue'));
Vue.component('why-modal', require('./v2/wallet/WhyModal.vue'));
Vue.component('withdraw-modal', require('./v2/wallet/WithdrawModal.vue'));

// 详情页评论
Vue.component('new-comment', require('./v2/detail/NewComment.vue'));


// 分享
Vue.component('share', require('./v2/contributeModal/ShareModal.vue'));

/*  */

Vue.component('article-list', require('./v2/ArticleList.vue'));
Vue.component('category-list', require('./v2/CategoryList.vue'));

const app = new Vue({
}).$mount('#app');
