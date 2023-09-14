<template>
  <div class="about">
    <img alt="ICP logo" src="../assets/icp.png" />
    <h1>ICP Scorecard คืออะไร</h1>
    <br />
    <h5>สมุดพกพาเพื่อการวางแผนอาชีพ ซึ่งเป็นเครื่องมือที่ช่วยให้นักศึกษาแต่ละคน สามารถวางแผนอาชีพและการพัฒนาทักษะ</h5>
    <br />
    <h5>ได้อย่างมืเ้ป้าหมายที่ชัดเจนและเป็นรูปธรรมมากขึ้น โดยสามารถวัดผลและเห็นความคืบหน้าได้</h5>
    <br />
    <h5>รวมถึงการนำไปสู่การพูดคุยกับพี่เลี้ยง หรือโค้ชด้านอาชีพได้อย่างมีประสิทธิภาพและมีประสิทธิผล</h5>
  </div>
  <!-- <div>
    <input type="radio" value="apple" v-model="selected" />
    <label>apple</label>
    <input type="radio" value="orange" v-model="selected" />
    <label>orange</label>
    <input type="radio" value="grape" v-model="selected" />
    <label>grape</label>
    <p>{{ selected }}</p>
  </div> -->
</template>

<script>
export default {
  data() {
    return {
      selected: "",
    };
  },
};
</script>
<style scoped>
img {
  width: 160px;
  height: 100px;
}
</style>