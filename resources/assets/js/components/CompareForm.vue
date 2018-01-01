<template>
         <div class="col-md-12">
            <form @submit.prevent="updateing" class="form-horizontal">
                <div class="form-group">
                    <label for="name">
                        赛季名称:
                    </label>
                    <input class="form-control" name="name" type="text" >
                    </input>
                </div>
                <div class="form-group">
                    <label for="email">
                        参赛队伍数量
                    </label>
                    <select id="count" required="required" name="count" class="form-control" v-model="value" @change="ChangeValue(value)">
                        <option value="4">4</option>
                        <option value="6">6</option>
                        <option value="8">8</option>
                    </select> 
                </div>
            <div v-if="value" v-for="n in count" class="col-md-10">
                <div class="form-group">
                    <label for="teamname">
                        队伍{{ n }}名称:
                    </label>
                    <input class="form-control" name="teamname" type="text" v-model="compare.teamname">
                    </input>
                </div>

                <div class="form-group">
                    <label for="member">
                        队伍{{ n }}成员(只限两名用,隔开)
                    </label>
                    <input class="form-control" name="member" type="text" v-model="compare.member">
                    </input>
                </div>
            </div>

            </form>
        </div>
</template>

<script>
export default {

  name: 'CompareForm',

  watch:{
      
  },

  methods:{
      fetchData(){
          var url='/compare/create';
          var vm=this;
          window.axios.get(url).then(function(response){
              vm.options = response.data;
          });
      },

      ChangeValue(value){
         if(value){
            var vm=this;
            var array=[1,2,3,4,5,6,7,8];
            vm.count =array.slice(0,value);
         }
      }
  },

  data () {
    return {
        value:false,
        count:false,
        compare:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>