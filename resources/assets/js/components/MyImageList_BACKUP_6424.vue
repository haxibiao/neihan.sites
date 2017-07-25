<template>
<<<<<<< HEAD
	<div class="row bottom10" id="my_images">
    <div v-for="item in list" class="col-xs-4 col-md-3 top5">
      <img :src="item.path_small" alt="" class="img img-responsive">
    </div>
  </div>
=======
	<mt-loadmore :top-method="loadTop" :bottom-method="loadBottom" :bottom-all-loaded="allLoaded" ref="loadmore">
	  <ul>
	    <li v-for="item in list">{{ item.path }}</li>
	  </ul>
	</mt-loadmore>
>>>>>>> 2d3ac55f3b9c064ab04a0a001553c6da0577d51c
</template>

<script>
export default {

  name: 'MyImageList',

  props: ['user_id'],

  mounted: function() {
  	this.loadMore();
<<<<<<< HEAD

    window.setTimeout(this.bindImgClick, 2000);
=======
>>>>>>> 2d3ac55f3b9c064ab04a0a001553c6da0577d51c
  },

  methods: {
  	loadMore: function() {
  		var vm = this;
  		this.$http.get('/api/user/' + this.user_id + '/images?page='+this.currentPage)
  		.then(function(response) {
  			vm.list = vm.list.concat(response.data.data);
  			vm.currentPage ++;
<<<<<<< HEAD
        vm.bindImgClick();
  		});      
  	},
    bindImgClick: function() { 
      $('#my_images img').click(function(e) {
          $(this).parent().hide();
          var img_url = $(this).attr('src');
          img_url = img_url.replace('.small.jpg','');
          img_url = img_url.replace('.small.png','');
          img_url = img_url.replace('.small.gif','');

          $('.editable').summernote('insertImage', img_url,function ($image) {
            $image.attr('data-url-big', img_url);
            $image.addClass('img-responsive');
          });

          var image_url_el = $('input[name="image_url"]');
          image_url_el.val(img_url);
          $('<input type="hidden" name="images[]" value="' + img_url + '">').insertBefore(image_url_el);

          $article_image_el = $('#article_image_template').clone();
          $article_image_el = $article_image_el.insertBefore($('#article_image_template'));
          $article_image_el.find('img').attr('src', img_url);
          $article_image_el.find('input').val(img_url);
          $article_image_el.removeClass('hide');

          e.preventDefault();
          return false;
      });
    }
=======
  		});
  	},
  	loadTop() {
	  //...// 加载更多数据
	  this.$refs.loadmore.onTopLoaded();
	},
	loadBottom() {
	  //...// 加载更多数据
	  this.allLoaded = true;// 若数据已全部获取完毕
	  this.$refs.loadmore.onBottomLoaded();
	}
>>>>>>> 2d3ac55f3b9c064ab04a0a001553c6da0577d51c
  },

  data () {
    return {
    	currentPage: 1,
    	allLoaded: false,
    	list: []
    };
  }
};
</script>

<style lang="css" scoped>
</style>