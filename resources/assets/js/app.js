/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
Vue.prototype.$http = window.axios;
window.bus = new Vue();


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//index
Vue.component('recommend-authors', require('./components/contributeModal/RecommendAuthors.vue'));
Vue.component('categorys-list', require('./components/contributeModal/CategoryList.vue'));


Vue.component('editor', require('./components/Editor.vue'));
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
Vue.component('article-tool', require('./components/contributeModal/ArticleTool.vue'));

Vue.component('user-select', require('./components/UserSelect.vue'));


// 评论
Vue.component('new-comment', require('./components/detail/NewComment.vue'));
Vue.component('comments', require('./components/Comments.vue'));
Vue.component('reply-comment', require('./components/ReplyComment.vue'));


// 详情页文章工具
Vue.component('article-tools', require('./components/detail/ArticleTools.vue'));
Vue.component('writer', require('./components/detail/Writer.vue'));
Vue.component('catalog', require('./components/detail/Catalog.vue'));
Vue.component('comment-tool', require('./components/detail/CommentTool.vue'));


/* 模态框 */

//详情页模态
Vue.component('detailmodal-user', require('./components/contributeModal/DetailModal_User.vue'));
Vue.component('detailmodal-home', require('./components/contributeModal/DetailModal_Home.vue'));
//投稿页面模态
Vue.component('modal-contribute', require('./components/contributeModal/CategoryModal_User'));
// 钱包页
Vue.component('recharge-modal', require('./components/wallet/RechargeModal.vue'));
Vue.component('period-modal', require('./components/wallet/PeriodModal.vue'));
Vue.component('why-modal', require('./components/wallet/WhyModal.vue'));
Vue.component('withdraw-modal', require('./components/wallet/WithdrawModal.vue'));
Vue.component('support-modal', require('./components/detail/SupportModal.vue'));
// 问答页模态
Vue.component('input-matching', require('./components/interlocution/InputMatching.vue'));
Vue.component('question-modal', require('./components/interlocution/QuestionModal.vue'));
Vue.component('answer-tool', require('./components/interlocution/AnswerTool.vue'));
Vue.component('favorite-question', require('./components/interlocution/Favorite.vue'));
// 问题回答页问题的工具
Vue.component('question-tool', require('./components/interlocution/QuestionTool.vue'));
Vue.component('question-list', require('./components/interlocution/QuestionList.vue'));


// 分享
Vue.component('share', require('./components/contributeModal/ShareModal.vue'));

// 小火箭
Vue.component('go-top', require('./components/contributeModal/GoTop.vue'));


Vue.component('article-list', require('./components/ArticleList.vue'));
Vue.component('category-list', require('./components/CategoryList.vue'));

// 空白的页面
Vue.component('blank-content', require('./components/BlankContent.vue'));

// 搜索
Vue.component('search', require('./components/search/SearchBox.vue'));
Vue.component('hot-search', require('./components/search/Hot.vue'));
Vue.component('recently-search', require('./components/search/Recently.vue'));


//editor needed modals..
Vue.component('image-list-modal', require('./modals/ImageListModal.vue'));
Vue.component('image-list', require('./components/ImageList.vue'));
// 主配图
Vue.component('image-select', require('./components/contributeModal/ImageSelect.vue'));

const app = new Vue({
}).$mount('#app');
