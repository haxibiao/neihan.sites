<form action="/image" method="POST" role="form" enctype="multipart/form-data" id='image_form'>
<div class="form-group">
    <label>配图</label>
    <div>
      <input type="file" name="image[]">
      <button type="button" class="btn btn-default" id="add_image">+</button>
      <button type="button" class="btn btn-danger" id="upload_image">上传</button>
    </div>
    <div class="row" id="preview_images">

    </div>
</div>
</form>

@push('scripts')
	<script type="text/javascript">
		$(function() {
			$('#add_image').click(function() {
		        $('<input type="file" name="image[]">').insertBefore($(this));
		    });

		    $('#upload_image').click(function() {
		        var form = $('#image_form')[0];
		        var data = new FormData(form);
		        console.log(data);
		        console.log(form);
		        $.ajax({
		          url: '/image',
		          type: 'POST',
		          cache: false,
		          data: data,
		          processData: false,
		          contentType: false
		        }).done(function(res) {
		          $('#preview_images').innerHTML = '';
		          for(var i in res.files) {
		            var img_path = res.files[i].url;
		            $('#preview_images').append('<img src="'+img_path+'" class="col-sm-3 img img-responsive" onclick="select_image(this)"/>')
		          }
		        }).fail(function(res) {
		            
		        });
		    });

			window.select_image = function(img) {
				var img_url = $(img).attr('src');
				$('.editable').summernote('insertImage', img_url,function ($image) {
				  $image.attr('data-url-big', img_url.replace('small.jpg',''));
				});
			}
		});
	</script>
@endpush