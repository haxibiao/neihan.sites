<div class="form-group top30">
    <label>配图 (注意光标需要先保证在编辑器中，再来这里加入图片)</label>
    
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload_image" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>添加...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>传全部</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>删全部</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
</div>

@push('js')
    <!-- The main application script -->
    <script src="/js/upload/image.js"></script>

    <script type="text/javascript">

        window.article_image_uploaded = function(){
            $('.preview img').off('click');
            $('.preview img').each(function() {
                $(this).addClass('img-responsive');
            });
            $('.preview img').click(function(e) {
                $(this).parent().parent().parent().parent().hide();
                var is_video = $(this).parent().attr('title').indexOf('.mp4') !== -1;
                var img_url = $(this).attr('src');
                var video_id = 0;
                if(!is_video){
                    img_url = img_url.replace('.small.jpg','');
                    img_url = img_url.replace('.small.png','');
                    img_url = img_url.replace('.small.gif','');
                }　else {
                    video_id = $(this).parent().attr('title').replace('.mp4','');
                    video_id = video_id.replace('storage/video', '' ,video_id);
                }

                //插入图片到编辑器
                $('.editable').summernote('insertImage', img_url,function ($image) {
                    if(!is_video){
                        $image.attr('data-url-big', img_url);
                        $image.addClass('img-responsive');
                    } else {
                        $image.attr('data-video', video_id);
                        $image.addClass('img-thumbnail');
                        $image.addClass('video');
                    }
                });

                //默认最后选的图做主配图
                var image_url_el = $('input[name="image_url"]');
                image_url_el.val(img_url);

                if(!is_video){
                    $('<input type="hidden" name="images[]" value="' + img_url + '">').insertBefore(image_url_el);
                } else {
                    $('<input type="hidden" name="videos[]" value="' + video_id + '">').insertBefore(image_url_el);
                }

                //更新已选配图区,视频不算配图
                if(!is_video){
                    var article_image_el = $('#article_image_template').clone();
                    article_image_el = article_image_el.insertBefore($('#article_image_template'));
                    article_image_el.find('img').attr('src', img_url);
                    article_image_el.find('input').val(img_url);
                    article_image_el.removeClass('hide');
                }

                e.preventDefault();
                return false;
            });
        };

        window.setTimeout(function(){
            $(function(){
                window.article_image_uploaded();
            });
        }, 1000);
    </script>
@endpush