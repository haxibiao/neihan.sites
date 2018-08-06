<template>
  <div>
      <button v-if="covers == false" type="button" class="btn btn-primary disabled" autocomplete="off" v-text="btnText">  
      </button>
      <div v-else class="col-xs-6 col-md-3" v-for="(item, index) of covers">
        <label :for="'cover'+index"><img :src="item" class="img img-responsive"></label>
        <input name="cover" type="radio" :value="item" :id="'cover'+index">
        <label :for="'cover'+index">选取</label> 
        <small class="text-danger"></small>
      </div>
  </div>
</template>

<script>
export default {
  name: "RequestCovers",

  props: ["api"],

  mounted() {
    this.getCovers();
  },

  methods:{
    getCovers(){
      var vm = this;
      var second = 0;
      vm.timer = setInterval(() => { 
        vm.btnText +='.'; 
        if(second %5 == 0){
          vm.btnText = '截图正在处理中';
        }
        if(second >= 30){
          window.axios.get(this.api).then(function(response) {
            if(response.data != false){
              vm.covers = response.data;
              clearInterval(vm.timer);
            }
          });
          second = 0;
        }
        second++;
        }, 1000);
    }
  },

  data(){
    return{
      covers:[],
      timer: null,
      btnText:'截图正在处理中'
    }
  }
};
</script>

<style lang="css" scoped>
</style>
