<template>
	<div class="row" id="tag_images">
    <div class="col-md-12">
      <div class="input-group">
        <input type="text" class="form-control" @keyup.13="loadData" placeholder="图片所在文章标题..." v-model="tag_name">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" @click="loadData">搜索</button>
        </span>
      </div>
      <div class="tags">
        <a v-for="tag_name in tag_names" href="#" class="btn btn-link" @click="clickTag">{{ tag_name }}</a>
      </div>
    </div>
    <div class="images clearfix">
      <div v-for="item in list" class="col-xs-4 col-md-3">
        <img :src="item.image.path_small" alt="" class="img img-responsive" @click="select_image">
        <p class="strip_title">{{ item.image.title }}</p>
      </div>
    </div>
    <div class="more">
      <button class="btn btn-danger" @click="loadMore">加载更多</button>
    </div>
  </div>
</template>

<script>
export default {

  name: 'ImageList',

  props: ['tags'],

  created: function(){
    this.loadData();
    this.tag_names = this.tags.split(',');
  },

  mounted: function() { 

  },

  methods: {
    clickTag: function(e) {
      this.tag_name = $(e.target).text();
      this.loadData();
    },
    loadData: function() {
      var vm = this;
      var $api_url = '/api/tag/' + this.tag_name + '/images';
      this.$http.get($api_url).then(function(response) {
        vm.list = response.data.data;
      });
    },
  	loadMore: function() {
  		var vm = this;
      var $api_url = '/api/tag/' + this.tag_name + '/images?page='+this.currentPage;
  		this.$http.get($api_url)
  		.then(function(response) {
  			vm.list = vm.list.concat(response.data.data);
  			vm.currentPage ++;

        $('#my_images .images')[0].scrollTo(0, $('.images').scrollTop() + 500);
  		});
  	},
    select_image: function(event) {
            
          console.log('restoreRange editor range ...');
          $('.editable').summernote('restoreRange');
                
          //隐藏已选中
          $(event.target).parent().hide();

          var img_url = $(event.target).attr('src');
          img_url = img_url.replace('.small','');

          //插入编辑器
          $('.editable').summernote('insertImage', img_url,function ($image) {
            $image.attr('data-url-big', img_url);
            $image.addClass('img-responsive');
          });

          //添加配图隐藏表单
          var image_url_el = $('input[name="image_url"]');
          image_url_el.val(img_url);
          $('<input type="hidden" name="images[]" value="' + img_url + '">').insertBefore(image_url_el);

          //添加底部已选配图
          var selected_template = $('#article_image_template');
          if(selected_template.length){
            var article_image_el = selected_template.clone();
            article_image_el = article_image_el.insertBefore(selected_template);
            article_image_el.find('img').attr('src', img_url);
            article_image_el.find('input').val(img_url);
            article_image_el.removeClass('hide');
          }

          event.preventDefault();
          return false;
    }
  },

  data () {
    return {
      tag_name: '情侣头像',
      tag_names: [],
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