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
      <q-tab name="project" icon="description" label="โตรงการ" />
      <q-tab name="disability" icon="accessible" label="ความพิการ" />
      <q-tab name="target" icon="check_circle" label="ค่าเป้าหมาย" />
      <q-tab name="improvement" icon="fact_check" label="ค่าการประเมิน" />
      <q-tab name="level" icon="verified_user" label="ระดับความสำคัญ" />
      <q-tab name="importance" icon="privacy_tip" label="ความสำคัญ" />
      <q-tab name="career_group" icon="assignment" label="กลุ่มอาชีพ" />
      <q-tab
        name="qualification_group"
        icon="pending_actions"
        label="กลุ่มคุณสมบัติ/ทักษะ"
      />
    </q-tabs>
    <q-separator />
    <q-tab-panels v-model="tab" animated>
      <q-tab-panel name="project">
        <project-form />
      </q-tab-panel>
      <q-tab-panel name="disability">
        <disability-form />
      </q-tab-panel>
      <q-tab-panel name="target">
        <target-form />
      </q-tab-panel>
      <q-tab-panel name="improvement">
        <improvement-form />
      </q-tab-panel>
      <q-tab-panel name="level">
        <level-form />
      </q-tab-panel>
      <q-tab-panel name="importance">
        <importance-form />
      </q-tab-panel>
      <q-tab-panel name="career_group">
        <careergroup-form />
      </q-tab-panel>
      <q-tab-panel name="qualification_group">
        <qualificationgroup-form />
      </q-tab-panel>
    </q-tab-panels>
  </q-card>
</template>

<script>
import { ref } from "vue";
import ProjectForm from "./ProjectForm.vue";
import DisabilityForm from "./DisabilityForm.vue";
import TargetForm from "./TargetForm.vue";
import LevelForm from "./LevelForm.vue";
import ImprovementForm from "./ImprovementForm.vue";
import ImportanceForm from "./ImportanceForm.vue";
import CareergroupForm from "./CareergroupForm.vue";
import QualificationgroupForm from "./QualificationgroupForm.vue";

export default {
  components: {
    ProjectForm,
    DisabilityForm,
    TargetForm,
    LevelForm,
    ImprovementForm,
    ImportanceForm,
    CareergroupForm,
    QualificationgroupForm,
  },
  Provide() {
    return {};
  },
  setup() {
    return {
      tab: ref("project"),
      // innerTab: ref("innerMails"),
      // splitterModel: ref(20),
    };
  },
};
</script>

<style></style>
