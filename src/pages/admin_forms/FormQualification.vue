<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="bg-grey-2">
      <q-page
        padding
        class="items-center justify-center"
        style="background: linear-gradient(#74c588, #0ad13c)"
      >
        <div class="full-width">
          <div class="col-md-8 offset-md-2 col-xs-12 q-pa-xs">
            <q-card flat class="bg-white text-black">
              <q-card-section class="bg-blue-14">
                <h4 class="text-h5 text-white q-my-xs text-center">
                  {{ title }}
                </h4>
              </q-card-section>
              <div class="row">
                <div class="col-md-12 col-xs-12 q-pa-xs">
                  <q-form
                    @submit.prevent="submitForm"
                    @reset="resetForm"
                    method="post"
                    class="q-gutter-md"
                  >
                    <!-- รายชื่อ -->
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <q-select
                          use-input
                          @filter="filterMember"
                          color="blue-3"
                          v-model="member"
                          :options="members.options"
                          label="ชื่อ-สกุล *"
                          stack-label
                          @update:model-value="(val) => onMemberNames(val)"
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:selected>
                            ชื่อ-สกุล:
                            <q-chip
                              v-if="member"
                              dense
                              square
                              color="white"
                              text-color="primary"
                              class="q-pa-xs"
                            >
                              {{
                                member.label != ""
                                  ? member.label +
                                    " (" +
                                    member.description +
                                    ")"
                                  : ""
                              }}
                            </q-chip>
                            <q-badge v-else>*none*</q-badge>
                          </template>
                          <template v-if="member" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="onMember((member = null))"
                              class="cursor-pointer"
                            />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                        </q-select>
                      </div>
                    </div>
                    <!-- อาชีพเป้าหมาย + คุณสมบัติ-->
                    <div class="row">
                      <!-- แผนอาชีพ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          use-input
                          @filter="filterPlan_career"
                          color="blue-3"
                          v-model="plan_career"
                          :options="plan_careers.options"
                          label="อาชีพเป้าหมาย *"
                          stack-label
                          @update:model-value="onPlan_career(plan_career)"
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:selected>
                            อาชีพ:
                            <q-chip
                              v-if="plan_career"
                              dense
                              square
                              color="white"
                              text-color="primary"
                              class="q-pa-xs"
                            >
                              {{
                                plan_career.label != ""
                                  ? plan_career.label +
                                    " (" +
                                    plan_career.description +
                                    ")"
                                  : ""
                              }}
                            </q-chip>
                            <q-badge v-else>*none*</q-badge>
                          </template>
                          <template v-if="plan_career" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="
                                onPlan_career((plan_career = null))
                              "
                              class="cursor-pointer"
                            />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                        </q-select>
                      </div>
                      <!-- กลุ่มอาชีพ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          standout
                          bottom-slots
                          filled
                          v-model="ca_group_name"
                          label="กลุ่มอาชีพ"
                          clearable
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                      <!-- คุณสมบัติ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          use-input
                          @filter="filterQualification"
                          color="blue-3"
                          v-model="qualification"
                          :options="qualifications.options"
                          label="คุณสมบัติ/ทักษะ *"
                          stack-label
                          @update:model-value="onQaualification(qualification)"
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:selected>
                            คุณสมบัติ:
                            <q-chip
                              v-if="qualification"
                              dense
                              square
                              color="white"
                              text-color="primary"
                              class="q-pa-xs"
                            >
                              {{
                                qualification.label != ""
                                  ? qualification.label
                                  : ""
                              }}
                            </q-chip>
                            <q-badge v-else>*none*</q-badge>
                          </template>
                          <template v-if="qualification" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="
                                onQaualification((qualification = null))
                              "
                              class="cursor-pointer"
                            />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                        </q-select>
                      </div>
                      <!-- กลุ่มคุณสมบัติ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          standout
                          bottom-slots
                          filled
                          v-model="qualification_group_name"
                          label="กลุ่มคุณสมบัติ"
                          clearable
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                    </div>
                    <!-- ค่าเป้าหมาย + ระดับความสำคัญ-->
                    <div class="row">
                      <!-- ค่าเป้าหมาย -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterTarget"
                          use-input
                          color="blue-3"
                          v-model="target.options.value"
                          :options="target.options"
                          label="ค่าเป้าหมาย *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="flag_circle" />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                          <template v-if="target.options.value" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="target.options.value = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                      <!-- ระดับความสำคัญ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterLevel"
                          use-input
                          color="blue-3"
                          v-model="level.options.value"
                          :options="level.options"
                          label="ระดับความสำคัญ *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="running_with_errors" />
                          </template>
                          <template v-slot:option="scope">
                            <q-item v-bind="scope.itemProps">
                              <q-item-section avatar>
                                <q-icon :name="scope.opt.icon" />
                              </q-item-section>
                              <q-item-section>
                                <q-item-label>{{
                                  scope.opt.label
                                }}</q-item-label>
                                <q-item-label caption>{{
                                  scope.opt.description
                                }}</q-item-label>
                              </q-item-section>
                            </q-item>
                          </template>
                          <template v-if="level.options.value" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="level.options.value = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                    </div>
                    <div class="row">
                      <!-- ปุ่มควบคุม -->
                      <div
                        class="col-md-12 col-xs-12 q-pa-xs row justify-center"
                      >
                        <!-- บันทึก/แก้ไข -->
                        <q-btn
                          :label="btnLabel"
                          type="submit"
                          color="primary"
                          icon="save"
                        />
                        <!-- ยกเลิก -->
                        <q-btn
                          label="ยกเลิก"
                          type="reset"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          icon="clear"
                        />
                        <!-- ออก -->
                        <q-btn
                          icon="logout"
                          label="ออก"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/"
                        />
                        <!-- ย้อนกลับ -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_previous"
                          label="กลับฟอร์มกำหนดอาชีพเป้าหมาย"
                          to="/AdminFormPlanCareer"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มกำหนดอาชีพเป้าหมาย</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปข้างหน้า -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_next"
                          label="ไปฟอร์มการพัฒนาตนเอง"
                          to="/AdminFormPlan"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            title="ข้อมูลคุณสมบัติ/ทักษะ"
                            :rows="qualifications1"
                            :columns="columns"
                            row-key="skill"
                            :filter="filter"
                            :loading="loading"
                            :visible-columns="visibleColumns"
                          >
                            <template v-slot:top-right="props">
                              <div class="row">
                                <div class="col-md-5 col-xs-5 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="filter"
                                    placeholder="ค้นหาคุณสมบัติ"
                                  >
                                    <template v-slot:append>
                                      <q-icon name="search" />
                                    </template>
                                  </q-input>
                                </div>
                                <!-- ส่งออก excel -->
                                <!-- <div class="col-md-3 col-xs-6 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="file_export"
                                    placeholder="ชื่อไฟล์นำออก"
                                    outlined
                                  >
                                    <template v-slot:append>
                                      <q-icon name="save" />
                                    </template>
                                  </q-input>
                                </div>
                                <div class="col-md-3 col-xs-5 q-pa-xs">
                                  <q-btn
                                    flat
                                    icon-right="archive"
                                    label="ส่งออกไฟล์"
                                    @click="exportTable()"
                                  />
                                </div> -->
                                <div class="col-md-5 col-xs-5 q-pa-xs">
                                  <q-select
                                    v-model="visibleColumns"
                                    multiple
                                    outlined
                                    dense
                                    options-dense
                                    :display-value="$q.lang.table.columns"
                                    emit-value
                                    map-options
                                    :options="columns"
                                    option-value="name"
                                    options-cover
                                    style="min-width: 150px"
                                  />
                                </div>
                                <div class="col-md-2 col-xs-2 q-pa-xs">
                                  <q-btn
                                    flat
                                    round
                                    dense
                                    :icon="
                                      props.inFullscreen
                                        ? 'fullscreen_exit'
                                        : 'fullscreen'
                                    "
                                    @click="props.toggleFullscreen"
                                    class="q-ml-md"
                                  />
                                </div>
                              </div>
                            </template>
                            <template v-slot:body-cell-actions="props">
                              <q-td :props="props">
                                <q-btn
                                  icon="mode_edit"
                                  label="แก้ไข"
                                  @click="editUser(props.row.qa_plan_career_id)"
                                ></q-btn>
                                <q-btn
                                  icon="delete"
                                  label="ลบ"
                                  @click="
                                    onDelete(
                                      props.row.qa_plan_career_id,
                                      props.row.career_name,
                                      props.row.qualification_name
                                    )
                                  "
                                ></q-btn>
                              </q-td>
                            </template>
                          </q-table>
                        </div>
                      </div>
                    </div>
                  </q-form>
                </div>
              </div>
            </q-card>
          </div>
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
import { useQuasar } from "quasar";
import { exportFile } from "quasar";
// ส่งออกไฟล์ excel
function wrapCsvValue(val, formatFn, row) {
  let formatted = formatFn !== void 0 ? formatFn(val, row) : val;

  formatted =
    formatted === void 0 || formatted === null ? "" : String(formatted);

  formatted = formatted.split('"').join('""');
  /**
   * Excel accepts \n and \r in strings, but some other CSV parsers do not
   * Uncomment the next two lines to escape new lines
   */
  // .split('\n').join('\\n')
  // .split('\r').join('\\r')

  return `"${formatted}"`;
}

export default {
  name: "FormQualification",
  components: {},
  data() {
    return {
      file_export: "",
      url: "",
      url_api_career: "",
      url_api_plan_career: "",
      url_api_qualification: "",
      url_api_qa_plan_career: "",
      // ------------------------------------------------------------------------------
      message: "Form Qualification",
      title: "คุณสมบัติ/ทักษะ(ผู้ดูแลระบบ)",
      btnLabel: "เพิ่มข้อมูล",
      visibleColumns: ref([
        "actions",
        "qa_plan_career_id",
        "member_id",
        "full_name",
        "plan_career_id",
        "career_id",
        "career_name",
        "ca_group_name",
        "qualification_id",
        "qualification_name",
        "qualification_group_name",
        "level_id",
        "level_description",
        "target_id",
        "target_name",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "qa_plan_career_id",
          label: "รหัสคุณสมบัติอาชีพ",
          align: "center",
          field: (row) => row.qa_plan_career_id,
          format: (val) => `${val}`,
          sortable: true,
          required: true,
        },
        // {
        //   name: "member_id",
        //   label: "รหัสสมาชิค",
        //   align: "center",
        //   field: "member_id",
        //   sortable: true,
        // },
        {
          name: "full_name",
          label: "ชื่อ-สกุล",
          align: "left",
          field: "full_name",
          sortable: true,
        },
        // {
        //   name: "plan_career_id",
        //   label: "รหัสอาชีพเป้าหมาย",
        //   align: "center",
        //   field: "plan_career_id",
        //   sortable: true,
        // },
        // {
        //   name: "career_id",
        //   label: "รหัสอาชีพ",
        //   align: "center",
        //   field: "career_id",
        //   sortable: true,
        // },
        {
          name: "career_name",
          label: "อาชีพ",
          align: "left",
          field: "career_name",
          sortable: true,
        },
        // ca_group_name
        {
          name: "ca_group_name",
          label: "กลุ่มอาชีพ",
          align: "left",
          field: "ca_group_name",
          sortable: true,
        },
        // {
        //   name: "qualification_id",
        //   label: "รหัสคุณสมบัติ",
        //   align: "center",
        //   field: "qualification_id",
        //   sortable: true,
        // },
        {
          name: "qualification_name",
          label: "คุณสมบัติ",
          align: "left",
          field: "qualification_name",
          sortable: true,
        },
        {
          name: "qualification_group_name",
          label: "กลุ่มคุณสมบัติ",
          align: "left",
          field: "qualification_group_name",
          sortable: true,
        },
        // {
        //   name: "level_id",
        //   label: "รหัสระดับ",
        //   align: "center",
        //   field: "level_id",
        //   sortable: true,
        // },
        {
          name: "level_description",
          label: "ระดับ",
          align: "left",
          field: "level_description",
          sortable: true,
        },
        // {
        //   name: "target_id",
        //   label: "รหัสเป้าหมาย",
        //   align: "center",
        //   field: "target_id",
        //   sortable: true,
        // },
        {
          name: "target_name",
          label: "เป้าหมาย",
          align: "left",
          field: "target_name",
          sortable: true,
        },
      ],
      member_id: this.$store.getters.myMember_id,
      filter: ref(""),
      loading: ref(false),
      qa_plan_career_id: "",
      qa_plan_career_full_name: "",
      ca_group_name: "",
      qualification_group_name: "",
      qualifications1: [],
      qualifications_: {
        options: [],
      },
      qualifications: {
        options: [],
      },
      qualification: ref({
        label: "",
        value: "",
        description: "",
        qualification_group_name: "",
      }),
      // plan_careers: "",
      plan_careers_: {
        options: [],
      },
      plan_careers: {
        options: [],
      },
      plan_career: ref({
        label: "",
        value: "",
        description: "",
        group_name: "",
      }),
      targets: "",
      target_: {
        options: [],
      },
      target: {
        options: [],
      },
      levels: "",
      level_: {
        options: [],
      },
      level: {
        options: [],
      },
      $q: useQuasar(),
      members: {
        options: [],
      },
      members_: {
        options: [],
      },
      member: ref({
        label: "",
        value: "",
        description: "",
      }),
    };
  },
  methods: {
    // นำออกไฟล์ excel
    exportTable() {
      console.log("Export excel");
      var columns = this.columns;
      var rows = this.individuals1;
      // naive encoding to csv format
      const content = [columns.map((col) => wrapCsvValue(col.label))]
        .concat(
          rows.map((row) =>
            columns
              .map((col) =>
                wrapCsvValue(
                  typeof col.field === "function"
                    ? col.field(row)
                    : row[col.field === void 0 ? col.name : col.field],
                  col.format,
                  row
                )
              )
              .join(",")
          )
        )
        .join("\r\n");

      const status = exportFile(this.file_export, "\ufeff" + content, {
        encoding: "utf-8",
        mimeType: "text/csv;charset=utf-8;",
      });

      if (status !== true) {
        $q.notify({
          message: "Browser denied file download...",
          color: "negative",
          icon: "warning",
        });
      }
    },
    //---------------------------------------
    createValue1(val, done) {
      done(val, "add-unique");
      console.log("new val:", val);
    },
    newQualification(val, done) {
      done(val, "add-unique");
      console.log("val:", val);
      // if (confirm("คุณต้องการเพิ่มคุณสมบัติ [" + val + "] ใหม่หรือไม่ ?")) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message: "คุณต้องการเพิ่มคุณสมบัติ [" + val + "] ใหม่หรือไม่ ?",
          persistent: true,
          cancel: true,
        })
        .onOk(() => {
          var self = this;
          var member_id = Number(this.$store.getters.myMember_id);
          axios
            .post(this.url_api_qualification, {
              action: "insert",
              qualification_name: val,
              member_id: member_id,
            })
            .then(function (response) {
              console.log(response);
              // self.resetForm();
              // self.getUpdate();
              self.getQualification();
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      console.log("ยกเลิกการบันทึกข้อมูล");
      if (this.member) {
        this.member.value = "";
        this.member.label = "";
        this.member.description = "";
      }
      if (this.plan_career) {
        this.plan_career.value = "";
        this.plan_career.label = "";
        this.plan_career.description = "";
        this.plan_career.group_name = "";
      }
      if (this.qualification) {
        this.qualification.value = "";
        this.qualification.label = "";
        this.qualification.description = "";
      }
      if (this.target) {
        this.target.options.value = "";
        this.target.options.lebel = "";
      }
      if (this.level) {
        this.level.options.value = "";
        this.level.options.label = "";
      }
      this.qa_plan_career_full_name = "";

      this.getMember();
      this.getQualification();
      this.getTarget();
      this.getLevel();
    },
    getUpdateQualification() {
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getAll",
        })
        .then(function (res) {
          console.log("Update Qualification:", res.data);
          self.qualifications1 = res.data;
          console.log("getUpdate():", self.qualifications1);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    onMemberNames(member) {
      console.log("member_id:", member.value);
      console.log("full_name:", member.label);
      console.log("status:", member.description);
      this.getPlan_career(member.value);
    },
    getPlan_career(member_id) {
      console.log(" ข้อมูลอาชีพ สมาขิค:");
      var self = this;
      axios
        .post(this.url_api_plan_career, {
          action: "get_plan_career_by_member_id",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("get_plan_career_by_member_id:", res.data);
          var plan_career_id = res.data.map((item) => item.plan_career_id);
          var career_name = res.data.map((item) => item.career_name);
          var start_date = res.data.map((item) => item.start_date);
          var ca_group_name = res.data.map((item) => item.ca_group_name);

          self.plan_careers.options.splice(0);
          for (var i = 0; i < plan_career_id.length; i++) {
            self.plan_careers.options.push({
              label: career_name[i],
              value: plan_career_id[i],
              description: "วันเริ่ม:" + start_date[i],
              group_name: ca_group_name[i],
            });
          }
          self.plan_careers_.options = self.plan_careers.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getQualification() {
      console.log(" แสดงข้อมูลคุณสมบัติ ");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getCareerQualifiation",
        })
        .then(function (res) {
          console.log("Qualification:", res.data);
          var qualification_id = res.data.map((item) => item.qualification_id);
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          // var full_name = res.data.map((item) => item.full_name);
          var qualification_group_name = res.data.map(
            (item) => item.qualification_group_name
          );

          self.qualifications.options.splice(0);
          for (var i = 0; i < qualification_id.length; i++) {
            self.qualifications.options.push({
              label: qualification_name[i],
              value: qualification_id[i],
              // description: "ผู้สร้าง:" + full_name[i],
              qualification_group_name: qualification_group_name[i],
            });
          }
          console.log("qualifications:", self.qualifications.options);
          self.qualifications_.options = self.qualifications.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    submitForm() {
      if (!this.isEdit) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการเพิ่มข้อมูลหรือไม่?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            console.log("plan_career_id:", this.plan_career);
            console.log("qualification_id:", this.qualification);
            const newQualification = {
              plan_career_id: this.plan_career.value,
              qualification_id: this.qualification.value,
              level_id: this.level.options.value,
              target_id: this.target.options.value,
            };
            this.$emit("saveData", newQualification);
            axios
              .post(this.url_api_qa_plan_career, {
                action: "insert",
                plan_career_id: this.plan_career.value,
                qualification_id: this.qualification.value,
                level_id: this.level.options.value,
                target_id: this.target.options.value,
              })
              .then((res) => {
                console.log("insert:", res.data);
                this.getUpdate();
              })
              .catch(function (error) {
                console.log(error);
              });
          });
      } else {
        // if (this.confirm("คุณต้องการบันทึกการแก้ไขข้อมูลหรือไม่?")) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการแก้ไขข้อมูลหรือไม่?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            console.log("การแก้ไขข้อมูล");
            axios
              .post(this.url_api_qa_plan_career, {
                action: "update",
                qa_plan_career_id: this.qa_plan_career_id,
                plan_career_id: this.plan_career.value,
                qualification_id: this.qualification.value,
                level_id: this.level.options.value,
                target_id: this.target.options.value,
              })
              .then((response) => {
                console.log("Update:", response.data);
                this.isEdit = false;
                console.log("isEdit:", this.isEdit);
                this.btnLabel = "เพิ่มข้อมูล";
                this.getUpdate();
              })
              .catch(function (error) {
                console.log(error);
              });
          })
          .onCancel(() => {
            this.isEdit = false;
            console.log("isEdit:", this.isEdit);
            this.btnLabel = "เพิ่มข้อมูล";
          });
      }
    },
    editUser(qa_plan_career_id) {
      console.log("Edit data:qa_plan_career_id: ", qa_plan_career_id);
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "edit",
          qa_plan_career_id: qa_plan_career_id,
        })
        .then(function (response) {
          console.log("แก้ไข111111:", response.data);
          self.member.value = response.data.member_id;
          self.member.label = response.data.full_name;
          self.member.description = response.data.status;

          self.qa_plan_career_id = response.data.qa_plan_career_id;
          self.plan_career.value = response.data.plan_career_id;
          self.plan_career.label = response.data.career_name;
          self.plan_career.description = response.data.start_date;
          self.ca_group_name = response.data.ca_group_name;

          self.qualification.value = response.data.qualification_id;
          self.qualification.label = response.data.qualification_name;
          self.qualification.description = response.data.full_name;
          self.qualification_group_name =
            response.data.qualification_group_name;

          self.level.options.value = response.data.level_id;
          self.level.options.label = response.data.level_name;
          self.target.options.value = response.data.target_id;
          self.target.options.label = response.data.target_name;
          self.qa_plan_career_full_name = response.data.full_name;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    onDelete(qa_plan_career_id, career_name, qualification_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบคุณสมบัติ [ " +
            qualification_name +
            " ] อาชีพ [ " +
            career_name +
            " ] หรือไม่?",
          persistent: true,
          cancel: true,
        })
        .onOk(() => {
          console.log("delete:", qa_plan_career_id);
          var self = this;
          axios
            .post(this.url_api_qa_plan_career, {
              action: "delete",
              qa_plan_career_id: qa_plan_career_id,
            })
            .then(function (response) {
              console.log("delete:", response.data);
              self.getUpdate();
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    customFilter(rows, terms) {
      console.log(terms, rows);
      let lowerSearch = terms.search ? terms.search.toLowerCase : "";
      const filteredRows = rows.filter((row, i) => {
        let ans = false;
        let c1 = this.filterToggle.nice_to_have && row.level == "Nice to have";
        let c2 = this.filterToggle.must_have && row.level == "Must have";
        let c3 = this.filterToggle.optional && row.level == "Optional";
        console.log("c1:", c1);
        console.log("c2:", c2);
        console.log("c3:", c3);
        let s1 = true;
        if (lowerSearch != "") {
          s1 = false;
          let s1_values = Object.values(row);
          let s1_lower = s1_values.map((x) => x.toString().toLowerCase());
          for (let val = 0; val < s1_lower.length; val++) {
            if (s1_lower[val].includes(lowerSearch)) {
              s1 = true;
              break;
            }
          }
        }
        ans = false;
        if ((c1 && s1) || (c2 && s1) || (c3 && s1)) {
          ans = true;
        }
        return ans;
      });
      return filteredRows;
    },

    getUpdate() {
      console.log("ข้อมูลจาก qa_plan_career");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getAll_",
        })
        .then(function (res) {
          console.log("getUpdate:", res.data);
          self.qualifications1 = res.data;
          console.log("getUpdate:", self.qualifications1);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    getMember() {
      console.log("Get Member:");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getMember",
        })
        .then(function (res) {
          console.log("name:", res);
          var member_id = res.data.map((item) => item.member_id);
          var full_name = res.data.map((item) => item.full_name);
          var status = res.data.map((item) => item.status);
          self.members.options.splice(0);
          for (var i = 0; i < member_id.length; i++) {
            self.members.options.push({
              label: full_name[i],
              value: member_id[i],
              description: status[i],
            });
          }
          self.members_.options = self.members.options;
        })
        .finally(() => {
          self.loading = false;
        });
    },
    storeEmp_id(emp_id) {
      console.log("store Emp_id:", emp_id);
      this.$store.commit("setMyEmployee_id", emp_id);
      console.log("store employee_id", this.$store.getters.myEmployee_id);
    },
    onNext() {
      this.$router.replace({ name: "FormPlan" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    getTarget() {
      console.log(" ข้อมูลค่าเป้าหมาย ");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getTarget",
        })
        .then(function (res) {
          self.targets = res.data;
          console.log("target:", self.targets);
          var target_id = res.data.map((item) => item.target_id);
          var target_name = res.data.map((item) => item.target_name);
          self.target.options.splice(0);
          for (var i = 0; i < target_id.length; i++) {
            self.target.options.push({
              label: target_name[i],
              value: target_id[i],
            });
          }
          self.target_.options = self.target.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getLevel() {
      console.log(" ข้อมูลระดับความสำคัญ ");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "getLevel",
        })
        .then(function (res) {
          self.levels = res.data;
          console.log("level:", self.levels);
          var level_id = res.data.map((item) => item.level_id);
          var level_name = res.data.map((item) => item.level_description);
          self.level.options.splice(0);
          for (var i = 0; i < level_id.length; i++) {
            self.level.options.push({
              label: level_name[i],
              value: level_id[i],
            });
          }
          self.level_.options = self.level.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterLevel(val, update) {
      if (val === "") {
        update(() => {
          this.level.options = this.level_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.level.options = this.level_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterTarget(val, update) {
      if (val === "") {
        update(() => {
          this.target.options = this.target_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.target.options = this.target_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterQualification(val, update) {
      if (val === "") {
        update(() => {
          this.qualifications.options = this.qualifications_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualifications.options = this.qualifications_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterPlan_career(val, update) {
      if (val === "") {
        update(() => {
          this.plan_careers.options = this.plan_careers_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.plan_careers.options = this.plan_careers_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterMember(val, update) {
      if (val === "") {
        update(() => {
          this.members.options = this.members_.options;
          console.log("members_.options:", this.members_.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.members.options = this.members_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("members_.options:", this.members_.options);
      });
    },
    onMember(member) {
      if (!member)
        this.member = ref({
          label: "",
          value: "",
          description: "",
        });
    },
    onPlan_career(plan_career) {
      if (plan_career) {
        this.ca_group_name = plan_career.group_name;
        console.log("ca_group_name:", this.ca_group_name);
      } else {
        this.plan_career = ref({
          label: "",
          value: "",
          description: "",
          ca_group_name: "",
        });
        this.ca_group_name = "";
        console.log("ca_group_name:", this.ca_group_name);
      }
    },
    onQaualification(qualification) {
      if (qualification) {
        this.qualification_group_name = qualification.qualification_group_name;
        console.log(
          "qualification.description:",
          this.qualification_group_name
        );
      } else {
        this.qualification = ref({
          label: "",
          value: "",
          description: "",
          qualification_group_name: "",
        });
        this.qualification_group_name = "";
        console.log(
          "qualification.description:",
          this.qualification_group_name
        );
      }
    },
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_admin/qa_plan_career_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url = local_ + "api-member.php";
      this.url_api_career = local_ + "api-career.php";
      this.url_api_plan_career = local_ + "api-plan-career.php";
      this.url_api_qualification = local_ + "api-qualification.php";
      this.url_api_qa_plan_career = local_ + "api-qa-plan-career.php";
    } else {
      this.url = www_ + "api-member.php";
      this.url_api_career = www_ + "api-career.php";
      this.url_api_plan_career = www_ + "api-plan-career.php";
      this.url_api_qualification = www_ + "api-qualification.php";
      this.url_api_qa_plan_career = www_ + "api-qa-plan-career.php";
    }
  },
  mounted() {
    this.getMember();
    this.getQualification();
    this.getTarget();
    this.getLevel();
    this.getUpdate();
  },
};
</script>

<style lang="sass">
.my-sticky-header-table
  height: 310px
  .q-table__top,
  .q-table__bottom,
  thead tr:first-child th
    background-color: #c1f4cd
  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0
  &.q-table--loading thead tr:last-child th
    top: 48px
</style>
