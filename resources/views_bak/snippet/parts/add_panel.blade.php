
@include('article.parts.upload_ui_templates')

<div class="panel panel-default　media-panel">
    <div class="panel-heading">
        <h3 class="panel-title">多媒体</h3>
    </div>
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item">
                <button type="button" class="btn btn-primary media-button" data-toggle="modal" data-target=".bs-pic-modal-lg">加入图片</button>
                <div class="modal fade bs-pic-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">     
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">加入图片</h4>
                          </div>                     
                        <div class="col-md-12">
                            @include('article.parts.image_add')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            {{-- <button type="button" class="btn btn-primary">确定</button> --}}
                        </div>
                    </div>
                  </div>
                </div>
            </li>
            <li class="list-group-item">
                <button type="button" class="btn btn-danger media-button" data-toggle="modal" data-target=".bs-video-modal-lg">加入视频</button>
                <div class="modal fade bs-video-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">     
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">加入视频</h4>
                          </div>                     
                        <div class="col-md-12">
                            @include('article.parts.video_add')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            {{-- <button type="button" class="btn btn-primary">确定</button> --}}
                        </div>
                    </div>
                  </div>
                </div>
            </li>
        </ul>
    </div>
</div>

@push('js')
    <script type="text/javascript">
        $(function() {
            $('.media-button').click(function() {
                console.log('save editor range ...');
                $('.editable').summernote('saveRange');
            });
        });
    </script>
@endpush