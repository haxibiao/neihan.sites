!function(t){var e={};function n(i){if(e[i])return e[i].exports;var r=e[i]={i:i,l:!1,exports:{}};return t[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,i){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(i,r,function(e){return t[e]}.bind(null,r));return i},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=3)}({3:function(t,e,n){t.exports=n("dJQB")},dJQB:function(t,e,n){var i=n("iXGI");$(document).ready((function(){new i({container:"#hot-movies",items:$("#hot-movies .movie-pic"),itemsInfo:$(".hot-movies-intro .movie-info")})}))},iXGI:function(t,e){function n(t){var e=t.container,n=t.items,i=t.itemsInfo;this.container=e,this.items=n,this.itemsInfo=i,this.loadedImages=0,this.imagesCount=this.items.length,this.currentMiddleIndex=Math.floor(this.items.length/2),this.midpoint=this.currentMiddleIndex,this.timer=null,this.translateValue={x:160,z:110},this.init(this.items)}n.prototype.init=function(){for(var t=0;t<this.items.length;t++){var e=this.items[t],n=$(e).attr("data-src");this.prevLoadImage(n,e)}},n.prototype.prevLoadImage=function(t,e){var n=this,i=new Image;i.src=t,i.onload=function(){i.onload=null,i.onerror=null,$(e).attr("src",t),n.loadedImages++,n.loadedImages==n.imagesCount&&(n.startAnimation(),n.onClickListener())},i.onerror=function(){i.onload=null,i.onerror=null,$(e).attr("src",t),n.loadedImages++,n.loadedImages==n.imagesCount&&(n.startAnimation(),n.onClickListener())}},n.prototype.startAnimation=function(){var t=this;this.timer&&clearTimeout(this.timer);for(var e=0;e<this.items.length;e++){var n=this.items[e],i=0,r=0,o=60;if(e==this.currentMiddleIndex)i=0,r=300,o=100,this.itemsInfo.removeClass("show"),this.itemsInfo.eq(e).addClass("show");else if(Math.abs(this.currentMiddleIndex-e)>this.midpoint){var s=e>this.currentMiddleIndex?-1:1,a=2*this.midpoint-Math.abs(this.currentMiddleIndex-e)+1;i=s*this.translateValue.x*a,r=270-this.translateValue.z*a}else i=(e-this.currentMiddleIndex)*this.translateValue.x,r=270-this.translateValue.z*Math.abs(e-this.currentMiddleIndex);$(n).parent().css("transform","translateX(".concat(i,"px) translateZ(").concat(r,"px)")),$(n).parent().css("filter","brightness(".concat(o,"%)"))}this.currentMiddleIndex===this.imagesCount-1?this.currentMiddleIndex=0:this.currentMiddleIndex++,this.timer=setTimeout((function(){t.startAnimation()}),5e3)},n.prototype.onClickListener=function(){var t=this,e=_.debounce((function(e){var n=$(e.target).parent().index();if(n==t.currentMiddleIndex-1){var i=$(e.target).parent().attr("href");i.indexOf("http")?window.location.assign(i):window.location.assign(window.location.host+i)}else t.currentMiddleIndex=n,t.startAnimation()}),150);$(this.container).on("click",(function(t){t.preventDefault(),e(t)}))},t.exports=n}});