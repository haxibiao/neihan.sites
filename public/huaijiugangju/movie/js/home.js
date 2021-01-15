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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/themes/huaijiugangju/assets/js/movie/_homeCarousel.js":
/*!*************************************************************************!*\
  !*** ./resources/themes/huaijiugangju/assets/js/movie/_homeCarousel.js ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// 首页轮播图js
$(function () {
  // 将#banner #bg中的第一个li复制一份, 放到#banner #bg的最后
  $firstLiCopy = $("#banner #bg li:first").clone(true);
  $("#banner #bg").append($firstLiCopy);
  var imgWidth = 1200;
  var imgCount = $("#banner #bg li").length; // 此时, imgCount = 5, 但是实际上我们循环显示的只有4张
  // 为了便于区分说明, 我们声明: 第1张图片为【真正的第1张图片】, 第5张图片为【真正的最后1张图片】
  // 动态设置#banner #bg的宽度

  $("#banner #bg").css("width", imgWidth * imgCount); // 有多少张图片, 底部就有多个小图片
  // for (var i = 0; i < imgCount - 1; i++) { // i = 0, 1, 2, 3 < 4
  //     $("#banner #aList").append($("<li></li>"));
  // }
  // 动态纠正#banner #aList, 使其水平居中
  // $("#banner #aList").css("marginLeft", -$("#banner #aList").width() / 2);
  // 底部小图片选中时的样式

  var aSelectedCss = {
    background: "rgba(255, 255, 255, 0.5)",
    width: 100
  }; // 底部小图片未选中时的样式

  var aNoneCss = {
    background: "rgba(0, 0, 0, 0.5)",
    width: 100
  }; // 选中时底部图片的样式

  var selectedimageCss = {
    border: "2px solid #EB5488"
  };
  var imageCss = {
    border: "2px solid #fff"
  }; // 一开始进来页面, 将底部的第一个小图片设置为选中的样式

  $("#banner #aList li").eq(0).css(aSelectedCss);
  $("#banner #aList li:eq(0) img").css(selectedimageCss);
  var stayDuration = 4000;
  var slideDuration = 500; // 一张图片滑动所需的时间

  var curIndex = 0; // 当前的显示的图片的索引(从0开始)
  // 根绝curIndex的变化, 动态设置底部那一排小图片的样式变化
  // 在slide方法中被调用

  function setA() {
    // 类推一下。 当第1张图片滑动到第2张图片完成后, 此时curIndex = 1
    // 当第4张图片滑动到第5张图片完成后, 换言之此时刚刚开始显示第5张(第1张的副本), 那么此时的curIndex为多少？
    // 所以那么此时curIndex == imgCount - 1 == 4
    if (curIndex == imgCount - 1) {
      $("#banner #aList li").eq(0).css(aSelectedCss);
      $("#banner #aList li").eq(0).siblings().css(aNoneCss);
      $("#banner #aList li:eq(0) img").css(selectedimageCss);
      $("#banner #aList li:eq(0)").siblings().children('img').css(imageCss);
      return;
    } // 在这里是否需要考虑curIndex < 0的情况？
    // 不需要!!!


    $curA = $("#banner #aList li").eq(curIndex);
    $curAimage = $("#banner #aList li:eq(" + curIndex + ") img");
    $curA.css(aSelectedCss);
    $curA.siblings().css(aNoneCss);
    $curAimage.css(selectedimageCss);
    $curA.siblings().children('img').css(imageCss);
  } // 滑动一张图片


  function slide(isToLeft) {
    // ifToLeft = true : 表示从右向左滑动
    if (isToLeft) {
      curIndex++; // 如果curIndex == 2, 说明第1张图片刚刚滑过去, 现在正在显示第2张图片
      // 以此类推
      // 如果curIndex == 5, 说明第4张图片刚刚滑过去, 现在正在显示第5张图片(第5张图片是第一张图片的副本)

      if (curIndex >= imgCount) {
        // 此时偷梁换柱, 一瞬间将位置瞬移到真正的第1张图片
        curIndex = 1; // 第一张已经完成滑过动画了

        $("#banner #bg").css("marginLeft", 0);
      }

      $("#banner #bg").stop().animate({
        //marginLeft: "-=" + imgWidth // 不能使用这个, 否则当连续快速点击"<"会有bug
        marginLeft: -curIndex * imgWidth
      }, slideDuration, function () {
        setA();
      });
    } else {
      curIndex--; // 如果curIndex == -1, 说明下面需要执行的动画就是: 【真正的第1张图片】滑动到【真正的最后1张图片】
      // 不过在此之前, 先偷梁换柱, 将位置瞬移到第5张（第1张的副本）的位置

      if (curIndex < 0) {
        curIndex = imgCount - 2; // curIndex = 3

        $("#banner #bg").css("marginLeft", -imgWidth * (imgCount - 1));
      }

      $("#banner #bg").stop().animate({
        //marginLeft: "+=" + imgWidth // 不能使用这个, 否则当连续快速点击">"会有bug
        marginLeft: -curIndex * imgWidth
      }, slideDuration, function () {
        // if (curIndex == -1) 里面已经将curIndex重置为3, 所以在setA里面不要考虑curIndex < 0的情况
        setA();
      });
    }
  }

  var timer = null; // 开启自动滑动

  function autoPlay() {
    timer = setInterval(function () {
      // 这里给timer赋值了!!!
      slide(true); // 从右向左滑动
    }, stayDuration); // 先等待再执行
  }

  autoPlay(); // 一进页面, 轮播图自动滑动起来

  $("#banner").hover(function () {
    clearInterval(timer); // 鼠标悬停在轮播图（即#banner）时, 清除定时器timer
  }, function () {
    autoPlay(); // 鼠标移出时, 再开一个定时器
  }); // 我们通过底部的小图片和左右的"<"和">"来切换图片的时候, 是否会影响和混乱轮播图的自动循环滑动？
  // 不会!! 因为鼠标悬停在轮播图的时候, 我们已经将定时器timer的清除掉了
  // $("#banner #aList li").click(function() {
  //     $(this).css(aSelectedCss);
  //     $(this).siblings().css(aNoneCss);
  //     $(this).children('img').css(selectedimageCss);
  //     $(this).siblings().children('img').css(imageCss);
  //     curIndex = $(this).index();
  //     $("#banner #bg").css("marginLeft", -imgWidth * curIndex);
  // });
  // 鼠标移入和点击的效果一直，多了放大图片的效果

  $("#banner #aList li").mouseover(function () {
    $(this).css(aSelectedCss);
    $(this).siblings().css(aNoneCss);
    $(this).children('img').css(selectedimageCss);
    $(this).siblings().children('img').css(imageCss);
    $(this).children('img').addClass("transformImg");
    curIndex = $(this).index();
    $("#banner #bg").css("marginLeft", -imgWidth * curIndex);
  });
  $("#banner #aList li").mouseout(function () {
    $(this).children('img').removeClass("transformImg");
  });
  $("#banner .left").click(function () {
    slide(false);
  });
  $("#banner .right").click(function () {
    slide(true);
  });
});

/***/ }),

/***/ "./resources/themes/huaijiugangju/assets/js/movie/home.js":
/*!****************************************************************!*\
  !*** ./resources/themes/huaijiugangju/assets/js/movie/home.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// var CarouselMovies = require('./_hotMovies');
__webpack_require__(/*! ./_homeCarousel */ "./resources/themes/huaijiugangju/assets/js/movie/_homeCarousel.js"); // $(document).ready(function() {
//     // $('#hot-movies').on('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd');
//     new CarouselMovies({
//         container: '#hot-movies',
//         items: $('#hot-movies .movie-pic'),
//         itemsInfo: $('.hot-movies-intro .movie-info'),
//     });
// })

/***/ }),

/***/ 4:
/*!**********************************************************************!*\
  !*** multi ./resources/themes/huaijiugangju/assets/js/movie/home.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /data/www/neihan.sites/resources/themes/huaijiugangju/assets/js/movie/home.js */"./resources/themes/huaijiugangju/assets/js/movie/home.js");


/***/ })

/******/ });