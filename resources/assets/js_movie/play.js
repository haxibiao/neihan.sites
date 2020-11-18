import Vue from 'vue';

Vue.component('video-player', require('./components/VideoPlayer.vue').default);
// movie
Vue.component('movie-player', require('./components/MoviePlayer.vue').default);
// comment
Vue.component('comment-module', require('./components/comment/CommentModule.vue').default);
Vue.component('comment-send', require('./components/comment/CommentSend.vue').default);
Vue.component('comment-item', require('./components/comment/CommentItem.vue').default);

const app = new Vue({
    el: '#app',
});