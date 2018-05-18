/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
Vue.prototype.$http = window.axios;

import VueRouter from 'vue-router';

Vue.use(VueRouter);

// window.Vue = require('vue');

// Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding v2 to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//index

Vue.component('recommend-authors', require('./v2/contributeModal/RecommendAuthors.vue'));
Vue.component('example', require('./v2/Example.vue'));
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



/*  */
Vue.component('article-list', require('./v2/ArticleList.vue'));
Vue.component('category-list', require('./v2/CategoryList.vue'));


// 关注页
Vue.component('follow-left', require('./v2/follow/FollowLeft.vue'));

// 消息页
Vue.component('notifications-left', require('./v2/notification/NotificationLeft.vue'));

// 设置页
Vue.component('setting-left', require('./v2/setting/SettingLeft.vue'));

const routes = [
    // 关注页路由
    { path: '/add', component: require('./v2/follow/Add.vue') },
    { path: '/timeline', component: require('./v2/follow/Timeline.vue') },
    { path: '/categories/:id', component: require('./v2/follow/Category.vue') },
    { path: '/collection/:id', component: require('./v2/follow/Collection.vue') },
    { path: '/users/:id', component: require('./v2/follow/User.vue') },
    // 消息页路由
    { path: '/comments', component: require('./v2/notification/Comments.vue') },
    { path: '/chats', component: require('./v2/notification/Chats.vue') },
    { path: '/requests', component: require('./v2/notification/Requests.vue') },
    { path: '/likes', component: require('./v2/notification/Likes.vue') },
    { path: '/follows', component: require('./v2/notification/Follows.vue') },
    { path: '/tip', component: require('./v2/notification/Tip.vue') },
    { path: '/others', component: require('./v2/notification/Others.vue') },
    { path: '/chat/:id', component: require('./v2/notification/Chat.vue') },
    { path: '/pending_submissions', component: require('./v2/notification/Pending_Submissions.vue') },
    { path: '/collections/:id/submissions', component: require('./v2/notification/Submissions.vue') },
    // 设置页路由
    { path: '/basic', component: require('./v2/setting/Basic.vue') },
    { path: '/profile', component: require('./v2/setting/Profile.vue') },
    { path: '/reward-setting', component: require('./v2/setting/Reward-Setting.vue') }
];

const router = new VueRouter({
    routes
})

const app = new Vue({
    router
}).$mount('#app');
