<template>
    <div class="modal fade modal-tips">
        <div class="modal-dialog simple">
            <div class="modal-content">

                <h4>至少保留一个文集! </h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalTips",

    data() {
        return {};
    }
};
</script>

<style lang="scss" scoped>
.simple {
    width: 300px !important;
    height: 160px !important;
    .modal-content{
    	height: 100%;
    	display: flex;
    	justify-content: center;
    	align-items: center;
    }
}
</style>
