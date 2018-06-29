(function(win) {
	var poster = class Poster {
		constructor(option) {
			(this.container = option.container),
				(this.data = option.data),
				(this.speed = option.speed),
				(this.auto = option.auto),
				(this.flag = true);
		}
		init() {
			this.createDom(this.container, this.data);
			this.active();
			this.directiveHandler();
			if (this.auto) {
				this.autoPlay(this.speed);
			}
			this.hover();
		}
		createDom(container, data) {
			var html = `<div class="poster-inner">
			    	    </div>
			    	    <a href="javascript:;" class="left banner-btn">
			    	        <i class="iconfont icon-zuobian"></i>
			    	    </a>
			    	    <a href="javascript:;" class="right banner-btn">
			    	        <i class="iconfont icon-youbian"></i>
			    	    </a>`;
			$("#poster").html(html);
			data.forEach(function(ele, index, array) {
				var item = `<div class="item">
		    	            	<div class="banner">
		    	            		<a target="_blank" href="${array[index][0]}" alt="">
		    	            			<img src="${array[index][1]}" alt="">
		    	            			<div class="title">
		    	            				<p>${array[index][2]}</p>
		    	            			</div>
		    	            		</a>
		    	            	</div> 
		    	            </div>
		    	            `;
				$("#poster .poster-inner").append(item);
			});
		}
		active() {
			$("#poster .poster-inner")
				.find(".item")
				.first()
				.addClass("active");
		}
		directiveHandler() {
			$("#poster .banner-btn")
				.filter(".left")
				.on("click", () => {
					this.roll("prev");
				});
			$("#poster .banner-btn")
				.filter(".right")
				.on("click", () => {
					this.roll("next");
				});
		}
		roll(orde) {
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
				$(".poster-inner .item")
					.eq(index)
					.addClass(orde)
					.delay(50)
					.queue(function() {
						$(".poster-inner .item.active")
							.addClass(direction)
							.parent()
							.children(".item")
							.eq(index)
							.addClass(direction)
							.dequeue();
					})
					.delay(600)
					.queue(function() {
						$(".poster-inner .item.active")
							.removeClass("active" + " " + direction)
							.dequeue();
						$(".poster-inner .item")
							.filter("." + orde)
							.removeClass(orde + " " + direction)
							.addClass("active")
							.dequeue();
						that.flag = true;
					});
			}
		}
		autoPlay(speed) {
			speed = speed < 1000 ? 1000 : speed;
			this.timer = setInterval(() => {
				this.roll("next");
			}, speed);
		}
		hover() {
			$("#poster").hover(
				() => {
					clearInterval(this.timer);
				},
				() => {
					if (this.auto) {
						this.timer = setInterval(() => {
							this.roll("next");
						}, this.speed);
					}
				}
			);
		}
	};
	win.Poster = poster;
})(window);
