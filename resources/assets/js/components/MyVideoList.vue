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
      <img :src="item.cover" :alt="item.title" :data-video-id="item.id" class="img img-responsive" @click="select_video">
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
    select_video: function(event) {
            
          console.log('restoreRange editor range ...');
          $('.editable').summernote('restoreRange');
          
          //隐藏已选中
          $(event.target).parent().hide();
          var video_id = $(event.target).attr('data-video-id');
          var img_url = $(event.target).attr('src');

          //插入编辑器
          $('.editable').summernote('insertImage', img_url,function ($video) {
            $video.attr('data-video', video_id);
            $video.addClass('img-responsive video');
          });

          //添加配图隐藏表单
          var hidden_image_url = $('input[name="image_url"]');
          hidden_image_url.val(img_url);
          $('<input type="hidden" name="videos[]" value="' + video_id + '">').insertBefore(hidden_image_url);

          //视频无需添加已选配图了
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