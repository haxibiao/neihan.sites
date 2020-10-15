//设置rem
(function () {
    var newRem = function () {
        var html = document.documentElement;

        var ClientWidth = html.getBoundingClientRect().width;

        if (ClientWidth <= 1000) {
            html.style.fontSize = ClientWidth / 3.6 + "px";
        } else {
            html.style.fontSize = ClientWidth / 3.6 + "px";
        }
    };

    window.addEventListener("resize", newRem, false);
    newRem();
})();

//   视频播放
var VideoBtn = $("#video_btn");
var Video = $("#theVideo");
var videoInfo = $("#videoInfo");

var bofang = function () {
    if (Video.get(0).paused) {
        Video.get(0).play();
        VideoBtn.hide();
    } else {
        Video.get(0).pause();
        VideoBtn.show();
    }
};

Video.on("click",bofang);

videoInfo.on("click", bofang);

VideoBtn.on("click", function () {
        Video.get(0).play();
        VideoBtn.hide();
    }
);

// 播放结束
Video.get(0).addEventListener("ended",function(){
    $("#xiazaiimg").show();
    $("#tancen").show();
    VideoBtn.show();
},false);

//关闭广告
var close =$("#close")
close.on("click", function close() {
    $("#xiazaiimg").hide();
    $("#tancen").hide();
});

function isIos() {
    var u = navigator.userAgent;
    if (u.indexOf("iPhone") > -1 || u.indexOf("iOS") > -1) {
        return true;
    }
    return false;
}
// 下载
function openlink(link){
    var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger\/[0-9]/i )||ua.match(/QQ\/[0-9]/i) ){

            document.getElementById("mask").style.display="inline";

        } else {
            window.location.href=link;
        }
 
}



