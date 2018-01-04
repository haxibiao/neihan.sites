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
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//index
Vue.component('recommend-authors', require('./components/contributeModal/RecommendAuthors.vue'));

Vue.component('example', require('./components/Example.vue'));
Vue.component('bar', require('./components/Bar.vue'));
Vue.component('line-chart', require('./components/Line.vue'));
Vue.component('single-list', require('./components/SingleList.vue'));


Vue.component('my-image-list', require('./components/MyImageList.vue'));
Vue.component('my-video-list', require('./components/MyVideoList.vue'));
Vue.component('single-list-create', require('./components/SingleListCreate.vue'));
Vue.component('single-list-select', require('./components/SingleListSelect.vue'));

Vue.component('favorite', require('./components/Favorite.vue'));
Vue.component('like', require('./components/Like.vue'));
Vue.component('comment', require('./components/Comment.vue'));
Vue.component('follow', require('./components/Follow.vue'));
Vue.component('comments', require('./components/Comments.vue'));
Vue.component('search', require('./components/Search.vue'));
// Vue.component('article-tool', require('./components/contributeModal/ArticleTool.vue'));

// 详情页评论
Vue.component('new-comment', require('./components/detail/NewComment.vue'));

// 模态框
Vue.component('categorymodal-home', require('./components/contributeModal/CategoryModal_Home.vue'));
Vue.component('categorymodal-delete', require('./components/contributeModal/CategoryModal_Delete.vue'));
Vue.component('categorymodal-user', require('./components/contributeModal/CategoryModal_User.vue'));
Vue.component('detailmodal-home', require('./components/detail/DetailModal_Home.vue'));
Vue.component('detailmodal-user', require('./components/detail/DetailModal_User.vue'));
Vue.component('recharge-modal', require('./components/wallet/RechargeModal.vue'));

Vue.component('article-list', require('./components/ArticleList.vue'));
Vue.component('category-list', require('./components/CategoryList.vue'));

const app = new Vue({
}).$mount('#app');
