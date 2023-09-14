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
        name="FormComponent"
        icon="account_circle"
        label="ฟอร์มข้อมูลส่วนตัว"
      />
      <q-tab
        name="FormReportTable2"
        icon="assignment"
        label="รายงานข้อมูลส่วนตัว"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormComponent">
        <form-component />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormComponent from "../FormComponent.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormComponent,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormComponent"),
    };
  },
};
</script>

<style></style>
