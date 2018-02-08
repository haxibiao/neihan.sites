<template>
	<div class='input_box input-matching' ref="input-matching">
		<input :name="name" :class="['form-control',question&&matchedData?'matching':'']" type="text" placeholder="请输入问题(不超过40字)" v-model="question" @input="inputQuestion" autocomplete="off">
		<div class="matched-wrap" v-show="question&&matchedData">
			<h5>相似问题</h5>
			<ul class="matched">
				<li v-for="item in matchedData" @click="selectQuestion">
					<a :href="'/answer/'+item.id" class="matchItem">{{ item.text }}</a>
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
  		var vm = this;
  		var api=window.tokenize('/api/question/search');
  		window.axios.get(api,this.question).then(function(data){
  				// 因为没有api所以就凉了
  				vm.matchedData = data.matched;
  				console.log(vm.matchedData);
  			},function(error){
  				vm.matchedData = vm.simulationData;
  			}
  		)
  	},
  	selectQuestion(index) {
  		this.question = this.simulationData[index]
  	}
  },

  data () {
    return {
	  question: '',
	  matchedData:null,
	  simulationData:[
		{'id':'','text':'你要多喝水才行啊'},
		{'id':'','text':'怎么才能让你去多喝水'},
		{'id':'','text':'你要怎么样才去喝水'},
		{'id':'','text':'快点去喝水，最好是热水'},
		{'id':'','text':'我们怎么做到一天喝水喝到吐'},
		{'id':'','text':'喝水喝到什么程度才算厉害'},
		{'id':'','text':'你们今天喝水了吗'},
		{'id':'','text':'医生说没事多喝水'},
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