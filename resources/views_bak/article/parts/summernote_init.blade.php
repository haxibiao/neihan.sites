<script type="text/javascript">
    $(function() {
        $('#keywords').tagsinput({
            width:'auto',
        });

        var editor = $('.editable').summernote({
            lang: 'zh-CN', // default: 'en-US',
            height: 500,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ["insert", ["link","hr"]],
                ['misc',['codeview', 'undo','redo','fullscreen']]
              ],
            focus:true
          });

        $('.editable').summernote('code',$('input[name="body"]').val());

        $('.editable').on('summernote.change', function(we, contents, $editable) {
          $('input[name="body"]').val(contents);
        });
    });
</script>