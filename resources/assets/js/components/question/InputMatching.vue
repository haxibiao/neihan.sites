<template>
	<div class='input-matching' ref="input-matching">
		<input :name="name" :class="['input-style',title&&matchedData.length?'matching':'']" type="text" :placeholder="placeholder?placeholder:'请输入问题（不超过40字）'" v-model="title" @input="inputQuestion">
		<div class="matched-wrap" v-show="title&&matchedData.length">
			<h5>相似问题</h5>
			<ul class="matched">
				<li v-for="question in matchedData" @click="selectQuestion(question)">
					<span class="small pull-right">继续提问</span>
					<a :href="'/question/'+question.id" class="matchItem">{{ question.title }}</a>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
export default {
	name: "InputMatching",

	props: ["name", "placeholder"],

	methods: {
		inputQuestion() {
			// $('.matched-wrap').show();
			this.$nextTick(function() {
				$(document).click(function() {
					$(".matched-wrap").hide();
				});
			});
			var _this = this;
			axios
				.get(
					tokenize("/api/suggest-question?q=" + this.title),
					this.question
				)
				.then(
					function(response) {
						// 因为没有api所以就凉了
						// _this.matchedData = _this.simulationData;
						_this.matchedData = response.data;
					},
					function(error) {
						_this.matchedData = [];
					}
				);
		},
		selectQuestion(question) {
			this.title = question.title;
		}
	},

	data() {
		return {
			title: "",
			matchedData: [],
			simulationData: [
				{ id: 1, title: "你要多喝水才行啊" },
				{ id: 2, title: "怎么才能让你去多喝水" },
				{ id: 3, title: "你要怎么样才去喝水" },
				{ id: 4, title: "快点去喝水，最好是热水" },
				{ id: 5, title: "我们怎么做到一天喝水喝到吐" },
				{ id: 6, title: "喝水喝到什么程度才算厉害" },
				{ id: 7, title: "你们今天喝水了吗" },
				{ id: 8, title: "医生说没事多喝水" }
			]
		};
	}
};
</script>

<style lang="scss" scoped>
.input-matching {
	width: 100%;
	position: relative;
	input {
		height: 35px;
		font-size: 14px;
		&.matching {
			border: 1px solid #c4c4c4;
			border-bottom: none;
			border-radius: 2px 2px 0 0;
		}
	}
	.matched-wrap {
		position: absolute;
		width: 100%;
		border: 1px solid #c4c4c4;
		border-radius: 0 0 2px 2px;
		text-indent: 0.5em;
		line-height: 30px;
		background-color: #fff;
		z-index: 3;
		font-size: 14px;
		.matched {
			li {
				padding-right: 10px;
				cursor: pointer;
				&:hover {
					background-color: #ececec;
				}
				.matchItem {
					// display: block;
					color: #717171;
				}
			}
		}
	}
}
</style>
