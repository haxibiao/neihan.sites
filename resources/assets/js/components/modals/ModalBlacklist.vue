<template>
    <div class="modal fade modal-blacklist">
        <div class="modal-dialog simple">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
                    加入黑名单
                    </h4>
                </div>
                <div class="modal-body">
                    <p>确认将该用户加入黑名单吗？在你黑名单中的用户无法在你文章下评论，无法在其它评论中提到你，无法给你发送私信，自动从你的粉丝列表移除且无法再关注你。</p>
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
    name: "ModalBlacklist",

    data() {
        return {};
    }
};
</script>

<style lang="scss" scoped>
.simple {
    width: 450px !important;
}
</style>
