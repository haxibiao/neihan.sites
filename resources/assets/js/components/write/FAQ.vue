<template>
	<div class="modal fade FAQ">
	    <div class="modal-dialog simple">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">常见问题</h4>
	            </div>
	            <div class="modal-body">
	              <div class="solution">
	              	如果你在使用编辑器的过程中遇到问题，可以尝试以下方案解决：
	              	<ul>
	              		<li>1. Windows用户尽量将浏览器设置为极速模式，不要使用兼容模式写作</li>
	              		<li>2.推荐使用chrome浏览器，创作体验更加流畅</li>
	              		<li>3.浏览器插件可能与编辑器功能冲突，可以在使用编辑器时禁用插件</li>
	              	</ul>
	              </div>
	            </div>
	            <footer class="clearfix">
	            	<a class="cancel" data-dismiss="modal" aria-label="Close" href="javascript:;">我知道了</a>
	            </footer>
	        </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'FAQ',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss">
.FAQ {
	.modal-dialog.simple {
		.modal-body {
			border-bottom: 1px solid #e5e5e5;
			.solution {
				font-size: 14px;
				ul {
					li {
						margin-top: 20px;
					}
				}
			}
		}
		footer {
			text-align: center;
			.cancel {
				float: unset;
				font-size: 16px;
				color: #ec7259;
			}
		}
	}
}
</style>