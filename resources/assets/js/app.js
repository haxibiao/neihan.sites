/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
window.$bus = new Vue();
window.$bus.state = {
	answer: {
		answerIds: []
	}
};
Vue.prototype.$http = window.axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//客人用户所能看到的js组件...
Vue.component("blank-content", require("./components/BlankContent.vue"));
Vue.component("single-list", require("./components/SingleList.vue"));

Vue.component("follow", require("./components/Follow.vue"));
Vue.component("favorite", require("./components/Favorite.vue"));
Vue.component("like", require("./components/Like.vue"));
// Vue.component('comment', require('./components/Comment.vue'));
Vue.component("comments", require("./components/Comments.vue"));
Vue.component("new-comment", require("./components/NewComment.vue"));
Vue.component("reply-comment", require("./components/ReplyComment.vue"));

Vue.component("go-top", require("./components/GoTop.vue"));
Vue.component("article-tool", require("./components/ArticleTool.vue"));

Vue.component("article-list", require("./components/ArticleList.vue"));
Vue.component("category-list", require("./components/CategoryList.vue"));
Vue.component("search-box", require("./components/search/SearchBox.vue"));
Vue.component("recently", require("./components/search/Recently.vue"));
Vue.component("hot-search", require("./components/search/Hot.vue"));

Vue.component("share", require("./components/Share.vue"));

//recommend component
Vue.component("recommend-category", require("./components/RecommendCategory.vue"));
Vue.component("recommend-authors", require("./components/aside/RecommendAuthors.vue"));


Vue.component("captcha", require("./components/logins/Captcha.vue"));
Vue.component("social-login", require("./components/logins/SocialLogin.vue"));
Vue.component("signs", require("./components/logins/Signs.vue"));
const app = new Vue({}).$mount("#app");
