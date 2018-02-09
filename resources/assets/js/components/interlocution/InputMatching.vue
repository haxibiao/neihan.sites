<template>
	<div class='input_box input-matching' ref="input-matching">
		<input :name="name" :class="['form-control',title&&matchedData.length?'matching':'']" type="text" placeholder="请输入问题(不超过40字)" v-model="title" @input="inputQuestion" autocomplete="off">
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

  name: 'InputMatching',

  props:['name'],

  methods: {
  	inputQuestion() {
  		this.$nextTick(function() {
			$(document).click(function() {
				$('.matched-wrap').hide();
			});  					
		});
  		var vm = this;
  		var api=window.tokenize('/api/question/search?que='+this.title);
  		window.axios.get(api).then(function(data){
  				vm.matchedData = data.data;
  			},function(error){
  				vm.matchedData = [];
  			}
  		)
  	},
  	selectQuestion(question) {
  		this.title = question.title;
  	}
  },

  data () {
    return {
	  title: '',
	  matchedData:[],
	  simulationData:[
		{'id':1,'text':'你要多喝水才行啊'},
		{'id':2,'text':'怎么才能让你去多喝水'},
		{'id':3,'text':'你要怎么样才去喝水'},
		{'id':4,'text':'快点去喝水，最好是热水'},
		{'id':5,'text':'我们怎么做到一天喝水喝到吐'},
		{'id':6,'text':'喝水喝到什么程度才算厉害'},
		{'id':7,'text':'你们今天喝水了吗'},
		{'id':8,'text':'医生说没事多喝水'},
	  ]
    }
  }
}
</script>

<style lang="scss" scoped>
.input-matching {
	width: 100%;
	position: relative;
	input {
		height: 35px;
		&.matching {
			border: 1px solid #c4c4c4;
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
				line-height: 30px;
				&:hover{
					background-color: #ececec;
				}
				.matchItem {
					display: block;
					color: #717171;
				}                                                       
			}
		}
	}
}
</style>