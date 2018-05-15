import collectionApi from '../api/collection'
import * as types from './mutation-types'
import router from '../router'

// actions

export function destroyArticle({commit, dispatch, state}, articleId) {
	collectionApi.destroyArticle(articleId);
	commit(types.DESTROY_ARTICLE, articleId);
	dispatch('goDefaultTrash');
}

export function restoreArticle({commit, dispatch, state}, articleId) {
	collectionApi.restoreArticle(articleId, () => {
		//恢复文章后，刷新文集列表数据
		collectionApi.getCollections(collections => {
		    commit(types.GOT_COLLECTIONS, { collections })
		})
	});
	commit(types.RESTORE_ARTICLE, articleId);
	dispatch('goDefaultTrash');
}

export function getTrash({commit, dispatch, state}) {
	collectionApi.getArticleTrash( articles => {
		commit(types.GOT_TRASH, articles);
		dispatch('goDefaultTrash');
	});
}

export function goDefaultTrash({ state }) {
	console.log(state.trash);
	if(state.trash.length) {
  		var recycleId = state.trash[0].id;
  		router.replace(`/recycle/${recycleId}`);
  	}else {
  		router.replace('/recycle');
  	}
}

export function addArticle({ commit, dispatch, state }){
	collectionApi.addArticle(state.collectionId, article => {
		console.info('action');
		console.log(article);
	    commit(types.ADD_ARTICLE, article)
		dispatch('goArticle', { collectionId: state.collectionId, articleId: article.id });
	})
}

export function saveArticle({ commit, dispatch, state }){
	let { currentArticle } = state;
	let article = { id: currentArticle.id, title: currentArticle.title, body: currentArticle.body };
	collectionApi.saveArticle(article, article => {
	    commit(types.SAVED_ARTICLE, article)
	})
}

export function publishArticle({ commit, dispatch, state }){
	let { currentArticle } = state;
	let article = { id: currentArticle.id, title: currentArticle.title, body: currentArticle.body, status: 1 };
	collectionApi.saveArticle(article, article => {
	    commit(types.SAVED_ARTICLE, article)
	})
}

export function unpublishArticle({ commit, dispatch, state }){
	let { currentArticle } = state;
	let article = { id: currentArticle.id, title: currentArticle.title, body: currentArticle.body, status: 0 };
	collectionApi.saveArticle(article, article => {
	    commit(types.SAVED_ARTICLE, article)
	})
}

export function moveArticle({ commit, dispatch, state }, collectionId){
	let { currentArticle } = state;
	let fromCollectionId = state.collectionId;
	let article = { id: currentArticle.id, title: currentArticle.title, body: currentArticle.body, collection_id: collectionId };
	let toCollectionId = collectionId;
	collectionApi.moveArticle( { article, collectionId }, article => {
	    commit(types.MOVED_ARTICLE, { article, fromCollectionId, toCollectionId });
	    dispatch('goCollection', { collectionId: fromCollectionId });
	})
}

export function removeArticle({ commit, dispatch, state }){
	collectionApi.removeArticle(state.currentArticle.id);
	commit(types.REMOVE_ARTICLE)

	//去当前文集下第一篇文章
	dispatch('goCollection', { collectionId: state.currentCollection.id });
}


export function updateCurrentPathInfo({ commit }, { collectionId, articleId }){
	commit(types.GOT_CURRENT_PATH_INFO, { collectionId, articleId });
}

export function getCollections({ state, commit, dispatch }){
	collectionApi.getCollections(collections => {
	    commit(types.GOT_COLLECTIONS, { collections })
	    let { articleId, collectionId } = state;
	    console.log(state);
	    if(articleId) {
	    	dispatch('goArticle', { collectionId, articleId });
	    	console.log('goArticle');
	    } else if(collectionId) {
	    	dispatch('goCollection', { collectionId });
	    	console.log('goCollection');
	    } else {
	    	dispatch('goDefaultCollection');
	    }
	})
}

export function addCollection({ commit, dispatch }, { name }){
	collectionApi.addCollection(name, collection => {
	    commit(types.ADD_COLLECTION, { collection })
		dispatch('goDefaultCollection');
	})
}

export function goDefaultCollection({ dispatch, state }){
	console.log('goDefaultCollection');
	//默认选第一个文集
	var firstCollection = state.collections[0];
	console.log(firstCollection);
	dispatch('goCollection', { collectionId: firstCollection.id });
}

// export function goDefaultArticle({ dispatch, state },{ Collection,collectionId }){
// 	var Collection = state.collections.find(ele=>{
// 		return ele.id == collectionId
// 	});
// 	//如果文集下有文章，打开第一个
// 	if(Collection.articles.length) {
// 		var firstArticle = firstCollection.articles[0];
// 		dispatch('goArticle', { collectionId : collectionId, articleId: firstArticle.id });
// 	} 
// }

export function goCollection({ state, dispatch }, { collectionId }) {
	let collection = state.collections.find( item => item.id == collectionId );
	//如果文集下有文章，打开第一个
	console.log(collection.articles);
	if(collection.articles.length) {
		var firstArticle = collection.articles[0];
		console.log(firstArticle);
		dispatch('goArticle', { collectionId : collection.id, articleId: firstArticle.id });
	}  else {
		state.currentArticle = null; //no article 
		router.push(`/notebooks/${collectionId}`);
	}
}

export function goArticle({ state, commit }, { collectionId, articleId }) {
	console.log(`/notebooks/${collectionId}/notes/${articleId}`);
	router.push(`/notebooks/${collectionId}/notes/${articleId}`);

	//弥补直接刷新router path, watch 没工作
	commit(types.GOT_CURRENT_PATH_INFO, { collectionId, articleId });
}

export function removeCollection({ commit, dispatch }, collectionId){
	collectionApi.removeCollection(collectionId, collection => {
	  commit(types.REMOVE_COLLECTION, { collectionId })
	  dispatch('goDefaultCollection')
	})
}

export function renameCollection({ commit, state}, { name }){
	collectionApi.renameCollection({ id: state.currentCollection.id, name: name }, collection => {
	  // commit(types.RENAME_COLLECTION, { collection })
	  $('.modification-name').modal('hide');
	})
}