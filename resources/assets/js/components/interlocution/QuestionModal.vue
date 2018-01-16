<template>
    <!-- 问答页提问搜索 -->
    <div class="modal fade" id="question_modal">
     <form method="post" action="/question">
        <input type="hidden" name="_token" v-model="token">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">×
                    </button>
                    <h4 class="modal-title">
                        提问
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="input-question">
                        <input-matching name="title"></input-matching>
                    </div>
                    <div class="textarea-box textarea_box">
                        <textarea placeholder='添加问题背景描述（选填)' v-model="description" maxlength='500' name="background"></textarea>
                        <span class="word-count">{{ description.length }}/500</span>
                    </div>
                    <div class="img-selector">
                        <div :class="['ask-img-header',top3Imgs.length>0?'bigger':'']">添加图片<span class="desc">（最多3张）</span></div>
                        <div class="img-preview">
                            <div class="img-preview-item" v-for="item in top3Imgs">
                                <img :src="item.img" alt="" class="as-height">
                                <div class="img-del" @click="deleteImg(item)"><i class="iconfont icon-cha"></i></div>
                            </div>
                        </div>
                        <div class="tab-header">
                            <ul>
                                <li :class="tabActive=='free'?'tab-header-actived':''" @click="tabSwitch('free')">免费素材库</li>
                                <li :class="tabActive=='file'?'tab-header-actived':''" @click="tabSwitch('file')">本地上传</li>
                            </ul>
                        </div>
                        <div class="tab-body">
                            <div class="tab-body-item" v-show="tabActive=='free'">
                                <div class="material-search">
                                    <div class="search-icon"><i class="iconfont icon-sousuo"></i></div>
                                    <input type="text" class="search-input" placeholder="免费图片由「爱你城」提供">
                                    <input type="button" value="搜索" class="search-submit">
                                </div>
                                <div class="img-container">
                                    <div class="img-container-outer">
                                        <div class="img-tip">
                                            <span class="img-tip-content">
                                                使用图库视为您已同意<a href="javascript:;" target="_blank">《图片许可使用协议》</a>，如不同意，请停止使用图库；图片仅限您在问答发布问题使用，不得用于其他平台和用途。
                                            </span>
        
                                        </div>
                                        <div class="img-list">
                                            <div v-for="item in imgItems" :class="['img-item',item.selected ? 'img-item-check':'']" @click=selectImg(item)>
                                                <img :src="item.img" alt="">
                                                <div class="img-check"><i class="iconfont icon-weibiaoti12"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-body-item" v-show="tabActive=='file'">
                                <div class="img-upload-field">
                                    <div class="img-upload-btn">
                                        <i class="iconfont icon-tougaoguanli"></i>
                                        <span class="img-click-here">点击此处上传图片</span>
                                        <div class="img-file">
                                            <input type="file" @change="upload" multiple>
                                        </div>
                                        <span class="img-limit">支持图片拖拽上传</span>
                                    </div>
                                    <div class="img-loading" style="display: none;">
                                        <div class="img-progress">
                                            <span class="img-progress-bar" style="width: 100%;"></span>
                                            <span class="img-progress-num">图片上传中...</span>
                                        </div>
                                        <i class="iconfont icon-ask_close"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="clearfix">
                    <button class="btn_base btn_follow btn_followed_xs pull-right">提交</button>
                </footer>
                <input type="hidden" name="user_id" :value="userId">
                <input v-if="top3Imgs.length>0" name="image1" type="hidden" :value="top3Imgs[0].img">
                <input v-if="top3Imgs.length>1" name="image2" type="hidden" :value="top3Imgs[1].img">
                <input v-if="top3Imgs.length>2" name="image3" type="hidden" :value="top3Imgs[2].img">
    
            </div>
        </div>
        </form>
    </div>
</template>
<script>

import Dropzone from '../../plugins/Dropzone';

    export default {

  name: 'QuestionModal',

  computed:{
     token(){
        return window.csrf_token;
     },
     userId(){
        return window.current_user_id;
     }
  },

  mounted() {
    Dropzone($('.img-upload-field')[0], this.dragDropUpload);
  },

  methods: {
    dragDropUpload(fileObj, params) {
        console.log(this.filesCount);
        if(this.filesCount >=3 ) {
            return;
        }
        this._upload(fileObj);
        this.filesCount ++ ;
    },
    upload(e) {
        console.log(e.target.files);
        for(var i=0; i<e.target.files.length; i++) {
            if(this.filesCount >= 3) {
                break;
            }
            var fileObj = e.target.files[i];
            this._upload(fileObj);
            this.filesCount ++ ;
        }
    },
    _upload(fileObj) {
        var api = window.tokenize('/api/image/save');
        var _this = this;
        let formdata = new FormData();
        formdata.append('from','question');
        formdata.append('photo',fileObj);
            let config = {
            headers: {
               'Content-Type': 'multipart/form-data'
            }
        }
        console.log(api);
        window.axios.post(api, formdata, config).then(function(response){
            console.log(response.data);
           _this.imgItems.push({
                img: response.data,
                selected: 1
           });
           _this.top3Imgs = _this.selectedImgs();
        });
    },
    tabSwitch(tab) {
        this.tabActive = tab;
    },
    selectedImgs() {
        return _.filter(this.imgItems, ['selected', 1]);
    },
    selectImg(item) {
        console.log(this.selectedImgs().length);
        if(!item.selected && this.selectedImgs().length >= 3){
            return
        }
        item.selected = item.selected ? 0 : 1;
        this.top3Imgs = this.selectedImgs();
        item.selected ? this.filesCount++ : this.filesCount--;
    },
    deleteImg(item) {
        item.selected = 0;
        this.top3Imgs = this.selectedImgs();
        this.filesCount -- ;
    },
    submitQuestion() {
        window.axios.post('/api/question',{}).then(function(response) {
                alert('您的问题已提交');
            },function(error) {
                alert('提交失败');
            }
        )
    }
  },

  data () {
    return {
        description:'',
        tabActive:'free',
        filesCount: 0,
        top3Imgs:[],
        imgItems: [
            {'img':'/images/photo_01.jpg','selected': 0},
            {'img':'/images/photo_02.jpg','selected': 0},
            {'img':'/images/photo_03.jpg','selected': 0},
            {'img':'/images/photo_04.png','selected': 0},
            {'img':'/images/photo_user.png','selected': 0},
        ],
    }
  }
}
</script>
<style lang="scss" scoped="">
    #question_modal {
		.modal-dialog {
            padding-bottom: 20px;
            .modal-content {
                .modal-header {
                    padding: 10px 20px;
                }
                .modal-body {
                    padding: 20px 40px;
                    max-height: unset;
                    overflow: unset;
                    &>div {
                        line-height: normal;
                    }
                    .input-question {
                        margin-bottom: 10px;
                    }
                    .textarea-box {
                        position: relative;
                        .word-count {
                            position: absolute;
                            bottom: 1px;
                            right: 6px;
                            color: #969696;
                            font-size: 14px;
                        }
                    }
                    .img-selector {
                        margin-top: 20px;
                        position: relative;
                        .ask-img-header {
                            font-size: 14px;
                            padding-bottom: 20px;
                            &.bigger {
                                padding-bottom: 35px;
                            }
                            .desc {
                                color: #969696;
                            }
                        }
                        .img-preview {
                            position: absolute;
                            top: 0;
                            right: 0;
                            .img-preview-item {
                                float: right;
                                border: 1px solid #E8E8E8;
                                margin-left: 4px;
                                width: 60px;
                                height: 60px;
                                position: relative;
                                overflow: hidden;
                                .as-height {
                                    height: 100%;
                                }
                                .img-del {
                                    width: 18px;
                                    height: 18px;
                                    position: absolute;
                                    z-index: 2;
                                    top: 0;
                                    right: 0;
                                    background-color: rgba(0, 0, 0, 0.5);
                                    border-radius: 0 0 0 4px;
                                    padding: 1px;
                                    cursor: pointer;
                                    text-align: center;
                                    line-height: 18px;
                                    i {
                                        font-size: 14px;
                                        color: white;
                                    }
                                }
                            }
                        }
                        .tab-header {
                            ul {
                                li {
                                    padding-bottom: 6px;
                                    height: 20px;
                                    line-height: 20px;
                                    box-sizing: content-box;
                                    display: inline-block;
                                    font-size: 14px;
                                    color: #212121;
                                    margin-right: 30px;
                                    cursor: pointer;
                                    &.tab-header-actived {
                                        border-bottom: solid 2px #FF9D23;
                                        font-weight: bold;
                                    }
                                }
                            }
                        }
                        .tab-body {
                            height: 256px;
                            background-color: #f7f7f7;
                            margin: 0 -40px;
                            padding: 20px 0 0 0;
                            position: relative;
                            .tab-body-item {
                                height: 100%;
                                width: 100%;
                                position: relative;
                                .material-search {
                                    position: relative;
                                    height: 34px;
                                    margin: 0 40px 20px 40px;
                                    background-color: #FFFFFF;
                                    border: 1px solid #ececec;
                                    border-radius: 2px;
                                    overflow: hidden;
                                    .search-icon {
                                        position: absolute;
                                        top: 1px;
                                        left: 0;
                                        width: 31px;
                                        height: 31px;
                                        line-height: 31px;
                                        text-align: center;
                                    }
                                    .search-input {
                                        height: 100%;
                                        padding: 2px 64px 2px 32px;
                                        border: none;
                                        font-size: 14px;
                                        background-color: #fff;
                                    }
                                    .search-submit {
                                        height: 34px;
                                        width: 64px;
                                        background-color: #ececec;
                                        font-size: 14px;
                                        line-height: 14px;
                                        color: #515151;
                                        letter-spacing: 1px;
                                        border: none;
                                        position: absolute;
                                        top: 0;
                                        right: 0;
                                    }
                                }
                                .img-container {
                                    height: 180px;
                                    padding: 0 25px 0 40px;
                                    overflow-x: auto;
                                    .img-container-outer {
                                        overflow: hidden;
                                        .img-tip {
                                            margin: 0 0 20px 0;
                                            .img-tip-content {
                                                font-size: 13px;
                                                color: #969696;
                                                line-height: 18px;
                                                a {
                                                    color: #2B89CA;
                                                }
                                            }
                                        }
                                        .img-list {
                                            .img-item {
                                                width: 32.5%;
                                                height: 108px;
                                                margin: 0 1.25% 1.25% 0;
                                                float: left;
                                                position: relative;
                                                cursor: pointer;
                                                overflow: hidden;
                                                background-color: #e8e8e8;
                                                &:nth-of-type(3n) {
                                                    margin-right: 0;
                                                }
                                                img {
                                                    position: absolute;
                                                    width: 100%;
                                                    min-height: 100%;
                                                    top: 50%;
                                                    transform: translate(0,-50%);
                                                }
                                                .img-check {
                                                    position: absolute;
                                                    top: 0;
                                                    right: 0;
                                                    height: 18px;
                                                    width: 18px;
                                                    background-color: rgba(0, 0, 0, 0.5);
                                                    border-radius: 0 0 0 4px;
                                                    text-align: center;
                                                    line-height: 18px;
                                                    color: white;
                                                    i {
                                                        font-size: 14px;
                                                    }
                                                }
                                                &.img-item-check {
                                                    .img-check {
                                                        background-color: #FF9D23;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                .img-upload-field {
                                    height: 100%;
                                    padding-top: 46px;
                                    .img-upload-btn {
                                        position: relative;
                                        text-align: center;
                                        i {
                                            font-size: 42px;
                                            color: #c4c4c4;
                                            line-height: 1;
                                        }
                                        .img-click-here,.img-limit {
                                            font-size: 14px;
                                            color: #2B89CA;
                                            display: block;
                                            margin-top: 16px;
                                            line-height: 1;
                                        }
                                        .img-file {
                                            position: absolute;
                                            overflow: hidden;
                                            left: 50%;
                                            top: 0;
                                            margin-left: -60px;
                                            width: 120px;
                                            height: 100px;
                                            cursor: pointer;
                                            input {
                                                width: 100%;
                                                height: 100%;
                                                opacity: 0;
                                                cursor: pointer;
                                            }
                                        }
                                        .img-limit {
                                            color: #969696;
                                            margin-top: 12px;
                                        }
                                    }
                                    .img-loading {
                                        .img-progress {

                                        }
                                        .img-progress-bar {

                                        }
                                        .img-progress-num {

                                        }
                                        i {

                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                footer {
                    padding: 0 40px 20px 40px;
                }
            }
        }
	}
</style>