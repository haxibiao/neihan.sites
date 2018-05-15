!(function(t) {
	function e(r) {
		if (n[r]) return n[r].exports;
		var i = (n[r] = { i: r, l: !1, exports: {} });
		return t[r].call(i.exports, i, i.exports, e), (i.l = !0), i.exports;
	}
	var n = {};
	(e.m = t),
		(e.c = n),
		(e.d = function(t, n, r) {
			e.o(t, n) ||
				Object.defineProperty(t, n, {
					configurable: !1,
					enumerable: !0,
					get: r
				});
		}),
		(e.n = function(t) {
			var n =
				t && t.__esModule
					? function() {
							return t.default;
						}
					: function() {
							return t;
						};
			return e.d(n, "a", n), n;
		}),
		(e.o = function(t, e) {
			return Object.prototype.hasOwnProperty.call(t, e);
		}),
		(e.p = ""),
		e((e.s = 1));
})({
	1: function(t, e, n) {
		t.exports = n("H8vr");
	},
	H8vr: function(t, e) {
		function n(t, e) {
			if (!(t instanceof e))
				throw new TypeError("Cannot call a class as a function");
		}
		var r = (function() {
			function t(t, e) {
				for (var n = 0; n < e.length; n++) {
					var r = e[n];
					(r.enumerable = r.enumerable || !1),
						(r.configurable = !0),
						"value" in r && (r.writable = !0),
						Object.defineProperty(t, r.key, r);
				}
			}
			return function(e, n, r) {
				return n && t(e.prototype, n), r && t(e, r), e;
			};
		})();
		!(function(t) {
			var e = (function() {
				function t(e) {
					n(this, t),
						(this.container = e.container),
						(this.data = e.data),
						(this.speed = e.speed),
						(this.auto = e.auto),
						(this.flag = !0);
				}
				return (
					r(t, [
						{
							key: "init",
							value: function() {
								this.createDom(this.container, this.data),
									this.active(),
									this.tabScroll(),
									this.auto && this.autoPlay(this.speed),
									this.hover();
							}
						},
						{
							key: "createDom",
							value: function(t, e) {
								$("#poster").html(
									'<div class="poster-inner">\n\t\t\t    \t    </div>\n\t\t\t    \t    <a href="javascript:;" class="left banner-btn">\n\t\t\t    \t        <i class="iconfont icon-zuobian"></i>\n\t\t\t    \t    </a>\n\t\t\t    \t    <a href="javascript:;" class="right banner-btn">\n\t\t\t    \t        <i class="iconfont icon-youbian"></i>\n\t\t\t    \t    </a>'
								),
									e.forEach(function(t, e, n) {
										var r =
											'<div class="item">\n\t\t    \t            \t<div class="banner"><a target="_blank" href="' +
											n[e][0] +
											'" alt=""><img src="' +
											n[e][1] +
											'" alt=""></a></div> \n\t\t    \t            \t<div class="banner"><a target="_blank" href="' +
											n[
												e + 1 - n.length >= 0
													? e + 1 - n.length
													: e + 1
											][0] +
											'" alt=""><img src="' +
											n[
												e + 1 - n.length >= 0
													? e + 1 - n.length
													: e + 1
											][1] +
											'" alt=""></a></div> \n\t\t    \t            \t<div class="banner"><a target="_blank" href="' +
											n[
												e + 2 - n.length >= 0
													? e + 2 - n.length
													: e + 2
											][0] +
											'" alt=""><img src="' +
											n[
												e + 2 - n.length >= 0
													? e + 2 - n.length
													: e + 2
											][1] +
											'" alt=""></a></div>\n\t\t    \t            </div>\n\t\t    \t            ';
										$("#poster .poster-inner").append(r);
									});
							}
						},
						{
							key: "active",
							value: function() {
								$("#poster .poster-inner")
									.find(".item")
									.first()
									.addClass("active");
							}
						},
						{
							key: "tabScroll",
							value: function() {
								var t = this;
								$("#poster .banner-btn")
									.filter(".left")
									.on("click", function() {
										t.roll("prev");
									}),
									$("#poster .banner-btn")
										.filter(".right")
										.on("click", function() {
											t.roll("next");
										});
							}
						},
						{
							key: "roll",
							value: function(t) {
								function e(t, e) {
									var r = "next" == t ? "left" : "right";
									$(".poster-inner .item")
										.eq(e)
										.addClass(t)
										.delay(50)
										.queue(function() {
											$(".poster-inner .item.active")
												.addClass(r)
												.parent()
												.children(".item")
												.eq(e)
												.addClass(r)
												.dequeue();
										})
										.delay(600)
										.queue(function() {
											$(".poster-inner .item.active")
												.removeClass("active " + r)
												.dequeue(),
												$(".poster-inner .item")
													.filter("." + t)
													.removeClass(t + " " + r)
													.addClass("active")
													.dequeue(),
												(n.flag = !0);
										});
								}
								var n = this;
								if (n.flag) {
									n.flag = !1;
									var r = $(
										".poster-inner .item.active"
									).index();
									"next" == t
										? (r ==
											$(".poster-inner .item").length - 1
												? (r = 0)
												: r++,
											e(t, r))
										: (0 == r
												? (r =
														$(".poster-inner .item")
															.length - 1)
												: r--,
											e(t, r));
								}
							}
						},
						{
							key: "autoPlay",
							value: function(t) {
								var e = this;
								(t = t < 1e3 ? 1e3 : t),
									(this.timer = setInterval(function() {
										e.roll("next");
									}, t));
							}
						},
						{
							key: "hover",
							value: function() {
								var t = this;
								$("#poster").hover(
									function() {
										clearInterval(t.timer);
									},
									function() {
										t.timer = setInterval(function() {
											t.roll("next");
										}, t.speed);
									}
								);
							}
						}
					]),
					t
				);
			})();
			t.Poster = e;
		})(window);
	}
});
