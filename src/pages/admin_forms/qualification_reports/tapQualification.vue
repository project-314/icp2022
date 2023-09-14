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
      <q-tab
        name="FormQualification"
        icon="assignment_turned_in"
        label="ฟอร์มคุณสมบัติ/ทักษะ"
      />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานคุณสมบัติ/ทักษะ"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormQualification">
        <form-qualification />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormQualification from "../FormQualification.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormQualification,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormQualification"),
    };
  },
};
</script>

<style></style>
