<template>
  <div id="app">
    <router-view v-if="isRouTerAlice" />
  </div>
</template>

<script>
export default {
  name: 'App',
  provide() {
    return {
      reload: this.reload
    }
  },
  data() {
    return {
      isRouTerAlice: true
    }
  },
  methods: {
    reload() {
      this.isRouTerAlice = false
      this.$nextTick(function() {
        this.isRouTerAlice = true
      })
    }
  }
}
</script>
