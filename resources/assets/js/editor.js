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

//客人用户：　所能看到的js组件...
Vue.component("blank-content", require("./components/BlankContent.vue"));
Vue.component("single-list", require("./components/SingleList.vue"));

Vue.component("follow", require("./components/button/Follow.vue"));
Vue.component("favorite", require("./components/button/Favorite.vue"));
Vue.component("like", require("./components/button/Like.vue"));
// Vue.component('comment', require('./components/comment/Comment.vue'));
Vue.component("comments", require("./components/comment/Comments.vue"));
Vue.component("new-comment", require("./components/comment/NewComment.vue"));
Vue.component(
	"reply-comment",
	require("./components/comment/ReplyComment.vue")
);

Vue.component("side-tool", require("./components/SideTool.vue"));

Vue.component("article-list", require("./components/article/ArticleList.vue"));
Vue.component("video-list",require("./components/video/VideoList.vue"))
Vue.component(
	"category-list",
	require("./components/category/CategoryList.vue")
);
Vue.component("search-box", require("./components/search/SearchBox.vue"));
Vue.component("recently", require("./components/search/Recently.vue"));
Vue.component("hot-search", require("./components/search/Hot.vue"));

Vue.component("share", require("./components/Share.vue"));

Vue.component("captcha", require("./components/logins/Captcha.vue"));
Vue.component("social-login", require("./components/logins/SocialLogin.vue"));
Vue.component("signs", require("./components/logins/Signs.vue"));

//登录用户：　可见高级输入框组件...
Vue.component("tags-input", require("./components/TagsInput.vue"));
Vue.component("image-select", require("./components/image/ImageSelect.vue"));
Vue.component("user-select", require("./components/UserSelect.vue"));
Vue.component("modal-post", require("./components/modals/ModalPost.vue"));
Vue.component("add-videol", require("./components/video/AddVideol.vue"));
Vue.component(
	"category-select",
	require("./components/category/CategorySelect.vue")
);

Vue.component(
	"input-matching",
	require("./components/question/InputMatching.vue")
);
Vue.component(
	"modal-ask-question",
	require("./components/question/ModalAskQuestion.vue")
);
Vue.component("delete-button", require("./components/button/DeleteButton.vue"));

Vue.component(
	"recommend-category",
	require("./components/category/RecommendCategory.vue")
);
Vue.component(
	"recommend-authors",
	require("./components/aside/RecommendAuthors.vue")
);
Vue.component(
	"modal-contribute",
	require("./components/modals/ModalContribute.vue")
);
Vue.component(
	"modal-add-category",
	require("./components/modals/ModalAddCategory.vue")
);
Vue.component(
	"modal-category-contribute",
	require("./components/modals/ModalCategoryContribute.vue")
);
Vue.component("modal-delete", require("./components/modals/ModalDelete.vue"));
Vue.component("modal-admire", require("./components/modals/ModalAdmire.vue"));
Vue.component(
	"modal-withdraw",
	require("./components/modals/ModalWithdraw.vue")
);
Vue.component("modal-to-up", require("./components/modals/ModalToUp.vue"));
Vue.component(
	"modal-like-user",
	require("./components/modals/ModalLikeUsers.vue")
);
Vue.component(
	"modal-share-wx",
	require("./components/modals/ModalShareWX.vue")
);
Vue.component("setting-aside", require("./components/setting/Aside.vue"));

Vue.component("answer-tool", require("./components/question/AnswerTool.vue"));
Vue.component(
	"question-bottom",
	require("./components/question/QuestionBottom.vue")
);

//编辑用户：　能见到编辑编辑器，专题管理等
Vue.component("editor", require("./components/Editor.vue"));
//编辑器需要的模态框
Vue.component("my-image-list", require("./components/image/MyImageList.vue"));
Vue.component("my-video-list", require("./components/video/MyVideoList.vue"));
Vue.component(
	"single-list-create",
	require("./components/SingleListCreate.vue")
);
Vue.component(
	"single-list-select",
	require("./components/SingleListSelect.vue")
);
//免费图片素材
Vue.component("modal-images", require("./components/modals/ModalImages.vue"));
Vue.component("image-list", require("./components/image/ImageList.vue"));

//编辑用户：　能见到两个简单报表
Vue.component("bar", require("./components/Bar.vue"));
Vue.component("line-chart", require("./components/Line.vue"));
const app = new Vue({}).$mount("#app");
