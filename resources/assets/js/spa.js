/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
Vue.prototype.$http = window.axios;

import VueRouter from 'vue-router';
window.bus = new Vue();

Vue.use(VueRouter);

// window.Vue = require('vue');

// Vue.prototype.$http = window.axios;

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
Vue.component('go-top', require('./components/contributeModal/GoTop.vue'));
Vue.component('Article-tool', require('./components/contributeModal/ArticleTool.vue'));

//投稿页面模态
Vue.component('modal-contribute', require('./components/contributeModal/CategoryModal_User'));


// 关注页
Vue.component('follow-left', require('./components/follow/FollowLeft.vue'));

// 消息页
Vue.component('notifications-left', require('./components/notifications/NotificationsLeft.vue'));

// 评论
Vue.component('new-comment', require('./components/detail/NewComment.vue'));
Vue.component('comments', require('./components/Comments.vue'));
Vue.component('reply-comment', require('./components/ReplyComment.vue'));

// 设置页
Vue.component('setting-left', require('./components/setting/SettingLeft.vue'));

// 搜索
Vue.component('search', require('./components/search/SearchBox.vue'));
Vue.component('hot-search', require('./components/search/Hot.vue'));
Vue.component('recently-search', require('./components/search/Recently.vue'));
// 搜索页
Vue.component('search-left', require('./components/search/SearchLeft.vue'));


Vue.component('article-list', require('./components/ArticleList.vue'));
Vue.component('category-list', require('./components/CategoryList.vue'));

// 空白的页面
Vue.component('blank-content', require('./components/BlankContent.vue'));

const routes = [
    // 关注页路由
    { path: '/add', component: require('./components/follow/Add.vue') },
    { path: '/timeline', component: require('./components/follow/Timeline.vue') },
    { path: '/categories/:id', component: require('./components/follow/Category.vue') },
    { path: '/collection/:id', component: require('./components/follow/Collection.vue') },
    { path: '/users/:id', component: require('./components/follow/User.vue') },
    // 消息页路由
    { path: '/comments', component: require('./components/notifications/Comments.vue') },
    { path: '/chats', component: require('./components/notifications/Chats.vue') },
    { path: '/requests', component: require('./components/notifications/Requests.vue') },
    { path: '/likes', component: require('./components/notifications/Likes.vue') },
    { path: '/follows', component: require('./components/notifications/Follows.vue') },
    { path: '/tip', component: require('./components/notifications/Tip.vue') },
    { path: '/others', component: require('./components/notifications/Others.vue') },
    { path: '/chat/:id', component: require('./components/notifications/Chat.vue') },
    { path: '/pending_submissions', component: require('./components/notifications/Pending_Submissions.vue') },
    { path: '/collections/:id/submissions', component: require('./components/notifications/Submissions.vue') },
    // 设置页路由
    { path: '/basic', component: require('./components/setting/Basic.vue') },
    { path: '/profile', component: require('./components/setting/Profile.vue') },
    { path: '/reward-setting', component: require('./components/setting/Reward-Setting.vue') },
    // 搜索页路由
    { path: '/note', component: require('./components/search/Note.vue') },
    { path: '/user', component: require('./components/search/User.vue') },
    { path: '/collection', component: require('./components/search/Collection.vue') },
    { path: '/notebook', component: require('./components/search/NoteBook.vue') }
];

const router = new VueRouter({
    routes
})

const app = new Vue({
    router
}).$mount('#app');
