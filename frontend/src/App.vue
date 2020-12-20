<template>
  <div id="app">
    <Productivity />

    <Confid />
  </div>
</template>

<script>
import Productivity from './components/Productivity.vue'
import Confid from "@/components/Confid";

export default {
  name: 'App',
  components: {
    Confid,
    Productivity
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /*text-align: center;*/
  color: #2c3e50;
  margin-top: 60px;
}
</style>
