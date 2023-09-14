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
      <q-tab name="FormPlan" icon="person_add_alt" label="ฟอร์มการพัฒนาตนเอง" />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานการพัฒนาตนเอง"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormPlan">
        <form-plan />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormPlan from "../FormPlan.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormPlan,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormPlan"),
    };
  },
};
</script>

<style></style>
