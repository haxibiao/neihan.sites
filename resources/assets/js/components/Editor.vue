<template>
    <div class="simditor-box">
        <div class="promptBox" v-show="draftShow">
            <span class="title">{{autosavePrompt}}</span>
            <span class="btn yes" @click="yesClick">yes</span>
            <span class=" btn no" @click="noClick">no</span>
        </div>
        <textarea :id="textareaId" :name="name" :placeholder="placeholderText" data-autosave="editor-content">
            {{ html }}
        </textarea>
        <image-list-modal></image-list-modal>
    </div>
</template>
<script>
import Simditor from "../plugins/simditor";

export default {

    name: 'Editor',

    props: ['placeholder', 'name', 'value', 'picture', 'video'],

    computed: {
        html() {
            return this.editorHtml ? this.editorHtml : this.value;
        },
        placeholderText() {
            return this.placeholder ? this.placeholder : '请开始您的写作...';
        }
    },

    data() {
        return {
            draft:'',
            autosavePrompt:'',
            draftShow:false,
            editorHtml: null,
            textareaId: new Date().getTime(), //这里防止多个富文本发生冲突
            editor: null, //保存simditor对象
            toolbar: [
                'title', 'bold',
                // 'italic', 'underline', 'color', '|', 
                'ol', 'ul', 'blockquote', 'hr',
                // 'code',
                // 'link', '|',
                'image',
                'picture',
                // 'video', '|', 
                // 'indent', 'outdent'
            ]
        }
    },

    mounted() {
        this.loadEditor();
    },

    methods: {
        loadEditor() {

            var _this = this

            //自定义按钮
            if (this.picture　 !== undefined) {
                this.toolbar.push('picture');
            }
            if (this.video　 !== undefined) {
                this.toolbar.push('video');
            }

            this.editor = new Simditor({
                textarea: $('#' + _this.textareaId),
                toolbar: _this.toolbar,
                toolbarFloat: true,
                upload: {
                    url: window.tokenize('/api/image/save'),
                    params: {
                        from: 'simditor'
                    },
                    fileKey: 'photo',
                    connectionCount: 10, //同时上传个数
                    fileSize: 40960000,
                    leaveConfirm: '正在上传文件'
                },
                pasteImage: true,
                tabIndent: true
            });

            this.editor.on("valuechanged", function(e, src) {
                _this.editorHtml = _this.editor.getValue();
                 window.$bus.$emit('editor_value_changed');
            });

            this.editor.on('promptdraft', function(e, data) {
                _this.draft = data.draft;
                _this.draftShow = !_this.draftShow;
                _this.autosavePrompt=data.prompt;
            })
        },
        yesClick() {
            this.draftShow = !this.draftShow;
            this.editor.setValue(this.draft);
        },
        noClick() {
            this.draftShow = !this.draftShow;
            this.editor.setValue('');
        },
    }
}
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