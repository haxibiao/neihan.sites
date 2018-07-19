<template>
  <form  class="navbar-form form-box" id='searchForm'>
    <div class="form-group"> 
      <div class="search-wrapper">
        <input type="text" id="search-input" class="form-control" placeholder="搜索" v-model="inputValue" @keyup.enter="searchArticles">
        <span class="iconfont icon-sousuo search-btn" @click="searchArticles"></span>
      </div>
    </div>
  </form> 
</template>

<script>
export default {
  name: "CategorySearch",

  props: [],

  computed: {},

  methods: {
    searchArticles() {
      window.location.href = this.searchApi + this.inputValue;
    }
  },

  data() {
    return {
      searchApi: "/article?q=",
      inputValue: ""
    };
  }
};
</script>

<style lang="scss">
.form-box {
  display: inline-block;
}
.hot-search-wrp {
  margin-top: 9px;
  width: 220px;
  left: 0;
  top: 100%;
  visibility: hidden;
  opacity: 0;
  border-radius: 4px;
  z-index: 999;
  &,
  &::before {
    position: absolute;
    background-color: #fff;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
  }
  &::before {
    content: "";
    left: 27px;
    width: 10px;
    height: 10px;
    transform: rotate(45deg);
    top: -5px;
    z-index: -1;
  }
  .hot-search {
    border-bottom: 1px solid #f0f0f0;
  }
  .search-recent {
    padding: 10px 15px;
  }
}
</style>
