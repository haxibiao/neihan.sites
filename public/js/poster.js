function _classCallCheck(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var _createClass=function(){function t(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,n,i){return n&&t(e.prototype,n),i&&t(e,i),e}}();!function(t){var e=function(){function t(e){_classCallCheck(this,t),this.container=e.container,this.data=e.data,this.speed=e.speed,this.auto=e.auto,this.flag=!0}return _createClass(t,[{key:"init",value:function(){this.createDom(this.container,this.data),this.active(),this.directiveHandler(),this.auto&&this.autoPlay(this.speed),this.hover()}},{key:"createDom",value:function(t,e){$("#poster").html('<div class="poster-inner">\n\t\t\t    \t    </div>\n\t\t\t    \t    <a href="javascript:;" class="left banner-btn">\n\t\t\t    \t        <i class="iconfont icon-zuobian"></i>\n\t\t\t    \t    </a>\n\t\t\t    \t    <a href="javascript:;" class="right banner-btn">\n\t\t\t    \t        <i class="iconfont icon-youbian"></i>\n\t\t\t    \t    </a>'),e.forEach(function(t,e,n){var i='<div class="item">\n\t\t    \t            \t<div class="banner">\n\t\t    \t            \t\t<a target="_blank" href="'+n[e][0]+'" alt="">\n\t\t    \t            \t\t\t<img src="'+n[e][1]+'" alt="">\n\t\t    \t            \t\t\t<div class="title">\n\t\t    \t            \t\t\t\t<p>'+n[e][2]+"</p>\n\t\t    \t            \t\t\t</div>\n\t\t    \t            \t\t</a>\n\t\t    \t            \t</div> \n\t\t    \t            </div>\n\t\t    \t            ";$("#poster .poster-inner").append(i)})}},{key:"active",value:function(){$("#poster .poster-inner").find(".item").first().addClass("active")}},{key:"directiveHandler",value:function(){var t=this;$("#poster .banner-btn").filter(".left").on("click",function(){t.roll("prev")}),$("#poster .banner-btn").filter(".right").on("click",function(){t.roll("next")})}},{key:"roll",value:function(t){function e(t,e){var i="next"==t?"left":"right";$(".poster-inner .item").eq(e).addClass(t).delay(50).queue(function(){$(".poster-inner .item.active").addClass(i).parent().children(".item").eq(e).addClass(i).dequeue()}).delay(600).queue(function(){$(".poster-inner .item.active").removeClass("active "+i).dequeue(),$(".poster-inner .item").filter("."+t).removeClass(t+" "+i).addClass("active").dequeue(),n.flag=!0})}var n=this;if(n.flag){n.flag=!1;var i=$(".poster-inner .item.active").index();"next"==t?(i==$(".poster-inner .item").length-1?i=0:i++,e(t,i)):(0==i?i=$(".poster-inner .item").length-1:i--,e(t,i))}}},{key:"autoPlay",value:function(t){var e=this;t=t<1e3?1e3:t,this.timer=setInterval(function(){e.roll("next")},t)}},{key:"hover",value:function(){var t=this;$("#poster").hover(function(){clearInterval(t.timer)},function(){t.auto&&(t.timer=setInterval(function(){t.roll("next")},t.speed))})}}]),t}();t.Poster=e}(window);