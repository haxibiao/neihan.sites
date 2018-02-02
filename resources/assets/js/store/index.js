import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'
import mutations from './mutations'
import getters from './getters'

Vue.use(Vuex)

// const debug = process.env.NODE_ENV !== 'production'

// initial state
const state = {
  collections: [],
  currentCollection : {},
  collectionId: null,
  currentArticle: {},
  articleId: null,
  previewArticle: {},
  trash: [],
}

export default new Vuex.Store({
  state,
  actions,
  mutations,
  getters,
  // strict: debug,
})