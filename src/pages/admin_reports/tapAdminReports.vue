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
        name="FormReportTable1"
        icon="content_paste_search"
        label="รายงานการประเมินตนเอง"
      />
      <q-tab
        name="FormReportTable2"
        icon="add_chart"
        label="กราฟการประเมินตนเอง"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="FormReportTable1">
        <form-report-table1 />
      </q-tab-panel>
      <q-tab-panel name="FormReportTable2">
        <form-report-table2 />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import FormReportTable1 from "./FormReportTable1.vue";
import FormReportTable2 from "./FormReportTable2.vue";

export default {
  components: {
    FormReportTable1,
    FormReportTable2,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("FormReportTable1"),
    };
  },
};
</script>

<style></style>
