<li class="list-group-item">
    <button type="button" class="btn btn-success media-button" data-toggle="modal" data-target=".bs-article-modal-lg">加入关联</button>
    <div class="modal fade bs-article-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">     
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">加入关联</h4>
              </div>                     
            <div class="col-md-12">
                @include('video.parts.add_article')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                {{-- <button type="button" class="btn btn-primary">确定</button> --}}
            </div>
        </div>
      </div>
    </div>
</li>