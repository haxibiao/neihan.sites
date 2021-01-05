function CarouselMovies({container, items, itemsInfo}) {
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
        z: 110,
    };
    this.init(this.items);
}

CarouselMovies.prototype.init = function () {
    for (let index = 0; index < this.items.length; index++) {
        const element = this.items[index];
        const url = $(element).attr('data-src');
        this.prevLoadImage(url, element);
    }
}

CarouselMovies.prototype.prevLoadImage = function (url, element) {
    const that = this;
    const img = new Image();
    img.src = url;
    img.onload = function(){
        img.onload = null;
        img.onerror = null;
        $(element).attr('src', url);
        that.loadedImages++;
        if(that.loadedImages == that.imagesCount) {
            that.startAnimation();
            that.onClickListener();
        }
    }
    img.onerror = function(){
        img.onload = null;
        img.onerror = null;
        $(element).attr('src', url);
        that.loadedImages++;
        if(that.loadedImages == that.imagesCount) {
            that.startAnimation();
            that.onClickListener();
        }
    }
}

CarouselMovies.prototype.startAnimation = function() {
    if(this.timer) {
        clearTimeout(this.timer)
    }
    for (let index = 0; index < this.items.length; index++) {
        const element = this.items[index];
        let translateX = 0;
        let translateZ = 0;
        let brightness = 60;
        if(index == this.currentMiddleIndex) {
            translateX = 0;
            translateZ = 300;
            brightness = 100;
            this.itemsInfo.removeClass('show');
            this.itemsInfo.eq(index).addClass('show');
        }else if(Math.abs((this.currentMiddleIndex - index)) > this.midpoint) {
            const sign = index > this.currentMiddleIndex ? -1 : 1;
            const distance = this.midpoint * 2 - Math.abs((this.currentMiddleIndex - index)) + 1;
            translateX = sign * this.translateValue.x * distance ;
            translateZ = 270 - this.translateValue.z * distance;
        }else {
            translateX = (index - this.currentMiddleIndex) * this.translateValue.x;
            translateZ = 270 - this.translateValue.z * Math.abs(index - this.currentMiddleIndex);
        }
        $(element).parent().css('transform', `translateX(${translateX}px) translateZ(${translateZ}px)`);
        $(element).parent().css('filter', `brightness(${brightness}%)`);

    }
    if(this.currentMiddleIndex === this.imagesCount - 1) {
        this.currentMiddleIndex = 0;
    }else {
        this.currentMiddleIndex++;
    }
    this.timer = setTimeout(() => {
        this.startAnimation()
    }, 5000);
}

CarouselMovies.prototype.onClickListener = function () {
    const that = this;
    const debouncedClickHandler = _.debounce(function clickHandler(event) {
        const currentIndex = $(event.target).parent().index();
        if(currentIndex == that.currentMiddleIndex - 1) {
            const url = $(event.target).parent().attr('href');
            if(url.indexOf('http')){
                window.location.assign(url)
            }else {
                window.location.assign(window.location.host + url);
            }
        }else {
            that.currentMiddleIndex = currentIndex;
            that.startAnimation();
        }
    }, 150);
    $(this.container).on('click', function onCarouselMovieClick(event) {
        event.preventDefault();
        debouncedClickHandler(event);
    });
}

module.exports = CarouselMovies;

// -480 -60 1
// -320 50 2
// -160 160 3
// 300 4
// 160 160 5
// 320 50 6
// 480 -60 7
