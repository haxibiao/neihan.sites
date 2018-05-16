<template>
    <div class="modal fade modal-category-contribute">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                  <h4 class="modal-title">
                    投稿
                  </h4>
                  <div class="search-wrapper">
                      <input type="text" placeholder="搜索我管理的专题" id="search-input" v-model="q" @keyup.enter="search">
                      <a class="search-btn iconfont icon-sousuo"></a>
                  </div>
              </div>
              <div class="modal-body">
                  <ul id="contribute-note-list">
                      <div class="title">
                        我管理的专题
                        <a href="/category/create" target="_blank" class="new-note-btn"><span>新建专题</span></a>
                      </div>
                      <li v-for="category in categoryList">
                        <a :href="'/'+category.name_en" class="avatar-category">
                          <img :src="category.logo">
                        </a>
                        <div class="collection-info">
                          <a :href="'/'+category.name_en" class="collection-name">{{ category.name }}</a>
                          <div class="meta">{{ category.count }}篇文章·{{ category.follow }}人关注</div>
                           <a :class="['action-btn',getBtnClass(category.submit_status)]" 
                           @click="add(category)">
                            {{ category.submit_status }}
                          </a>
                        </div>
                      </li>
                  </ul>
                  <ul id="contribute-note-list">
                      <div class="title">
                        推荐专题
                      </div>
                      <li v-for="category in recommendCategoryList">
                        <a :href="'/'+category.name_en" class="avatar-category">
                          <img :src="category.logo">
                        </a>
                        <div class="collection-info">
                          <a :href="'/'+category.name_en" class="collection-name">{{ category.name }}</a>
                          <div class="meta">{{ category.count }}篇文章·{{ category.follow }}人关注</div>
                          <a :class="['action-btn',getBtn2Class(category.submit_status)]" 
                           @click="submit(category)"  v-if="category.submited_status != '已收录'">
                            {{ category.submit_status }}
                          </a>
                          <a :class="['action-btn',getBtn2Class(category.submit_status)]" 
                          v-else>{{ category.submited_status }}</a>
                        </div>
                      </li>
                      <a class="btn-base btn-more" href="javascript:;" @click="fetchMore">{{ page2 >= lastPage ? '已经到底了':'正在加载更多' }}...</a>
                  </ul>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  name: "ModalCategoryContribute",

  props: ["articleId"],

  mounted() {
    this.fetchData();
    this.listenScrollBottom();
    //TODO:: bind ScrollToBottom, load more ..
  },

  methods: {
    listenScrollBottom() {
      var _this = this;
      $(window).on("scroll", function() {
        var aheadMount = 5; //sometimes need ahead a little ...
        var is_scroll_to_bottom = $(this).scrollTop() >= $("body").height() - $(window).height() - aheadMount;
        if (is_scroll_to_bottom) {
          _this.fetchMore();
        }
      });
    },

    fetchMore() {
      ++this.page2;
      if (this.lastPage > 0 && this.page2 > this.lastPage) {
        //TODO: ui 提示  ...
        return;
      }
      this.fetchData();
    },
    apiUrl() {
      var api = "/api/categories/admin-check-article-" + this.articleId;
      if (this.q) {
        api = api + "?q=" + this.q;
      }
      return window.tokenize(api);
    },
    apiUrl2() {
      var page2 = this.page2;
      var api = "/api/categories/recommend-check-article-" + this.articleId + "?page=" + page2;
      return window.tokenize(api);
    },
    getBtnClass(status) {
      switch (status) {
        case "收录":
          return "btn-base btn-hollow btn-sm";
      }
      return "btn-base btn-hollow theme-tag btn-sm";
    },
    getBtn2Class(status) {
      switch (status) {
        case "投稿":
          return "btn-base btn-hollow btn-sm";
        case "再次投稿":
          return "btn-base btn-hollow btn-sm";
      }
      return "btn-base btn-hollow theme-tag btn-sm";
    },
    search() {
      this.page = 1;
      this.fetchManage();
    },
    add(category) {
      var api = window.tokenize("/api/categories/" + this.articleId + "/add-category-" + category.id);
      window.axios.get(api).then(function(response) {
        category.submit_status = response.data.submit_status;
        category.submited_status = response.data.submited_status;
      });
    },
    submit(category) {
      var api = window.tokenize("/api/categories/" + this.articleId + "/submit-category-" + category.id);
      window.axios.get(api).then(function(response) {
        category.submit_status = response.data.submit_status;
        category.submited_status = response.data.submited_status;
      });
    },
    fetchManage() {
      var _this = this;
      window.axios.get(this.apiUrl()).then(function(response) {
        if (_this.page == 1) {
          _this.categoryList = response.data.data;
        } else {
          _this.categoryList = _this.categoryList.concat(response.data.data);
          _this.page = response.data.currentPage;
          _this.page_total = response.data.lastPage;
        }
      });
    },
    fetchData() {
      var _this = this;
      this.fetchManage();
      window.axios.get(this.apiUrl2()).then(function(response) {
        _this.recommendCategoryList = _this.recommendCategoryList.concat(response.data.data);
        // _this.page2 = response.data.currentPage;
        _this.page2_total = response.data.lastPage;
        _this.lastPage = response.data.last_page;
      });
    }
  },

  data() {
    return {
      q: null,
      lastPage: -1,
      page: 1,
      page_total: 1,
      page2: 1,
      page2_total: 1,
      categoryList: [],
      recommendCategoryList: []
    };
  }
};
</script>

<style lang="scss" scoped>
.modal.modal-category-contribute {
  .modal-dialog {
    max-width: 960px;
    .search-wrapper {
      position: absolute;
      top: 15px;
      right: 80px;
      margin: 0 !important;
    }
    @media screen and (max-width: 1000px) {
      max-width: 720px;
      li {
        width: 50%;
      }
    }
    @media screen and (max-width: 768px) {
      max-width: 450px;
      li {
        width: 100%;
      }
    }
    .modal-body {
      height: 500px;
      #contribute-note-list {
        li {
          padding: 20px 60px 20px 25px;
        }
      }
    }
  }
  li {
    width: 33.3333%;
    display: inline-block;
    border-bottom: none !important;
  }
}
</style>
