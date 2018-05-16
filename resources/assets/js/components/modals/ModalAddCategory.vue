<template>
    <div class="modal fade modal-add-category">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                  <h4 class="modal-title">
                    收入到我管理的专题
                    <a href="/category/create" class="new-note-btn">新建专题</a>
                  </h4>
                  <div class="search-wrapper">
                      <input type="text" placeholder="搜索我管理的专题" id="search-input" v-model="q" @keyup.enter="search">
                      <a class="search-btn iconfont icon-sousuo"></a>
                  </div>
              </div>
              <div class="modal-body">
                  <ul id="contribute-note-list">
                      <li v-for="category in categoryList">
                        <a :href="'/'+category.name_en" class="avatar-category">
                          <img :src="category.logo">
                        </a>
                        <div class="collection-info">
                          <a :href="'/'+category.name_en" class="collection-name">{{ category.name }}</a>
                          <div class="meta">{{ category.user.name }} 編</div>
                          <span class="status has-add" v-if="category.status">
                              {{ category.submited_status }}
                          </span>
                          <a :class="['action-btn',getBtnClass(category.submit_status)]" 
                           @click="add(category)">
                            {{ category.submit_status }}
                          </a>
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
  name: "ModalAddCategory",

  props: ["articleId"],

  mounted() {
    this.fetchData();

    //TODO:: bind ScrollToBottom, load more ..
  },

  methods: {
    apiUrl() {
      var api = "/api/categories/admin-check-article-" + this.articleId;
      if (this.q) {
        api = api + "?q=" + this.q;
      }
      return window.tokenize(api);
    },
    getBtnClass(status) {
      switch (status) {
        case "收录":
          return "btn-base btn-hollow btn-sm";
      }
      return "btn-base btn-hollow theme-tag btn-sm";
    },
    search() {
      this.page = 1;
      this.fetchData();
    },
    add(category) {
      var api = window.tokenize("/api/categories/" + this.articleId + "/add-category-" + category.id);
      window.axios.get(api).then(function(response) {
        category.submit_status = response.data.submit_status;
        category.submited_status = response.data.submited_status;
      });
    },
    fetchData() {
      var _this = this;
      window.axios.get(this.apiUrl()).then(function(response) {
        if (_this.page == 1) {
          _this.categoryList = response.data.data;
        } else {
          _this.categoryList = _this.categoryList.concat(response.data.data);
        }
      });
    }
  },

  data() {
    return {
      q: null,
      page: 1,
      categoryList: [
        // {'id':1,'user':{'name':'眸若止水'},'logo':'/images/dissertation_04.jpg','name':'front','status':''},
        // {'id':2,'user':{'name':'眸若止水'},'logo':'/images/dissertation_05.jpg','name':'铲屎官的自我修养','status':''},
        // {'id':3,'user':{'name':'眸若止水'},'logo':'/images/dissertation_06.jpg','name':'程序员的自我修养','status':''}
      ]
    };
  }
};
</script>

<style lang="scss" scoped>
.modal.modal-add-category {
  .modal-dialog {
    width: 580px;
  }
  .modal-dialog {
    @media screen and(max-width:768px) {
      width: 90%;
      max-width: 500;
      min-width: 320px;
    }
  }
}
</style>
