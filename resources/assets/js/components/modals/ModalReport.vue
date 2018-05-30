<template>
    <div class="modal fade modal-report">
        <div class="modal-dialog simple">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
                    举报用户
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="radio" name="report" value="ad" checked="checked">
                    <span>广告及垃圾信息</span>
                    <input type="radio" name="report" value="plagiarism"> 
                    <span>抄袭或未授权转载</span>
                    <input type="radio" name="report" value="other"> 
                    <span>其它</span>
                    <textarea placeholder="写下举报的详情情况"></textarea>
                </div>
                <footer class="clearfix">
                    <button class="submit btn-base btn-hollow btn-sm">确认</button>
                    <a class="cancel" data-dismiss="modal" aria-label="Close" href="javascript:;">取消</a>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalReport",

    data() {
        return {};
    }
};
</script>

<style lang="scss" scoped>
.simple {
    width: 450px !important;
    .modal-body {
        span {
            margin-right: 10px;
        }
        textarea {
            margin-top: 20px;
            height: auto;
            font-size: 14px;
        }
    }
}
</style>
