/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
import router from "./router";

window.$bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component("write", require("./components/write/Write.vue").default);
Vue.component("note-books", require("./components/write/Notebooks.vue").default);
Vue.component("notes", require("./components/write/Notes.vue").default);
Vue.component("recycle", require("./components/write/Recycle.vue").default);

Vue.component("editor", require("./components/Editor.vue").default);
Vue.component("scroll-top", require("./components/write/ScrollTop.vue").default);
Vue.component("published", require("./components/write/Published.vue").default);
Vue.component("modal-tips", require("./components/modals/ModalTips.vue").default);

//免费图片素材
Vue.component("modal-images", require("./components/modals/ModalImages.vue").default);
Vue.component("image-list", require("./components/image/ImageList.vue").default);

// 文集重命名
Vue.component("modification-name", require("./components/write/modificationName.vue").default);
// 常见问题
Vue.component("frequently-asked-questions", require("./components/write/FAQ.vue").default);
// 删除文集
Vue.component("delete-notebook", require("./components/write/deleteNotebook.vue").default);

// 删除文章
Vue.component("delete-note", require("./components/write/deleteNote.vue").default);
// 彻底删除
Vue.component("thorough-delete", require("./components/write/thoroughDelete.vue").default);

const app = new Vue({
	router,
	store
}).$mount("#app");
