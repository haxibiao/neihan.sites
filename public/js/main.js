/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/main.js":
/***/ (function(module, exports) {

//设置rem
(function () {
    var newRem = function newRem() {
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

var bofang = function bofang() {
    if (Video.get(0).paused) {
        Video.get(0).play();
        VideoBtn.hide();
    } else {
        Video.get(0).pause();
        VideoBtn.show();
    }
};

Video.on("click", bofang);

videoInfo.on("click", bofang);

VideoBtn.on("click", function () {
    Video.get(0).play();
    VideoBtn.hide();
});

// 播放结束
Video.get(0).addEventListener("ended", function () {
    $("#xiazaiimg").show();
    $("#tancen").show();
    VideoBtn.show();
}, false);

//关闭广告
var close = $("#close");
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
function openlink(link) {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger\/[0-9]/i) || ua.match(/QQ\/[0-9]/i)) {

        document.getElementById("mask").style.display = "inline";
    } else {
        window.location.href = link;
    }
}

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/main.js");


/***/ })

/******/ });