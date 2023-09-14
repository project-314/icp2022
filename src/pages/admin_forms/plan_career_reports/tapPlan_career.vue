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
      <q-tab name="FormPlanCareer" icon="work" label="ฟอร์มอาชีพเป้าหมาย" />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานอาชีพเป้าหมาย"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormPlanCareer">
        <form-plan-career />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormPlanCareer from "../FormPlanCareer.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormPlanCareer,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormPlanCareer"),
    };
  },
};
</script>

<style></style>
