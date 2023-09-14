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
        name="FormRegistration"
        icon="averified"
        label="ฟอร์มการลงทะเบียน"
      />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานการลงทะเบียน"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormRegistration">
        <form-registration />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormRegistration from "../FormRegistration.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormRegistration,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormRegistration"),
    };
  },
};
</script>

<style></style>
