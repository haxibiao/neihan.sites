
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue' 
import VueRouter from 'vue-router'
import store from './store'
import router from './router'


window.$bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('write', require('./components/write/Write.vue'));
Vue.component('note-books', require('./components/write/Notebooks.vue'));
Vue.component('notes', require('./components/write/Notes.vue'));
Vue.component('recycle', require('./components/write/Recycle.vue'));

Vue.component('editor', require('./components/Editor.vue'));
Vue.component('scroll-top', require('./components/write/ScrollTop.vue'));

// 文集重命名
Vue.component('modification-name', require('./components/write/modificationName.vue'));
// 常见问题
Vue.component('frequently-asked-questions', require('./components/write/FAQ.vue'));
// 删除文集
Vue.component('delete-notebook', require('./components/write/deleteNotebook.vue'));
// 删除文章
Vue.component('delete-note', require('./components/write/deleteNote.vue'));
// 彻底删除
Vue.component('thorough-delete', require('./components/write/thoroughDelete.vue'));

//editor needed modals..
Vue.component('image-list-modal', require('./modals/ImageListModal.vue'));
Vue.component('image-list', require('./components/ImageList.vue'));


const app = new Vue({
    router,
    store
}).$mount('#app');
