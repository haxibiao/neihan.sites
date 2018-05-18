export default {
  getCollections (callback) {
    window.axios.get(window.tokenize('/api/collections')).then(function(response){
    	callback(response.data);
    });
  },
  addCollection(name, callback) {
    window.axios.post(window.tokenize('/api/collection/create'), { name }).then(function(response){
      callback(response.data);
    });
  },
  removeCollection(collectionId, callback) {
  	window.axios.delete(window.tokenize('/api/collection/'+collectionId)).then(function(response){
  		callback(collectionId);
  	}); 
  },
  renameCollection(collection, callback) {
  	window.axios.post(window.tokenize('/api/collection/'+collection.id), { name: collection.name }).then(function(response){
  		callback(response.data);
  	});
  },
  addArticle(collectionId, callback) {
    var newArticle = { title:'无标题文章', body:'' };
    window.axios.post(window.tokenize('/api/collection/'+collectionId+'/article/create'), newArticle).then(function(response){
        console.info('api');
        console.log(response.data);
        callback(response.data);  
      });
  },
  saveArticle(article, callback) {
    var update = { title:article.title, body:article.body, status: article.status };
    window.axios.put(window.tokenize('/api/article/'+article.id+'/update'), update).then(function(response){
        callback(response.data);  
      });
  },
  moveArticle({ article, collectionId }, callback) {
    window.axios.get(window.tokenize('/api/article-'+article.id+'-move-collection-'+collectionId)).then(function(response){
        callback(response.data);  
      });
  },
  removeArticle(articleId) {
    window.axios.delete(window.tokenize('/api/article/'+articleId));
  },
  destroyArticle(articleId) {
    window.axios.get(window.tokenize('/api/article/'+articleId+'/destroy'));
  },
  restoreArticle(articleId, callback) {
    window.axios.get(window.tokenize('/api/article/'+articleId+'/restore')).then(function(response){
      callback();
    });
  },
  getArticleTrash(callback) {
    window.axios.get(window.tokenize('/api/article-trash')).then(function(response){
      callback(response.data);
    });
  }
}