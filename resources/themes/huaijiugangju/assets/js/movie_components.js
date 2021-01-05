import Vue from 'vue';

// movie
Vue.component('video-player', require('./components/movie/VideoPlayer.vue').default);
Vue.component('movie-player', require('./components/movie/MoviePlayer.vue').default);
// comment
Vue.component('comment-module', require('./components/comment/CommentModule.vue').default);
Vue.component('comment-send', require('./components/comment/CommentSend.vue').default);
Vue.component('comment-item', require('./components/comment/CommentItem.vue').default);


const app = new Vue({
    el: '#app',
});
