<template>
	<a :data-content="dataContent" :data-placement="placement" class="share
    _button" data-html="true" data-original-title="" data-toggle="popover" data-trigger="focus" role="button" tabindex="0" title="">
        <i class="iconfont icon-yonghu01"></i>
    </a>
</template>

<script>
export default {

  name: 'Writer',

  props:['placement'],

  computed: {
    dataContent() {
        return `<ul class='popover_share_menu'>
                    <li>
                   	 	<div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_04.png' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_03.jpg' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		兵长一米六
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_04.png' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		Online Loan
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_03.jpg' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_04.png' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_03.jpg' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_04.png' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_03.jpg' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                    <li>
                        <div class='author'>
                   	 		<a href='' class='avatar avatar_xx'>
	                            <img src='/images/photo_04.png' />
	                        </a>
	                        <div class='info_meta'>
	                        	<a href='' class='nickname'>
	                        		NICE  MOON
	                        	</a>
	                        </div>
                   	 	</div>
                    </li>
                </ul>`;
    }
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>