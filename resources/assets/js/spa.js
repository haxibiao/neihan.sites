/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

// window.Vue = require('vue');

// Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

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


// 关注页
Vue.component('subscriptions-left', require('./components/subscriptions/SubscriptionsLeft.vue'));

const routes = [
    { path: '/subscriptions_add', component: require('./components/subscriptions/SubscriptionsAdd.vue') },
    { path: '/friend', component: require('./components/subscriptions/SubscriptionsFriend.vue') },
    { path: '/category', component: require('./components/subscriptions/SubscriptionsCategory.vue') },
    { path: '/collection', component: require('./components/subscriptions/SubscriptionsCollection.vue') },
    { path: '/user', component: require('./components/subscriptions/SubscriptionsUser.vue') },
    { path: '/comments', component: require('./components/notifications/NotificationsComments.vue') },
    { path: '/chats', component: require('./components/notifications/NotificationsChats.vue') },
    { path: '/requests', component: require('./components/notifications/NotificationsRequests.vue') },
    { path: '/likes', component: require('./components/notifications/NotificationsLikes.vue') },
    { path: '/follows', component: require('./components/notifications/NotificationsFollows.vue') },
    { path: '/tip', component: require('./components/notifications/NotificationsTip.vue') },
    { path: '/others', component: require('./components/notifications/NotificationsOthers.vue') },
    { path: '/chat_messages', component: require('./components/notifications/NotificationsChat_messages.vue') }
];

// 消息页
Vue.component('notifications-left', require('./components/notifications/NotificationsLeft.vue'));

const router = new VueRouter({
    routes
})

const app = new Vue({
    router
}).$mount('#app');
