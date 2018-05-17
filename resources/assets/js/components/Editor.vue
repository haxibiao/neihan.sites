<template>
    <div class="simditor-box">
        <slot></slot>
        <textarea :id="textareaId" :name="name" :placeholder="placeholderText">
            {{ value }}
        </textarea>
    </div>
</template>

<script>
import Simditor from "../plugins/simditor";

export default {
    name: "Editor",

    props: ["placeholder", "name", "value", "picture", "video", "write", "focus"],

    computed: {
        placeholderText() {
            return this.placeholder ? this.placeholder : "请开始您的写作...";
        }
    },

    //vue中，通过修改value属性来更新编辑器中的内容
    updated() {
        if (this.value != this.editor.getValue()) {
            this.editor.setValue(this.value);
        }
    },

    data() {
        return {
            textareaId: new Date().getTime(), //这里防止多个富文本发生冲突
            editor: null, //保存simditor对象
            toolbar: [
                "title",
                "bold",
                // 'italic', 'underline', 'color', '|',
                "ol",
                "ul",
                "blockquote",
                "hr",
                // 'code',
                // 'link', '|',
                "image"
                // 'picture', 'video', '|',
                // 'indent', 'outdent'
            ]
        };
    },

    mounted() {
        this.loadEditor();
    },

    methods: {
        loadEditor() {
            var _this = this;

            //自定义按钮
            if (this.picture !== undefined) {
                this.toolbar.push("picture");
            }
            if (this.video !== undefined) {
                this.toolbar.push("video");
            }
            if (this.write !== undefined) {
                this.toolbar.push("save");
                this.toolbar.push("publish");
            }

            this.editor = new Simditor({
                textarea: $("#" + _this.textareaId),
                toolbar: _this.toolbar,
                toolbarFloat: true,
                upload: {
                    url: window.tokenize("/api/image/save"),
                    params: {
                        from: "simditor"
                    },
                    fileKey: "photo",
                    connectionCount: 10, //同时上传个数
                    fileSize: 40960000,
                    leaveConfirm: "正在上传文件"
                },
                pasteImage: true,
                tabIndent: true
            });

            this.editor.on("imageuploaded", function(e, src) {
                window.$bus.$emit("imageuploaded", _this.editor.getValue());
            });

            this.editor.on("valuechanged", function(e, src) {
                _this.$emit("changed", _this.editor.getValue());
            });

            if (this.write !== undefined) {
                this.editor.on("saved", function(e, src) {
                    _this.$emit("saved", _this.editor.getValue());
                    //TODO:: 光标应该跑最后跳动的位置
                    _this.editor.focus();
                });
                this.editor.on("published", function(e, src) {
                    _this.$emit("published", _this.editor.getValue());
                    //TODO:: 光标应该跑最后跳动的位置
                    _this.editor.focus();
                });
            }
        },
        textChanged() {
            console.log("textchanged...");
            this.$emit("textchanged");
        }
    }
};
</script>

<style lang="scss">
.simditor-box {
    img {
        max-width: 100%;
        width: auto;
        height: auto;
    }
}
.simditor-toolbar {
    top: 52px !important;
}
</style>
