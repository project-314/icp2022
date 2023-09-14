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
      <q-tab name="FormNotification" icon="alarm" label="ฟอร์มการแจ้งเตือน" />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานการแจ้งเตือน"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormNotification">
        <form-notification />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormNotification from "../FormNotification.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormNotification,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormNotification"),
    };
  },
};
</script>

<style></style>
