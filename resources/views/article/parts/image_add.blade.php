<div class="col-md-12 top10">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#image_upload" aria-controls="image_upload" role="tab" data-toggle="tab">上传图片</a></li>
    <li role="presentation"><a href="#image_mine" aria-controls="image_mine" role="tab" data-toggle="tab">您的图片库</a></li>
    <li role="presentation"><a href="#image_all" aria-controls="image_all" role="tab" data-toggle="tab">公共图片库</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="image_upload">
      @include('article.parts.image_upload_ui')
    </div>
    <div role="tabpanel" class="tab-pane" id="image_mine">
      <my-image-list user_id="{{ Auth::user()->id }}"></my-image-list>
    </div>
    <div role="tabpanel" class="tab-pane" id="image_all">
      <image-list tags="{{ $data['latest_tags'] }}"></image-list>
    </div>
  </div>

</div>