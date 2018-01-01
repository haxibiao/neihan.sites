<template>
         <div class="col-md-12">
            <form @submit.prevent="updateing" class="form-horizontal">
                <div class="form-group">
                    <label for="name">
                        赛季名称:
                    </label>
                    <input class="form-control" name="name" type="text" v-model="compare.name">
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

                <input type="hidden" name="count" id="inputCount" class="form-control" :value="value">

            <div v-if="value" v-for="n in count" class="col-md-10">
                <div class="form-group">
                    <label for="teamname">
                        队伍{{ n }}名称:
                    </label>
                    <input class="form-control" :name="'teamname'+n" type="text" v-model="compare.teamname">
                    </input>
                </div>

                <div class="form-group">
                    <label for="member">
                        队伍{{ n }}成员(只限两名用,隔开)
                    </label>
                    <input class="form-control" :name="'member'+n" type="text" v-model="compare.member">
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
      updateing(){
         var vm=this;
         var author=window.current_user_name;
         var api='/compare/create';
         var formdata=new FormData();
          fromdata.append('name',vm.compare.name);
          fromdata.append('count',vm.value);
          fromdata.append('teamname',vm.compare.teamname);
          fromdata.append('member',vm.compare.name);
          fromdata.append('name',vm.compare.member);
          formdata.append('author',author);
         window.axios.post(api).then(function(response){
            // if(response.status ==200){
              
            //   }
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
        value:0,
        count:false,
        compare:[]
    }
  }
}
</script>

<style lang="css" scoped>
</style>