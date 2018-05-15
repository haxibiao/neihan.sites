<template>
	<div class="captcha">
	    <div class="geetest-wind">
	          <div class="geetest-radar-btn">
	              <div class="geetest-radar">
	              	<div class="geetest-sector">
	              	</div>
	              	<div class="geetest-ring">
	              	</div>
	              	 <div class="geetest-small">
	              	 </div>
	              </div>
	              <div class="geetest-radar-tip">
	                  <span class="geetest-radar-tip-content">点击按钮进行验证</span>
	              </div>
	              <a class="geetest-logo" target="_blank" href="javascript:;"><i class="iconfont icon-dunpai"></i></a>
	          </div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'Captcha',

  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>
	.captcha {
		.geetest-wind {
			position: relative;
	    width: 100%;
	    height: 44px;
    	.geetest-radar-btn {
    		position: relative;
    		width: 100%;
    		height: 100%;
    		min-width: 160px;
    		background-image: linear-gradient(180deg, #ffffff 0%,#f3f3f3 100%);
    		border: 1px solid #ccc;
    		border-radius: 2px;
    		cursor: pointer;
    		&:hover {
    			background-image: linear-gradient(0deg, #ffffff 0%,#f3f3f3 100%);
    			.geetest-radar {
    				.geetest-sector {
    					animation: scaleOne .5s linear both infinite; 
    				}
						.geetest-ring {
							animation: scaleTwo .5s linear both infinite .5s; 
						}
    			}
    		}
				.geetest-radar {
					position: absolute;
					margin: 6px;
					width: 30px;
					height: 30px;
					z-index: 1;
					.geetest-sector {
						position: absolute;
						width: 100%;
						height: 100%;
						box-shadow: inset 0 0 0 1px #407FCF;
						background-color: #c5daf9;
						border-radius: 50%;
					}
					.geetest-ring {
						position: absolute;
						width: 100%;
						height: 100%;
						box-shadow: inset 0 0 0 1px #407FCF;
						background-color: #c5daf9;
						border-radius: 50%;
					}
					.geetest-small {
						position: absolute;
						width: 10px;
						height: 10px;
						top: 0;
						left: 0;
						right: 0;
						bottom: 0;
						margin: auto;
						background-color: #407FCF;
						border-radius: 50%;
					}
				}
				.geetest-radar-tip {
					position: absolute;
			    top: 0;
			    left: 0;
			    box-sizing: border-box;
			    padding-left: 46px;
			    height: 42px;
			    width: 100%;
			    line-height: 42px;
			    font-size: 14px;
			    color: #717171;
			    white-space: nowrap;
			    text-align: left;
			    transform: translate3d(0, 0, 0);
				}
				.geetest-logo {
					position: absolute;
					right: 11px;
					width: 22px;
					height: 22px;
					top: 11px;
					color: #BBB6CE; 
					text-align: center;
					line-height: 22px;
					i {
						font-size: 18px;
					}
				}
    	}
		}
	}
</style>