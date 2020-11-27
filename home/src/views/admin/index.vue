<template>
  <div class="dashboard-container">
    <component :is="currentRole" />
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import adminAdmin from './admin'
import editorAdmin from './editor'
export default {
  name: 'AdminIndex',
  components: { adminAdmin, editorAdmin },
  data() {
    return {
      currentRole: 'adminAdmin'
    }
  },
  computed: {
    ...mapGetters([
      'roles'
    ])
  },
  created() {
    // if (!this.roles.includes('admin')) {
    //   this.currentRole = 'editorAdmin'
    // }
  }
}
</script>

<style lang="scss" scoped>
</style>
