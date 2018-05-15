
<template>
  <div class="others_admin">
    <multiselect
      v-model="multiValue"
      :options="options"
      :multiple="true"
  	  placeholder="请选择"
  	  label="name"
  	  track-by="id"	  
	  @input="updateSelected"
      >
    </multiselect>
    <input name="uids" type="hidden">
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";

export default {
	name: "UserSelect",

	props: ["users", "api"],

	computed: {
		apiUrl() {
			return this.api ? this.api : "/api/user/editors";
		}
	},

	components: { Multiselect },

	mounted() {
		if (this.users) {
			//加载选择的用户
			var data = JSON.parse(this.users);
			this.multiValue = [];
			for (var i in data) {
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
			window.axios.get(this.apiUrl).then(function(response) {
				_this.options = response.data.data;
			});
		},
		updateSelected(val) {
			$('input[name="uids"]').val(JSON.stringify(this.multiValue));
		}
	},

	data() {
		return {
			multiValue: null,
			options: [1, 2, 3, 4, 5, 6, 7, 8, 9]
		};
	}
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">
</style>

<style lang="scss" scoped>
.others_admin {
	display: block !important;
}
</style>
