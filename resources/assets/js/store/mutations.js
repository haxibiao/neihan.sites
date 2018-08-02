import * as types from "./mutation-types";

// mutations
export default {
  [types.GOT_TRASH](state, articles) {
    state.trash = articles;
  },

  [types.DESTROY_ARTICLE](state, articleId) {
    state.trash = state.trash.filter(item => item.id != articleId);
  },

  [types.RESTORE_ARTICLE](state, articleId) {
    state.trash = state.trash.filter(item => item.id != articleId);
  },

  [types.ADD_ARTICLE](state, article) {
    state.currentCollection.articles.unshift(article);
  },

  [types.SAVED_PREV_ARTICLE](state, article) {
    var { previousArticle } = state;
    if (previousArticle && previousArticle.id) {
      previousArticle.status = article.status;
      previousArticle.saved = true;
    }
  },

  [types.SAVED_ARTICLE](state, article) {
    //目前只有发布文章后，状态字段status会变化
    var { currentArticle } = state;
    if (currentArticle && currentArticle.id) {
      currentArticle.status = article.status;
      currentArticle.saved = true;
    }
  },

  [types.PUBLISH](state) {
    state.published = true;
  },

  [types.PUBLISHED_TOGGLE](state) {
    state.published = false;
  },

  [types.PUBLISHED_TOGGLE](state) {
    state.published = !state.published;
  },

  [types.MOVED_ARTICLE](state, { article, fromCollectionId, toCollectionId }) {
    state.currentCollection.articles = state.currentCollection.articles.filter(item => item.id != article.id);
    let toCollection = state.collections.find(item => item.id == toCollectionId);
    toCollection.articles.unshift(article);
  },

  [types.REMOVE_ARTICLE](state) {
    state.currentCollection.articles = state.currentCollection.articles.filter(item => item.id != state.currentArticle.id);
  },

  [types.GOT_COLLECTIONS](state, { collections }) {
    state.collections = collections;
  },

  [types.ADD_COLLECTION](state, { collection }) {
    state.collections.unshift(collection);
  },

  [types.REMOVE_COLLECTION](state, { collectionId }) {
    state.collections = state.collections.filter(item => item.id != collectionId);
  },

  [types.RENAME_COLLECTION](state, name) {
    state.currentCollection.name = name;
  },

  [types.GOT_CURRENT_PATH_INFO](state, { collectionId, articleId }) {
    state.currentCollection = state.collections.find(item => item.id == collectionId);
    if (state.currentArticle) {
      state.previousArticle = state.currentArticle;
    }
    if (articleId && state.currentCollection && state.currentCollection.articles) {
      state.currentArticle = state.currentCollection.articles.find(item => item.id == articleId);
    }
  }
};
