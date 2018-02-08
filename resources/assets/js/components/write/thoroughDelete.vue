<template>
    <div class="modal fade thorough-delete" v-if="recycleId">
        <div class="modal-dialog simple">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close">×</button>
                    <h4 class="modal-title">
				      彻底删除
					</h4>
                </div>
                <div class="modal-body">
					<p class="notice">确实彻底删除该文章吗，删除后不可恢复，请谨慎操作！</p>
					<p class="tip">{{ tip }}</p>
					<div class="verification-wrap">
					    <a class="iconfont icon-dunpai"></a>
					    <input type="tel" class="input_style" :placeholder="currentItem.title" v-model="input_code">
					</div>
                </div>
                <footer class="clearfix">
                	<a class="submit btn_base btn_sign_in" @click="submit">确认</a>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
export default {

  name: 'thoroughDelete',

  props:['recycleId'],

  computed: {
  	currentItem() {
  		var found = this.$store.state.trash.find( item => item.id == this.recycleId);
  		return found ? found : {};
  	}
  },

  methods: {
  	submit() {
  		if(!this.input_code) {
  			this.tip = '标题不能为空';
  		}
  		else if(this.input_code!==this.currentItem.title) {
  			this.input_code = '';
  			this.tip = '标题不符';
  		}
  		else {
  			this.$store.dispatch('destroyArticle',this.recycleId);
  			this.input_code = '';
  			$('.thorough-delete').modal('hide')
  		}
  	}
  },

  data () {
    return {
    	tip:'确实删除请输入文章标题',
    	input_code:null,
    }
  }
}
</script>

<style lang="scss">
	.thorough-delete {
		.modal-dialog.simple {
			width: 400px;
			.modal-body {
				.notice {
					text-align: center;
				}
				.tip {
					margin: 20px 0;
					text-align: center;
					span {
						font-size: 24px;
						margin: 0 5px;
					}
				}
				.verification-wrap {
					width: 100%;
					position: relative;
					input {
						padding-left: 30px;
						padding-right: 30px;
						height: 44px;
					}
					a.iconfont {
						position: absolute;
						left: 5px;
						top: 11px;
						font-size: 20px;
					}
				}
			}
			footer {
				.submit {
					font-size: 18px;
					height: 45px;
					line-height: 37px;
				}
			}
		}
	}
</style>