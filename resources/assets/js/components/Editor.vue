<template>
	<div class="simditor-box">
        <textarea :id="textareaId" :name="name" :placeholder="placeholderText">
            {{ html }}
        </textarea>
        <image-list-modal></image-list-modal>
    </div>
</template>

<script>

import Simditor from '../plugins/simditor';

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

    mounted() {
        console.log('editor Component mounted.');
        this.createEditor();
    },

    methods: {
        createEditor() {

            var _this = this

            // if(this.picture　!== undefined) {
            //     this.toolbar.push('picture');
            // }
            // if(this.video　!== undefined) {
            //     this.toolbar.push('video');
            // }

            this.editor = new Simditor({
                textarea: $('#' + _this.textareaId),
                toolbar: _this.toolbar,
                // picture: {},
                // video: {},
                upload: {
                    url: '/api/image/save', //api for post to upload image 
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
                window.bus.$emit('editor_value_changed');
            })
            
            //valuechanged是simditor自带获取值得方法
        }
    },

    data () {
    	return {
	        editorHtml: null,
	        textareaId: new Date().getTime(), //这里防止多个富文本发生冲突
	        editor: null, //保存simditor对象
	        toolbar: [
	            'title','bold', 
	            // 'italic', 'underline', 
	            // 'color', 
	            // '|', 
	            'ol', 'ul', 'blockquote', 'hr',
	            // 'code',
	            'link', 
	            '|',
	            'image', 
	            'picture',
                 // 'video',
	            // '|', 
	            // 'indent', 'outdent'
	        ]
    }
  }
}
</script>

<style lang="scss">
.simditor-box {
    img {
        max-width: 99%;
    }
}
</style>