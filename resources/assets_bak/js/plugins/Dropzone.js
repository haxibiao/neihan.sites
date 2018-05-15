export default function (el, upload) {
    
    //stop document drag drop events ...
    $(document.body).on("dragover", function(e) {
      e.originalEvent.dataTransfer.dropEffect = "none";
      return e.preventDefault();
    });
    $(document.body).on('drop', function(e) {
      return e.preventDefault();
    });

    //bind out drap drop events to el...
    return $(el).on("dragover", function(e) {
      e.originalEvent.dataTransfer.dropEffect = "copy";
      e.stopPropagation();
      return e.preventDefault();
    }).on("dragenter", function(e) {
        e.preventDefault();
        return e.stopPropagation();
    }).on("dragleave", function(e) {
        e.preventDefault();
        return e.stopPropagation();
    }).on("drop", function(e) {
        var file, imageFiles, _i, _j, _len, _len1, _ref;
        imageFiles = [];
        // 获取鼠标或者粘贴板的引用
        _ref = e.originalEvent.dataTransfer.files;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          file = _ref[_i];
          if (!(file.type.indexOf("image/") > -1)) {
            alert("「" + file.name + "]」文件不是图片。");
            return false;
          }
          imageFiles.push(file);
        }
        for (_j = 0, _len1 = imageFiles.length; _j < _len1; _j++) {
          file = imageFiles[_j];
          // 调用upload
          upload(file, {
            inline: true
          });
        }
        e.stopPropagation();
        return e.preventDefault();
      });
}