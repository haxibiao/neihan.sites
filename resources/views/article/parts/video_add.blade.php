<div class="col-md-12 top10">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#video_library" aria-controls="video_library" role="tab" data-toggle="tab">您的视频库</a></li>
    <li role="presentation"><a href="#video_links" aria-controls="video_links" role="tab" data-toggle="tab">视频链接</a></li>
    <li role="presentation"><a href="#video_upload" aria-controls="video_upload" role="tab" data-toggle="tab">上传视频</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="video_library">
      <my-video-list user_id="{{ Auth::user()->id }}"></my-video-list>
    </div>
    <div role="tabpanel" class="tab-pane" id="video_links">
      
      <p>还在开发，以后可以直接插入主流视频网站视频详细也地址，到文章里</p>

    </div>
    <div role="tabpanel" class="tab-pane" id="video_upload">
      @include('article.parts.video_upload_ui')
    </div>
  </div>

</div>