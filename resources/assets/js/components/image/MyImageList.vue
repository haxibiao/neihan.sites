<template>
  <div class="row bottom10" id="my_images">
    <div class="col-md-12 top10">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="图片所在文章标题..." v-model="title">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" @click="loadData">搜索</button>
        </span>
      </div>
    </div>
    <div v-for="image in list" class="col-xs-4 col-md-3 top5">
      <img :src="image.url_small" :alt="image.title" :big-url="image.url" class="img img-responsive" @click="select_image">
      <p class="strip_title">{{ image.title }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "MyImageList",

  props: ["user_id"],

  created: function() {
    this.loadData();
  },

  mounted: function() {},

  methods: {
    loadData: function() {
      var vm = this;
      var $api_url = "/api/user/" + this.user_id + "/images";
      if (this.title) {
        $api_url = $api_url + "?title=" + this.title;
      }
      this.$http.get($api_url).then(function(response) {
        vm.list = response.data.data;
      });
    },
    loadMore: function() {
      var vm = this;
      var $api_url = "/api/user/" + this.user_id + "/images?page=" + this.currentPage;
      if (this.title) {
        $api_url = $api_url + "&title=" + this.title;
      }
      this.$http.get($api_url).then(function(response) {
        vm.list = vm.list.concat(response.data.data);
        vm.currentPage++;
      });
    },
    select_image: function(event) {
      $(".editable").summernote("restoreRange");
      //隐藏已选中
      $(event.target)
        .parent()
        .hide();

      var img_url = $(event.target).attr("src");
      img_url = img_url.replace(".small", "");

      //插入编辑器
      $(".editable").summernote("insertImage", img_url, function($image) {
        $image.attr("data-url-big", img_url);
        $image.addClass("img-responsive");
      });

      //添加配图隐藏表单
      var image_url_el = $('input[name="image_url"]');
      image_url_el.val(img_url);
      $('<input type="hidden" name="images[]" value="' + img_url + '">').insertBefore(image_url_el);

      //添加底部已选配图
      var selected_template = $("#article_image_template");
      if (selected_template.length) {
        var article_image_el = selected_template.clone();
        article_image_el = article_image_el.insertBefore(selected_template);
        article_image_el.find("img").attr("src", img_url);
        article_image_el.find("input").val(img_url);
        article_image_el.removeClass("hide");
      }

      event.preventDefault();
      return false;
    }
  },

  data() {
    return {
      currentPage: 1,
      allLoaded: false,
      title: null,
      list: []
    };
  }
};
</script>

<style lang="css" scoped>
</style>
