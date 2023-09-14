<template>
  <q-card>
    <q-tabs
      v-model="tab"
      dense
      class="bg-blue-2 text-purple"
      active-color="primary"
      indicator-color="blue-7"
      align="justify"
    >
      <q-tab name="institute" icon="assured_workload" label="สถาบัน" />
      <q-tab name="faculty" icon="home_work" label="คณะ" />
      <q-tab name="degree" icon="school" label="ปริญญา" />
      <q-tab name="department" icon="add_home" label="สาขา" />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="institute">
        <institute-form />
      </q-tab-panel>
      <q-tab-panel name="faculty">
        <faculty-form />
      </q-tab-panel>
      <q-tab-panel name="degree">
        <degree-form></degree-form>
      </q-tab-panel>
      <q-tab-panel name="department">
        <department-form />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import DepartmentForm from "./departmentForm.vue";
import FacultyForm from "./facultyForm.vue";
import DegreeForm from "./degreeForm.vue";
import InstituteForm from "./instituteForm.vue";
export default {
  components: { DepartmentForm, FacultyForm, DegreeForm, InstituteForm },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("institute"),
      // innerTab: ref("innerMails"),
      // splitterModel: ref(20),
    };
  },
};
</script>

<style></style>
