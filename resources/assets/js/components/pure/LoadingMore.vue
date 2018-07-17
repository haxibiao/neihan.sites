<template>
	<div v-if="end" class="end">
		讨厌，都被你看光光了╭(╯^╰)╮
	</div>
	<div v-else class="note-placeholder ">
		<div class="image"></div>
		<div class="content">
			<div class="title"></div>
			<div class="text"></div>
			<div class="text animation-delay"></div>
			<div class="meta">
				<div class="read"></div>
				<i class="iconfont icon-svg37"></i>
				<div class="small"></div>
				<i class="iconfont icon-03xihuan"></i>
				<div class="small"></div>
			</div>
		</div>
	</div>

</template>

<script>
export default {

  name: 'LoadingMore',

  props: ["end"],

  data () {
    return {

    }
  }
}
</script>

<style lang="scss">
.end {
	padding: 30px 0;
	background-color: #f0f0f0;
	font-size: 16px;
	color: #aaa;
	text-align: center;
}
.note-placeholder {
	margin-bottom: 30px;
	width: 100%;
	position: relative;
	.image {
		position: absolute;
		top: 0;
		right: 0;
		width: 22%;
		height: 100%;
		border-radius: 5px;
		background-color: #eaeaea;
	}
	.content {
		width: 100%;
		padding-right: 25%;
		.title,.text,.animation-delay {
			background-color: #eaeaea;
		}
		.title {
			width: 50%;
			height: 20px;
			margin-bottom: 12px;
		}
		.text {
			width: 100%;
			height: 16px;
			margin-bottom: 8px;
			animation: loading 0.5s ease-in-out infinite alternate;
		}
		.animation-delay {
			
			animation: loading 0.5s ease-in-out -.5s infinite alternate;
		}
		.meta {
			height: 16px;
			div {
				display: inline-block;
				height: 100%;
				background-color: #eaeaea;
			}
			i {
				font-size: 16px;
				color: #eaeaea;
				margin-right: 4px;
			}
			.read {
				width: 15%;
				margin-right: 4px;
			}
			.small {
				width: 10%;
				margin-right: 4px;
			}
		}
	}
}
@keyframes loading {
	from {
		width: 100%;
	}to {
		width: 50%;
	}
}
</style>