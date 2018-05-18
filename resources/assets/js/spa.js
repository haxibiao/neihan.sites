/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);
window.$bus = new Vue();
Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//关注，消息spa页面
Vue.component("blank-content", require("./components/BlankContent.vue"));
Vue.component("follow", require("./components/Follow.vue"));
Vue.component("article-list", require("./components/ArticleList.vue"));
Vue.component("hot-search", require("./components/search/Hot.vue"));
Vue.component("search-box", require("./components/search/SearchBox.vue"));
Vue.component("recently", require("./components/search/Recently.vue"));

Vue.component("notification-aside", require("./components/notification/Aside.vue"));
Vue.component("follow-aside", require("./components/follow/Aside.vue"));
Vue.component("setting-aside", require("./components/setting/Aside.vue"));
Vue.component("go-top", require("./components/GoTop.vue"));
Vue.component("modal-contribute", require("./components/modals/ModalContribute.vue"));

const routes = [
  { path: "/comments", component: require("./components/notification/Comments.vue") },
  { path: "/chats", component: require("./components/notification/Chats.vue") },
  { path: "/chat/:id", component: require("./components/notification/Chat.vue") },
  { path: "/requests", component: require("./components/notification/Requests.vue") },
  { path: "/likes", component: require("./components/notification/Likes.vue") },
  { path: "/follows", component: require("./components/notification/Follows.vue") },
  { path: "/tips", component: require("./components/notification/Tips.vue") },
  { path: "/others", component: require("./components/notification/Others.vue") },

  { path: "/timeline", component: require("./components/follow/Timeline.vue") },
  { path: "/categories/:id", component: require("./components/follow/Category.vue") },
  { path: "/collections/:id", component: require("./components/follow/Collection.vue") },
  { path: "/users/:id", component: require("./components/follow/User.vue") },
  { path: "/recommend", component: require("./components/follow/Recommend.vue") },
  { path: "/submissions/:id", component: require("./components/notification/Submissions.vue") },
  { path: "/pending_submissions", component: require("./components/notification/PendingSubmissions.vue") },
  { path: "/base", component: require("./components/setting/Base.vue") },
  { path: "/profile", component: require("./components/setting/Profile.vue") },
  { path: "/reward", component: require("./components/setting/Reward.vue") }
];

const router = new VueRouter({
  routes
});

const app = new Vue({
  router
}).$mount("#app");
