<template>
	<div class="row bottom10" id="my_videos">
    <div class="col-md-12 top10">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="视频所在文章标题..." v-model="title">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" @click="loadData">搜索</button>
        </span>
      </div>
    </div>
    <div v-for="item in list" class="col-xs-4 col-md-3 top5">
      <img :src="item.cover" alt="" class="img img-responsive" @click="select_video">
      <p class="strip_title">{{ item.title }}</p>
    </div>
  </div>
</template>

<script>
export default {

  name: 'MyVideoList',

  props: ['user_id'],

  created: function(){
    this.loadData();
  },

  mounted: function() { 

  },

  methods: {
    loadData: function() {
      var vm = this;
      var $api_url = '/api/user/' + this.user_id + '/videos';
      if(this.title) {
        $api_url = $api_url + '?title='+this.title;
      }
      this.$http.get($api_url).then(function(response) {
        vm.list = response.data.data;
      });
    },
  	loadMore: function() {
  		var vm = this;
      var $api_url = '/api/user/' + this.user_id + '/videos?page='+this.currentPage;
      if(this.title) {
        $api_url = $api_url + '&title='+this.title;
      }
  		this.$http.get($api_url)
  		.then(function(response) {
  			vm.list = vm.list.concat(response.data.data);
  			vm.currentPage ++;
  		});
  	},
    select_video: function(event) {
          //隐藏已选中
          $(event.target).parent().hide();

          var img_url = $(event.target).attr('src');

          //插入编辑器
          $('.editable').summernote('insertImage', img_url,function ($video) {
            $video.attr('data-video-cover', img_url);
            $video.addClass('img-responsive video');
          });

          //添加配图隐藏表单
          var video_url_el = $('input[name="video_img"]');
          video_url_el.val(img_url);
          $('<input type="hidden" name="videos[]" value="' + img_url + '">').insertBefore(video_url_el);

          //添加底部已选配图
          var selected_template = $('#article_video_template');
          if(selected_template.length){
            var article_video_el = selected_template.clone();
            article_video_el = article_video_el.insertBefore(selected_template);
            article_video_el.find('img').attr('src', img_url);
            article_video_el.find('input').val(img_url);
            article_video_el.removeClass('hide');
          }

          event.preventDefault();
          return false;
    }
  },

  data () {
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