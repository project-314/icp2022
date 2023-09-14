<script>
import SidebarLink from "./SidebarLink";
import { collapsed, toggleSidebar, sidebarWidth } from "./state";

export default {
  props: {},
  components: { SidebarLink },
  setup() {
    return { collapsed, toggleSidebar, sidebarWidth };
  },
};
</script>

<template>
  <div class="sidebar" :style="{ width: sidebarWidth }">
    <h1>
      <span v-if="collapsed">
        <div STYLE="font-size:30.0pt">I</div>
        <div STYLE="font-size:30.0pt">C</div>
        <div STYLE="font-size:30.0pt">P</div>
      </span>
      <span STYLE="font-size:30.0pt" v-else>ICPscore</span>
    </h1>
    <label for="myName">{{ $store.getters.myName }}</label>
    <SidebarLink to="/" icon="home">Home</SidebarLink>
    <SidebarLink to="/FormComponent" icon="contact_page">
      ข้อมูลส่วนตัว
    </SidebarLink>
    <SidebarLink to="/FormPlanCareer" icon="manage_accounts">
      อาชีพเป้าหมาย
    </SidebarLink>
    <SidebarLink to="/FormQualification" icon="how_to_reg">
      คุณสมบัติ/ทักษะ
    </SidebarLink>
    <SidebarLink to="/FormPlan" icon="edit_calendar">แผนเรียน/ทำ</SidebarLink>
    <SidebarLink to="/FormSelfAssessment" icon="domain_verification">
      ประเมินตนเอง
    </SidebarLink>
    <SidebarLink to="/FormPivotTable" icon="running_with_errors"
      >ผลการประเมินตนเอง</SidebarLink
    >
    <SidebarLink to="/FormBehavior" icon="running_with_errors"
      >ความก้าวหน้า</SidebarLink
    >
    <SidebarLink to="/FormProgress" icon="running_with_errors"
      >ความก้าวหน้า1</SidebarLink
    >

    <!-- "/FormBehavior" -->
    <!-- <SidebarLink to="/LoginPage1" icon="running_with_errors">Login</SidebarLink>
    <SidebarLink to="/LoginPage2" icon="running_with_errors"
      >Login2</SidebarLink
    >
    <SidebarLink to="/FormIndividual" icon="running_with_errors"
      >ข้อมูลส่วนตัว1</SidebarLink
    > -->
    <span
      class="collapse-icon"
      :class="{ 'rotate-180': collapsed }"
      @click="toggleSidebar"
    >
      <!-- <i class="fas fa-angle-double-left"></i> -->
      <q-icon name="keyboard_double_arrow_left" />
    </span>
  </div>
</template>

<style>
:root {
  --sidebar-bg-color: #2f855a;
  --sidebar-item-hover: #38a169;
  --sidebar-item-active: #276749;
}
</style>

<style scoped>
.sidebar {
  color: white;
  background-color: var(--sidebar-bg-color);

  float: left;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  bottom: 0;
  padding: 0.5em;

  transition: 0.3s ease;

  display: flex;
  flex-direction: column;
}

.sidebar h1 {
  height: 2em;
}

.collapse-icon {
  position: absolute;
  bottom: 0;
  padding: 0.75em;

  color: rgba(255, 255, 255, 0.7);

  transition: 0.2s linear;
}

.rotate-180 {
  transform: rotate(180deg);
  transition: 0.2s linear;
}
</style>
