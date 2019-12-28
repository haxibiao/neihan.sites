var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function (win) {
	var poster = function () {
		function Poster(option) {
			_classCallCheck(this, Poster);

			this.container = option.container, this.data = option.data, this.speed = option.speed, this.auto = option.auto, this.flag = true;
		}

		_createClass(Poster, [{
			key: "init",
			value: function init() {
				this.createDom(this.container, this.data);
				this.active();
				this.directiveHandler();
				if (this.auto) {
					this.autoPlay(this.speed);
				}
				this.hover();
			}
		}, {
			key: "createDom",
			value: function createDom(container, data) {
				var html = "<div class=\"poster-inner\">\n\t\t\t    \t    </div>\n\t\t\t    \t    <a href=\"javascript:;\" class=\"left banner-btn\">\n\t\t\t    \t        <i class=\"iconfont icon-zuobian\"></i>\n\t\t\t    \t    </a>\n\t\t\t    \t    <a href=\"javascript:;\" class=\"right banner-btn\">\n\t\t\t    \t        <i class=\"iconfont icon-youbian\"></i>\n\t\t\t    \t    </a>";
				$("#poster").html(html);
				data.forEach(function (ele, index, array) {
					var item = "<div class=\"item\">\n\t\t    \t            \t<div class=\"banner\">\n\t\t    \t            \t\t<a target=\"_blank\" href=\"" + array[index][0] + "\" alt=\"\">\n\t\t    \t            \t\t\t<img src=\"" + array[index][1] + "\" alt=\"\">\n\t\t    \t            \t\t\t<div class=\"title\">\n\t\t    \t            \t\t\t\t<p>" + array[index][2] + "</p>\n\t\t    \t            \t\t\t</div>\n\t\t    \t            \t\t</a>\n\t\t    \t            \t</div> \n\t\t    \t            </div>\n\t\t    \t            ";
					$("#poster .poster-inner").append(item);
				});
			}
		}, {
			key: "active",
			value: function active() {
				$("#poster .poster-inner").find(".item").first().addClass("active");
			}
		}, {
			key: "directiveHandler",
			value: function directiveHandler() {
				var _this = this;

				$("#poster .banner-btn").filter(".left").on("click", function () {
					_this.roll("prev");
				});
				$("#poster .banner-btn").filter(".right").on("click", function () {
					_this.roll("next");
				});
			}
		}, {
			key: "roll",
			value: function roll(orde) {
				var that = this;
				if (that.flag) {
					that.flag = false;
					var index = $(".poster-inner .item.active").index();
					if (orde == "next") {
						if (index == $(".poster-inner .item").length - 1) {
							index = 0;
						} else {
							index++;
						}
						carousel(orde, index);
					} else {
						if (index == 0) {
							index = $(".poster-inner .item").length - 1;
						} else {
							index--;
						}
						carousel(orde, index);
					}
				}
				function carousel(orde, index) {
					var direction = orde == "next" ? "left" : "right";
					$(".poster-inner .item").eq(index).addClass(orde).delay(50).queue(function () {
						$(".poster-inner .item.active").addClass(direction).parent().children(".item").eq(index).addClass(direction).dequeue();
					}).delay(600).queue(function () {
						$(".poster-inner .item.active").removeClass("active" + " " + direction).dequeue();
						$(".poster-inner .item").filter("." + orde).removeClass(orde + " " + direction).addClass("active").dequeue();
						that.flag = true;
					});
				}
			}
		}, {
			key: "autoPlay",
			value: function autoPlay(speed) {
				var _this2 = this;

				speed = speed < 1000 ? 1000 : speed;
				this.timer = setInterval(function () {
					_this2.roll("next");
				}, speed);
			}
		}, {
			key: "hover",
			value: function hover() {
				var _this3 = this;

				$("#poster").hover(function () {
					clearInterval(_this3.timer);
				}, function () {
					if (_this3.auto) {
						_this3.timer = setInterval(function () {
							_this3.roll("next");
						}, _this3.speed);
					}
				});
			}
		}]);

		return Poster;
	}();
	win.Poster = poster;
})(window);