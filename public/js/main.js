!function(n){var e={};function t(o){if(e[o])return e[o].exports;var i=e[o]={i:o,l:!1,exports:{}};return n[o].call(i.exports,i,i.exports,t),i.l=!0,i.exports}t.m=n,t.c=e,t.d=function(n,e,o){t.o(n,e)||Object.defineProperty(n,e,{configurable:!1,enumerable:!0,get:o})},t.n=function(n){var e=n&&n.__esModule?function(){return n.default}:function(){return n};return t.d(e,"a",e),e},t.o=function(n,e){return Object.prototype.hasOwnProperty.call(n,e)},t.p="",t(t.s=0)}({0:function(n,e,t){t("aZpi"),t("xZZD"),t("u3Ud"),n.exports=t("dF3G")},aZpi:function(n,e){var t;t=function(){var n=document.documentElement,e=n.getBoundingClientRect().width;n.style.fontSize=e/3.6+"px"},window.addEventListener("resize",t,!1),t();var o=$("#video_btn"),i=$("#theVideo"),c=$("#videoInfo"),r=function(){i.get(0).paused?(i.get(0).play(),o.hide()):(i.get(0).pause(),o.show())};i.on("click",r),c.on("click",r),o.on("click",function(){i.get(0).play(),o.hide()}),i.get(0).addEventListener("ended",function(){$("#xiazaiimg").show(),$("#tancen").show(),o.show()},!1),$("#close").on("click",function(){$("#xiazaiimg").hide(),$("#tancen").hide()})},dF3G:function(n,e){},u3Ud:function(n,e){},xZZD:function(n,e){}});