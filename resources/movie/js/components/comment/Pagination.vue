<template>
    <div class="app-pagination" v-if="totalPage > 1">
        <span class="prev" v-show="current > 1" v-on:click="clickPage(current - 1)">上一页</span>
        <template v-for="(page, index) in pageNumbers">
            <span v-if="page == current" :key="index" class="tcd-number active">{{ page }}</span>
            <a
                v-else-if="Number(page)"
                :key="index"
                href="javascript:;"
                class="tcd-number"
                v-on:click="clickPage(page)"
                >{{ page }}</a
            >
            <span v-else class="dian" :key="index">...</span>
        </template>
        <span class="next" v-show="current < totalPage" v-on:click="clickPage(current + 1)">下一页</span>
        <div class="page-jump" v-show="totalPage > tcdNumbers">
            共{{ this.totalPage }}页，跳至 <input type="text" v-on:keyup.enter="skipPage" />页
        </div>
    </div>
</template>

<script>
export default {
    props: {
        count: {
            type: Number,
            required: true,
        },
        offset: {
            type: Number,
            default: function() {
                return 10;
            },
        },
        current: {
            type: Number,
            default: function() {
                return 1;
            },
        },
    },
    computed: {
        totalPage() {
            return Math.ceil(this.count / this.offset);
        },
        pageNumbers() {
            var arr = [];

            if (this.totalPage > this.tcdNumbers) {
                for (let index = 0; index < this.tcdNumbers; index++) {
                    // 当前页大于中间值
                    if (this.current > this.midpoint) {
                        //  设置第一第二个按钮的固定值
                        if (index == 0 || index == 1) {
                            arr[0] = 1;
                            arr[1] = '...';
                            continue;
                        }
                        // 剩余页码已经少于中点数
                        if (this.totalPage - this.current < this.midpoint) {
                            // 避免左边显示过多按钮
                            if (this.current + this.midpoint > this.totalPage + index) {
                                continue;
                            }
                            arr[arr.length] = this.totalPage - (this.tcdNumbers - index) + 1;
                        } else {
                            var diff = index - this.midpoint + 1;
                            arr[index] = this.current + diff;
                        }
                    } else {
                        // 避免右边显示过多按钮
                        if (this.current + this.midpoint < index) {
                            break;
                        }
                        arr[index] = index + 1;
                    }
                }
                // 设置倒数第一第二个按钮的固定值
                if (this.current <= this.totalPage - this.midpoint) {
                    arr[arr.length - 1] = this.totalPage;
                    arr[arr.length - 2] = '...';
                }
            } else {
                for (let index = 0; index < this.totalPage; index++) {
                    arr[index] = index + 1;
                }
            }
            return arr;
        },
    },
    methods: {
        clickPage(page) {
            this.$emit('update:current', page);
        },
        skipPage(event) {
            var page = Number(event.target.value) || 1;
            if (page > this.totalPage) {
                page = this.totalPage;
            } else if (page < 1) {
                page = 1;
            }
            this.$emit('update:current', page);
        },
    },
    data() {
        return {
            tcdNumbers: 9,
            midpoint: 5,
        };
    },
};
</script>

<style lang="scss" scoped>
.app-pagination {
    text-align: center;
    .prev,
    .next,
    .tcd-number,
    .dian {
        color: #222;
        text-align: center;
        background-color: #fff;
        background-image: none;
        transition: all 0.2s;
        font-size: 14px;
        min-width: 15px;
        margin: 2px;
        padding: 0 10px;
        display: inline-block;
        height: 36px;
        min-width: 34px;
        line-height: 36px;
        text-decoration: none;
    }
    .dian {
        cursor: not-allowed;
    }
    .prev,
    .next,
    .tcd-number {
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid #ddd;
        &.active,
        &:hover {
            color: #fff;
            border-color: #29b6f6;
            background-color: #29b6f6;
        }
        &.active {
            cursor: not-allowed;
        }
    }
    .prev,
    .next {
        padding: 0 15px;
    }
    .page-jump {
        display: inline-block;
        font-size: 14px;
        color: #99a2aa;
        height: 36px;
        line-height: 36px;
        margin-left: 5px;
        @media (max-width: 575px) {
            display: block;
            margin-top: 8px;
        }
        input {
            padding: 0 10px;
            height: 26px;
            line-height: 24px;
            margin: 3px 5px 7px;
            font-size: 12px;
            box-shadow: none;
            width: 36px;
            border-radius: 4px;
            border: 1px solid #ddd;
            outline: none;
            text-align: center;
        }
    }
}
</style>
