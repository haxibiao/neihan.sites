<template>
    <div class="simditor-box">
        <slot></slot>
        <div class="alert alert-success" v-show="draftShow">
           你要恢复未保存的草稿吗
            <span class="btn yes" @click="yesClick">yes</span>
            <span class=" btn no" @click="noClick">no</span>
        </div>
        <textarea :id="textareaId" :name="name" :placeholder="placeholderText" autofocus="autofocus" :data-autosave="autosave?'editor-content':null">
            {{ value }}
        </textarea>
    </div>
</template>

<script>
import Simditor from "../plugins/simditor";

export default {
    name: "Editor",

    props: ["placeholder", "name", "value", "picture", "video", "write", "focus", "autosave"],

    computed: {
        placeholderText() {
            return this.placeholder ? this.placeholder : "请开始您的写作...";
        }
    },

    //vue中，通过修改value属性来更新编辑器中的内容
    updated() {
        // if(this.value && this.value.length)
        if (this.write !== undefined) {
            this.editor.setValue(this.value);
            this.$emit("changed", this.value);
        } else {
            this.editor.setValue(this.editor_value);
        }
    },

    data() {
        return {
            editor_value: "",
            value_draft: "",
            draftShow: false,
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
                "image",
                "picture"
                // 'video', '|',
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

            this.editor.on("imgpicked", function(e, src) {
                _this.$emit("changed", _this.editor.getValue());
                _this.$emit("imgpicked", _this.editor.getValue());
            });

            this.editor.on("promptdraft", function(e, data) {
                _this.value_draft = data.draft;
                _this.draftShow = !_this.draftShow;
            });

            if (this.write !== undefined) {
                this.editor.on("blur", function(e, src) {
                    _this.$emit("blur", _this.editor.getValue());
                    //光标失去的时候，自动保存文章
                });
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
        yesClick() {
            this.draftShow = !this.draftShow;
            this.editor_value = this.value_draft;
            // this.editor.setValue(this.value_draft);
        },
        noClick() {
            this.draftShow = !this.draftShow;
            this.editor_value = "";
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
        margin-bottom: 10px !important;
    }
}
.simditor-toolbar {
    top: 52px !important;
}
</style>
