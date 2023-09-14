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
        name="FormSelfAssessment"
        icon="manage_accounts"
        label="ฟอร์มการประเมินตนเอง"
      />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานการประเมินตนเอง"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormSelfAssessment">
        <form-self-assessment />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormSelfAssessment from "../FormSelfAssessment.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormSelfAssessment,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormSelfAssessment"),
    };
  },
};
</script>

<style></style>
