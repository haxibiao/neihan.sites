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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./packages/haxibiao/breeze/resources/assets/media/js/_hotMovies.js":
/*!**************************************************************************!*\
  !*** ./packages/haxibiao/breeze/resources/assets/media/js/_hotMovies.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function CarouselMovies(_ref) {
  var container = _ref.container,
      items = _ref.items,
      itemsInfo = _ref.itemsInfo;
  this.container = container; // 容器

  this.items = items; // 电影图片

  this.itemsInfo = itemsInfo; // 电影简洁

  this.loadedImages = 0; // 已经加载完毕的图片

  this.imagesCount = this.items.length; // 图片总数

  this.currentMiddleIndex = Math.floor(this.items.length / 2); // 当前中间索引

  this.midpoint = this.currentMiddleIndex; // 中点
  // translate step

  this.timer = null;
  this.translateValue = {
    x: 160,
    z: 110
  };
  this.init(this.items);
}

CarouselMovies.prototype.init = function () {
  for (var index = 0; index < this.items.length; index++) {
    var element = this.items[index];
    var url = $(element).attr('data-src');
    this.prevLoadImage(url, element);
  }
};

CarouselMovies.prototype.prevLoadImage = function (url, element) {
  var that = this;
  var img = new Image();
  img.src = url;

  img.onload = function () {
    img.onload = null;
    img.onerror = null;
    $(element).attr('src', url);
    that.loadedImages++;

    if (that.loadedImages == that.imagesCount) {
      that.startAnimation();
      that.onClickListener();
    }
  };

  img.onerror = function () {
    img.onload = null;
    img.onerror = null;
    $(element).attr('src', url);
    that.loadedImages++;

    if (that.loadedImages == that.imagesCount) {
      that.startAnimation();
      that.onClickListener();
    }
  };
};

CarouselMovies.prototype.startAnimation = function () {
  var _this = this;

  if (this.timer) {
    clearTimeout(this.timer);
  }

  for (var index = 0; index < this.items.length; index++) {
    var element = this.items[index];
    var translateX = 0;
    var translateZ = 0;
    var brightness = 60;

    if (index == this.currentMiddleIndex) {
      translateX = 0;
      translateZ = 300;
      brightness = 100;
      this.itemsInfo.removeClass('show');
      this.itemsInfo.eq(index).addClass('show');
    } else if (Math.abs(this.currentMiddleIndex - index) > this.midpoint) {
      var sign = index > this.currentMiddleIndex ? -1 : 1;
      var distance = this.midpoint * 2 - Math.abs(this.currentMiddleIndex - index) + 1;
      translateX = sign * this.translateValue.x * distance;
      translateZ = 270 - this.translateValue.z * distance;
    } else {
      translateX = (index - this.currentMiddleIndex) * this.translateValue.x;
      translateZ = 270 - this.translateValue.z * Math.abs(index - this.currentMiddleIndex);
    }

    $(element).parent().css('transform', "translateX(".concat(translateX, "px) translateZ(").concat(translateZ, "px)"));
    $(element).parent().css('filter', "brightness(".concat(brightness, "%)"));
  }

  if (this.currentMiddleIndex === this.imagesCount - 1) {
    this.currentMiddleIndex = 0;
  } else {
    this.currentMiddleIndex++;
  }

  this.timer = setTimeout(function () {
    _this.startAnimation();
  }, 5000);
};

CarouselMovies.prototype.onClickListener = function () {
  var that = this;

  var debouncedClickHandler = _.debounce(function clickHandler(event) {
    var currentIndex = $(event.target).parent().index();

    if (currentIndex == that.currentMiddleIndex - 1) {
      var url = $(event.target).parent().attr('href');

      if (url.indexOf('http')) {
        window.location.assign(url);
      } else {
        window.location.assign(window.location.host + url);
      }
    } else {
      that.currentMiddleIndex = currentIndex;
      that.startAnimation();
    }
  }, 150);

  $(this.container).on('click', function onCarouselMovieClick(event) {
    event.preventDefault();
    debouncedClickHandler(event);
  });
};

module.exports = CarouselMovies; // -480 -60 1
// -320 50 2
// -160 160 3
// 300 4
// 160 160 5
// 320 50 6
// 480 -60 7

/***/ }),

/***/ "./packages/haxibiao/breeze/resources/assets/media/js/home.js":
/*!********************************************************************!*\
  !*** ./packages/haxibiao/breeze/resources/assets/media/js/home.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var CarouselMovies = __webpack_require__(/*! ./_hotMovies */ "./packages/haxibiao/breeze/resources/assets/media/js/_hotMovies.js");

$(document).ready(function () {
  // $('#hot-movies').on('transitionend MSTransitionEnd webkitTransitionEnd oTransitionEnd');
  new CarouselMovies({
    container: '#hot-movies',
    items: $('#hot-movies .movie-pic'),
    itemsInfo: $('.hot-movies-intro .movie-info')
  });
});

/***/ }),

/***/ 3:
/*!**************************************************************************!*\
  !*** multi ./packages/haxibiao/breeze/resources/assets/media/js/home.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /data/www/neihan.sites/packages/haxibiao/breeze/resources/assets/media/js/home.js */"./packages/haxibiao/breeze/resources/assets/media/js/home.js");


/***/ })

/******/ });