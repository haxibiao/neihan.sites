<template>
  <div class="categories visible-xs">
    <div class="top-title plate-title">
      <span>热门专题</span>
      <a class="category-change right" @click="changeCategory">
        <i class="iconfont icon-shuaxin" ref="fresh"></i>换一批
        </a>
    </div>
    <a :href="category.name_en" class="category-item"  v-for="category in categories">
      <span class="name">{{ category.name }}</span>
    </a>
  </div>
</template>

<script>
export default {
  name: "RecommendCategory",

  created() {
    this.fetchData();
  },

  methods: {
    changeCategory() {
      this.counter++;
      $(this.$refs.fresh).css("transform", `rotate(${360 * this.counter}deg)`);
      this.fetchData();
    },
    fetchData() {
      if(this.is_once){
        this.api = '/api/recommend-categories?index=true';
      }else{
        this.page++;
        if (this.lastPage) {
          if (this.page >= this.lastPage) {
            this.page = 1;
          }
        }
        this.api = "/api/recommend-categories?page=" + this.page;
      }
      var _this = this;
      window.axios.get(this.api).then(
        function(response) {
          if(_this.is_once){
            _this.is_once = false;
            _this.categories = response.data;
          }else{
            _this.categories = response.data.data;
            _this.lastPage = response.data.last_page;
          }
          
        },
        function(error) {
          _this.categories = _this.defaultCategories;
        }
      );
    }
  },

  data() {
    return {
      counter: 1,
      page: 0,
      lastPage: null,
      api:'',
      is_once:true,
      categories: [],
      defaultCategories: [
        { name: "美食食谱", name_en: "/meishicaipu" },
        { name: "合理膳食", name_en: "/helishanshi" },
        { name: "湖南美食", name_en: "/hunanmeishi" },
        { name: "各地美食", name_en: "/gedimeishi" },
        { name: "食疗养生", name_en: "/shiliaoyangsheng" },
        { name: "家常菜谱", name_en: "/jiachangcaicaipu" },
        { name: "懒人食谱", name_en: "/lanrenshipu" }
      ]
    };
  }
};
</script>

<style lang="scss">
.categories {
  .top-title {
    cursor: pointer;
    span {
      font-weight: 700;
    }
  }
  .category-item {
    display: inline-block;
    padding: 2px 9px;
    margin: 0 10px 10px 0;
    font-size: 14px;
    color: #d96a5f;
    border: 1px solid #d96a5f;
    border-radius: 4px;
    &:hover {
      background-color: rgba(#d96a5f, 0.3);
    }
  }
}
</style>
