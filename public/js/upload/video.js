$(function() {
    'use strict';
    var url = "/video";
    $('#fileupload_video').fileupload({
        url: url,
        method: 'POST',
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(mp4)$/i,
        maxFileSize: 100000000, // 100 MB
        disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        previewMaxWidth: 300,
        previewMaxHeight: 200,
        previewCrop: true,
    }).on('fileuploadadd', function(e, data) {
        data.context = $('<div class="col-md-3 videopreview" />').appendTo('#files');
        $.each(data.files, function(index, file) {
            var node = $('<p/>');
            if (!index) {
                node.append('<br>')
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function(e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node.prepend('<br>').prepend(file.preview);
        }
        if (file.error) {
            node.append('<br>').append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button').text('Upload').prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function(e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css('width', progress + '%');
    }).on('fileuploaddone', function(e, data) {
        $.each(data.result.files, function(index, file) {
            if (file.url) {
                var link = $('<a>').attr('target', '_blank').prop('href', file.url);
                $(data.context.children()[index]).wrap(link).append($('<span/>').text(file.name));
                $("#filesHidden").append('<input type="hidden" name="images[]" value="' + file.name + '">');
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index]).append('<br>').append(error);
            }
        });
    }).on('fileuploadfail', function(e, data) {
        $.each(data.files, function(index, file) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index]).append('<br>').append(error);
        });
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    // Load existing files:
    $('#fileupload_video').addClass('fileupload-processing');
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#fileupload_video').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload_video')[0]
    }).always(function() {
        $(this).removeClass('fileupload-processing');
    }).done(function(result) {
        $(this).fileupload('option', 'done').call(this, $.Event('done'), {
            result: result
        });
    });
});