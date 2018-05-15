<template>
    <div class="modal fade modification-name">
        <div class="modal-dialog simple">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">请输入新文集名</h4>
                </div>
                <div class="modal-body">
                  <input type="text" class="input-style" v-model="name">
                </div>
                <footer class="clearfix">
                	<a class="submit btn-base btn-hollow btn-md" @click="submit">确认</a>
                	<a class="cancel" data-dismiss="modal" aria-label="Close" href="javascript:;">取消</a>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
export default {

  name: 'modificationName',

  computed: {
    name: {
      get() {
        return this.$store.state.currentCollection.name;
      },
      set(name) {
        this.newCollectionName = name;
      }
    }
  },

  methods: {
  	submit() {
      this.$store.commit('RENAME_COLLECTION', this.newCollectionName);
  		this.$store.dispatch('renameCollection', { name: this.newCollectionName });
  	}
  },

  data () {
    return {
      newCollectionName:null,
    }
  }
}
</script>

<style lang="scss">

</style>