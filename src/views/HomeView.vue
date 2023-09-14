<template>
  <div id="HomeView">
    <div class="home">
      <div>
        <img alt="ICP logo" src="../assets/icp.png" />
        <HelloWorld msg="โครงการการวางแผนอาชีพรายบุคคล" />
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from "@/components/HelloWorld.vue";

export default {
  name: "HomeView",
  components: {
    HelloWorld,
  },
};
</script>

<style scoped>
img {
  width: 160px;
  height: 100px;
}
</style>
