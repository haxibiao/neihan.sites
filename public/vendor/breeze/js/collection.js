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