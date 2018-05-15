<template>
    <div class="modal fade modal-contribute">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
				      给该专题投稿
				      <a href="/write" class="new-note-btn">写篇新文章</a>
					</h4>
					<span class="notice">每篇文章有总共有5次投稿机会</span>
                    <div class="search-wrapper">
                        <input type="text" placeholder="搜索我的文章" id="search-input" @keyup.enter="fetchData" v-model="q">
                        <a class="search-btn iconfont icon-sousuo"></a>
                    </div>
                </div>
                <div class="modal-body">
                    <ul id="contribute-note-list">
                        <li v-for="article in articles">
                            <div>
                                <div class="note-name">{{ article.title }}</div>
                                <a :class="['action-btn',　getBtnClass(article)]" 
                                @click="submit(article)" v-if="article.submited_status != '已收录'">
                                	{{ article.submit_status }}
                              	</a>
                                <span class="status" v-if="article.submit_status">
                                	{{ article.submited_status }}
                              	</span>
                             </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    name: 'ModalContribute',

    props: ['categoryId'],

    created() {
        var _this = this;
        $bus.$on('showContribute', function(id){
            _this.cateId = id;
            _this.clear();
            _this.fetchData();
        });
    },

    mounted() {
        if(this.get_cate_id()) {
            this.fetchData();
        }
        //TODO: scroll to bottom, this.loadMore() ...
    },

    methods: {
        api() {
            var api = '/api/categories/check-category-' + this.get_cate_id();
            if(this.q) {
                api = api + '?q=' + this.q;
            }
            return window.tokenize(api);
        },
        get_cate_id() {
            return this.cateId ? this.cateId : this.categoryId;
        },
        clear() {
            this.articles = [];
        },
        loadMore() {
            if(this.page < this.lastPage) {
                this.page++;
                this.fetchData(this.categoryId);
            }
        },
        fetchData() {
            var _this = this;
            window.axios.get(this.api()).then(function(response){
                _this.articles = response.data.data;
                _this.lastPage = response.data.last_page;
            });
        },
    	submit(article) {
    		var api = window.tokenize('/api/categories/'+article.id+'/submit-category-'+this.get_cate_id());
            if(article.submit_status.indexOf("收录") !== -1 || article.submit_status.indexOf("移除") !== -1) {
                api = window.tokenize('/api/categories/'+article.id+'/add-category-'+this.get_cate_id());
            }
            window.axios.get(api).then(function(response){
                article.submit_status = response.data.submit_status;
                article.submited_status = response.data.submited_status;
            });
    	},
        getBtnClass(article) {
            if(article.submit_status.indexOf("投稿") !== -1 || article.submit_status.indexOf("收录") !== -1) {
                return 'btn-base btn-hollow btn-sm';
            } else {
                return 'btn-base btn-gray btn-sm'　;
            }
        }
    },

    data() {
        return {
            q: null,
            cateId: null,
            lastPage: null,
            page: 1,
        	articles:[]
        }
    }
}
</script>
<style lang="css" scoped>
</style>