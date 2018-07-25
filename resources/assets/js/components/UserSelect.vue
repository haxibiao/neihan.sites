
<template>
  <div>
    <multiselect
      v-model="multiValue"
      :options="options"
      :multiple="true"
  	  placeholder="請選擇"
  	  label="name"
  	  track-by="id"	  
	  @input="updateSelected"
      >
    </multiselect>
    <input name="uids" type="hidden">
  </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

	export default {

    name: 'UserSelect',

    props: ['users', 'api'],

    computed: { 
    	apiUrl() { 
    		return window.tokenize(this.api ? this.api : '/api/user/editors');
    	}
    },

	components: { Multiselect },

	mounted() {

		if(this.users) {
			//加载选择的用户
			var data = JSON.parse(this.users);
			this.multiValue = [];
			for(var i in data) {
				this.multiValue.push({
					id: i,
					name: data[i]
				});
			}
			$('input[name="uids"]').val(JSON.stringify(this.multiValue));
		}

		this.fetchData();
	},

	methods: {
		fetchData() {
			//加载可选择用户列表
			var _this = this;
			window.axios.get(this.apiUrl).then(function(response){
				_this.options = response.data.data;
			});
		},
		updateSelected(val) {
			$('input[name="uids"]').val(JSON.stringify(this.multiValue));
		}
	},

	data () {
		return {
			multiValue: null,
			options: [1,2,3,4,5,6,7,8,9]
		}
	}
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style lang="scss">
	.multiselect__input,.multiselect__single {
    	position: relative;
        border: none;
        border-radius: 5px;
        padding: 0 0 0 5px;
        background-color: #fff;
	}
	.multiselect__tag {
		margin: 0 10px 5px 0 !important;
		background-color: #FFF;
		border: 1px solid #42c02e;
		color: #42c02e;
		.multiselect__tag-icon {
			&:hover {
				background-color: #42c02e;
			}
		}
	}
	.multiselect__option--highlight {
		background-color: rgba(#42c02e, 0.5);
		&:after {
			background-color: rgba(#42c02e, 0.5);
		}
	}
	.multiselect__option--selected.multiselect__option--highlight {
		background-color: #FF9999;
			&:after {
			background-color: #FF9999;
		}
	}
	.multiselect__option.multiselect__option--highlight,.multiselect__option.multiselect__option--selected {
		margin: 0 !important;
	}
</style>