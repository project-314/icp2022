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
                    <!-- อาชีพเป้าหมาย + คุณสมบัติ-->
                    <div class="row">
                      <!-- แผนอาชีพ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterPlan_career"
                          use-input
                          color="green"
                          v-model="plan_career_id"
                          :options="plan_career.options"
                          label="อาชีพเป้าหมาย"
                          emit-value
                          map-options
                          @update:model-value="
                            (plan_career_id) => getQualification(plan_career_id)
                          "
                        >
                          <template v-slot:prepend>
                            <q-icon name="work_history" />
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
                      <!-- คุณสมบัติ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterQa_plan_career"
                          use-input
                          color="green"
                          v-model="qa_plan_career_id"
                          :options="qa_plan_career.options"
                          label="คุณสมบัติที่ต้องการ"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="fact_check" />
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
                    <!-- เดือนประเมิน -->
                    <div class="row">
                      <!-- วันประเมินตนเอง -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          filled
                          v-model="self_assessment_date"
                          label="วันประเมินตนเอง"
                        >
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy
                                cover
                                transition-show="scale"
                                transition-hide="scale"
                              >
                                <q-date
                                  v-model="self_assessment_date"
                                  mask="DD-MM-YYYY"
                                >
                                  <div class="row items-center justify-end">
                                    <q-btn
                                      v-close-popup
                                      label="Close"
                                      color="primary"
                                      flat
                                    />
                                  </div>
                                </q-date>
                              </q-popup-proxy>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <!-- ประเมินตนเอง -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterPerform"
                          use-input
                          color="green"
                          v-model="perform_id"
                          :options="perform.options"
                          label="ผลการพัฒนาตนเอง"
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
                        </q-select>
                      </div>
                    </div>
                    <!-- การอ้างอิง -->
                    <div class="row">
                      <!-- การอ้างอิง -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            class="my-sticky-header-table"
                            title="ข้อมูลผลงาน"
                            :rows="references1"
                            :columns="references"
                            row-key="reference_id"
                            :filter="filter_reference"
                            :loading="loading"
                          >
                            <!-- ค้นหา -->
                            <template v-slot:top-right>
                              <!-- ค้นหา -->
                              <div class="col-9">
                                <q-input
                                  borderless
                                  dense
                                  debounce="300"
                                  v-model="filter_reference"
                                  placeholder="ค้นหาผลงาน"
                                >
                                  <template v-slot:append>
                                    <q-icon name="search" />
                                  </template>
                                </q-input>
                              </div>
                              <!-- ปุ่มเพิ่มแหล่งอ้างอิง -->
                              <div class="col-5">
                                <q-btn
                                  round
                                  flat
                                  @click="show_dialog = true"
                                  icon="add_circle"
                                  label="เพิ่มผลงาน"
                                  no-caps
                                >
                                  <q-tooltip class="bg-accent"
                                    >เพิ่มแหล่งอ้างอิง/ผลงาน</q-tooltip
                                  >
                                </q-btn>
                              </div>
                              <!-- ปุ่มทดสอบการเพิ่มข้อมูลในฐานข้อมูล -->
                              <!-- <q-btn
                                round
                                flat
                                @click="submitRef()"
                                icon="add_circle"
                                no-caps
                              >
                                <q-tooltip class="bg-accent"
                                  >ทดสอบการเพิ่มข้อมูลในฐานข้อมูล</q-tooltip
                                >
                              </q-btn> -->
                              <!-- dialog เพิ่มข้อมูล -->
                              <div class="q-pa-sm q-gutter-sm">
                                <q-dialog v-model="show_dialog">
                                  <q-card>
                                    <q-card-section>
                                      <div class="text-h6">
                                        เพิ่มข้อมลแหล่งอ้างอิง/ผลงาน
                                      </div>
                                    </q-card-section>
                                    <q-card-section>
                                      <!-- ค้นหา -->
                                      <q-input
                                        v-model="
                                          editedReference.reference_description
                                        "
                                        label="แหล่งช้อมูลอ้างอิง/ผลงาน"
                                      ></q-input>
                                    </q-card-section>
                                    <q-card-actions align="right">
                                      <q-btn
                                        flat
                                        label="เพิ่มข้อมูล"
                                        color="primary"
                                        v-close-popup
                                        @click="addRow"
                                      ></q-btn>
                                    </q-card-actions>
                                  </q-card>
                                </q-dialog>
                              </div>
                              <!-- แหล่งข้อมูลอ้างอิง -->
                            </template>
                            <!-- แก้ไขข้อมูล + ลบข้อมูล -->
                            <template v-slot:body-cell-actions="props">
                              <q-td :props="props">
                                <!-- แก้ไขข้อมูล -->
                                <q-btn
                                  icon="mode_edit"
                                  @click="editItem(props.row)"
                                ></q-btn>
                                <!-- ลบข้อมูล -->
                                <q-btn
                                  icon="delete"
                                  @click="deleteItem(props.row)"
                                ></q-btn>
                              </q-td>
                            </template>
                          </q-table>
                        </div>
                      </div>
                    </div>
                    <!-- ปุ่มควบคุม -->
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
                          label="กลับฟอร์มรายงานการพัฒนาตนเอง"
                          no-caps
                          flat
                          icon="skip_previous"
                          to="/FormReport"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มรายงานการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปรายงานการประเมินตนเอง -->
                        <q-btn
                          color="primary"
                          label="ไปฟอร์มรายงานการประเมินตนเอง"
                          no-caps
                          flat
                          icon="skip_next"
                          to="/FormPivotTable"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มรายงานการประเมินตนเอง</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <!-- ตารางการประเมินตนเอง -->
                    <div class="row">
                      <!-- ตารางการประเมินตนเอง -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            ref="myTable"
                            class="my-sticky-header-table"
                            title="การประเมินตนเอง"
                            :rows="selfAssessments1"
                            :columns="main_columns"
                            row-key="self_assessment_id"
                            :filter="filter"
                            :loading="loading"
                            selection="multiple"
                            v-model:selected="selected"
                            style="min-height: 100vh"
                          >
                            <!-- การค้นหา -->
                            <template v-slot:top-right>
                              <div class="col-9">
                                <q-input
                                  borderless
                                  dense
                                  debounce="300"
                                  v-model="filter"
                                  placeholder="ค้นหาการประเมินตนเเอง"
                                >
                                  <template v-slot:append>
                                    <q-icon name="search" />
                                  </template>
                                </q-input>
                              </div>
                            </template>
                            <!-- ปุ่มลบ/ปุ่มแก้ไข -->
                            <template v-slot:body-cell-actions="props">
                              <q-td :props="props">
                                <q-btn
                                  icon="mode_edit"
                                  label="แก้ไข"
                                  @click="OnEdit(props.row.self_assessment_id)"
                                ></q-btn>
                                <q-btn
                                  icon="delete"
                                  label="ลบ"
                                  @click="
                                    onDelete(
                                      props.row.self_assessment_id,
                                      props.row.self_assessment_date
                                    )
                                  "
                                ></q-btn>
                              </q-td>
                            </template>
                            <!-- ปุ่ม +/- ส่วนหัว -->
                            <template v-slot:header="props">
                              <q-tr :props="props">
                                <q-th auto-width>
                                  <q-btn
                                    size="sm"
                                    color="secondary"
                                    round
                                    dense
                                    @click="toggleExpansions()"
                                    :icon="expansionsToggled ? 'remove' : 'add'"
                                  ></q-btn>
                                </q-th>
                                <q-th
                                  v-for="col in props.cols"
                                  :key="col.name"
                                  :props="props"
                                >
                                  {{ col.label }}
                                </q-th>
                              </q-tr>
                            </template>
                            <!-- end -->
                            <!-- ตารางย่อยข้อมูลอ้างอิง -->
                            <template v-slot:body="props">
                              <!-- ปุ่ม +/- แต่ละแถว -->
                              <q-tr :props="props">
                                <q-td auto-width>
                                  <q-toggle
                                    v-model="props.expand"
                                    checked-icon="add"
                                    unchecked-icon="remove"
                                    :label="`กดดูผลงาน: ${props.row.self_assessment_id}`"
                                    @update:model-value="
                                      subRow(props.row.self_assessment_id)
                                    "
                                  />
                                  <q-btn
                                    icon="mode_edit"
                                    label="แก้ไข"
                                    @click="
                                      OnEdit(props.row.self_assessment_id)
                                    "
                                  ></q-btn>
                                  <q-btn
                                    icon="delete"
                                    label="ลบ"
                                    @click="
                                      onDelete(
                                        props.row.self_assessment_id,
                                        props.row.self_assessment_date
                                      )
                                    "
                                  ></q-btn>
                                </q-td>
                                <q-td
                                  v-for="col in props.cols"
                                  :key="col.name"
                                  :props="props"
                                >
                                  {{ col.value }}
                                </q-td>
                              </q-tr>
                              <!-- ตารางย่อยข้อมูลอ้างอิง -->
                              <q-tr v-show="props.expand" :props="props">
                                <q-td colspan="100%">
                                  <div class="text-left">
                                    <q-table
                                      class="my-sticky-header-table"
                                      title="การพัฒนาตนเอง"
                                      :rows="references1"
                                      :columns="sub_columns"
                                      row-key="reference_id"
                                    >
                                      <template v-slot:body-cell="props1">
                                        <q-td
                                          key="perform_value"
                                          v-if="
                                            props1.row.self_assessment_id ==
                                            props.row.self_assessment_id
                                          "
                                          :props="props1"
                                          :class="
                                            props1.row.self_assessment_id ==
                                            props.row.self_assessment_id
                                              ? 'bg-indigo-5 text-white'
                                              : 'bg-white text-black'
                                          "
                                        >
                                          <div>{{ props1.value }}</div>
                                        </q-td>
                                      </template>
                                    </q-table>
                                  </div>
                                </q-td>
                              </q-tr>
                            </template>
                            <!-- end -->
                            <!-- การใส่สีผลการประเมิน-->
                            <!-- <template v-slot:body-cell="props">
                              <q-td
                                key="perform_value"
                                v-if="props.row.perform_value == 1"
                                :props="props"
                                :class="
                                  props.row.perform_value == 1
                                    ? 'bg-indigo-1 text-white'
                                    : 'bg-white text-black'
                                "
                              >
                                <div>{{ props.value }}</div>
                              </q-td>
                              <q-td
                                key="perform_value"
                                v-if="props.row.perform_value == 2"
                                :props="props"
                                :class="
                                  props.row.perform_value == 2
                                    ? 'bg-indigo-2 text-white'
                                    : 'bg-white text-black'
                                "
                              >
                                <div>{{ props.value }}</div>
                              </q-td>
                              <q-td
                                key="perform_value"
                                v-if="props.row.perform_value == 3"
                                :props="props"
                                :class="
                                  props.row.perform_value == 3
                                    ? 'bg-indigo-3 text-white'
                                    : 'bg-white text-black'
                                "
                              >
                                <div>{{ props.value }}</div>
                              </q-td>
                              <q-td
                                key="perform_value"
                                v-if="props.row.perform_value == 4"
                                :props="props"
                                :class="
                                  props.row.perform_value == 4
                                    ? 'bg-indigo-4 text-white'
                                    : 'bg-white text-black'
                                "
                              >
                                <div>{{ props.value }}</div>
                              </q-td>
                              <q-td
                                key="perform_value"
                                v-if="props.row.perform_value == 5"
                                :props="props"
                                :class="
                                  props.row.perform_value == 5
                                    ? 'bg-indigo-5 text-white'
                                    : 'bg-white text-black'
                                "
                              >
                                <div>{{ props.value }}</div>
                              </q-td>
                            </template> -->
                          </q-table>
                        </div>
                      </div>
                    </div>
                    <!-- end -->
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
  name: "FormSelfAssessment",
  data() {
    return {
      // ---------------------------------------------------------------------------------

      // ---------------------------------------------------------------------------------
      message: "Form Self Acessment",
      title: "การประเมินตนเอง",
      currentYear: new Date().getFullYear(),
      selfAssessments: Array,
      selfAssessments_: Array,
      careers: Array,
      career_qualifications: Array,
      member_id: this.$store.getters.myMember_id,
      planCareerId: "",
      selfAssessment: {
        selfAssessmentId: "",
        qualificationId: "",
        month: "",
        assessment: "",
        reference_id: "",
        isVisible: false,
      },
      isEdit: false,
      status: "บันทึก",
      qualification: {
        options: [],
      },
      btnLabel: "เพิ่มข้อมูล",
      pagination: ref({
        sortBy: "reference_description",
        descending: false,
        page: 1,
        rowsPerPage: 3,
      }),
      show_dialog: false,
      editedIndex: -1,
      references1_: [],
      references1: [],
      editedReference: {
        reference_description: "",
      },
      references: [
        { name: "actions", align: "left", label: "Action" },
        {
          name: "reference_id",
          align: "center",
          label: "รหัสผลงาน",
          field: (row) => row.reference_id,
          format: (val) => `${val}`,
        },
        {
          name: "self_assessment_id",
          align: "center",
          label: "รหัสการประเมินตนเอง",
          field: (row) => row.self_assessment_id,
          format: (val) => `${val}`,
        },
        {
          name: "reference",
          align: "left",
          label: "แหล่งอ้างอิง/ผลงาน",
          field: (row) => row.reference_description,
          format: (val) => `${val}`,
        },
      ],
      sub_columns: [
        {
          name: "reference_id",
          align: "center",
          label: "รหัสการอ้างอิง",
          field: (row) => row.reference_id,
          format: (val) => `${val}`,
        },
        {
          name: "self_assessment_id",
          align: "center",
          label: "รหัสการประเมินตนเอง",
          field: (row) => row.self_assessment_id,
          format: (val) => `${val}`,
        },
        {
          name: "reference_description",
          align: "left",
          label: "แหล่งอ้างอิง/ผลงาน",
          field: (row) => row.reference_description,
          format: (val) => `${val}`,
        },
      ],
      main_columns: [
        {
          name: "self_assessment_id",
          align: "center",
          label: "รหัสการประเมิน",
          field: (row) => row.self_assessment_id,
          format: (val) => `${val}`,
        },
        {
          name: "self_assessment_date",
          align: "center",
          label: "วันประเมิน",
          field: (row) => row.self_assessment_date,
          format: (val) => `${val}`,
        },
        {
          name: "career_id",
          align: "center",
          label: "รหัสอาชีพ",
          field: (row) => row.career_id,
          format: (val) => `${val}`,
        },
        {
          name: "career_name",
          align: "left",
          label: "อาชีพ",
          field: (row) => row.career_name,
          format: (val) => `${val}`,
        },
        {
          name: "qa_plan_career_id",
          align: "center",
          label: "รหัสคุณสมบัติ",
          field: (row) => row.qa_plan_career_id,
          format: (val) => `${val}`,
        },
        {
          name: "qualification_name",
          align: "left",
          label: "คุณสมบัติ",
          field: (row) => row.qualification_name,
          format: (val) => `${val}`,
        },
        {
          name: "target_id",
          align: "center",
          label: "รหัสเป้าหมาย",
          field: (row) => row.target_id,
          format: (val) => `${val}`,
        },
        {
          name: "target_name",
          align: "left",
          label: "เป้าหมาย",
          field: (row) => row.target_name,
          format: (val) => `${val}`,
        },
        {
          name: "target_value",
          align: "center",
          label: "ค่าเป้าหมาย",
          field: (row) => row.target_value,
          format: (val) => `${val}`,
        },
        {
          name: "perform_id",
          align: "center",
          label: "รหัสการประเมิน",
          field: (row) => row.perform_id,
          format: (val) => `${val}`,
        },
        {
          name: "perform_name",
          align: "left",
          label: "การประเมิน",
          field: (row) => row.perform_name,
          format: (val) => `${val}`,
        },
        {
          name: "perform_value",
          align: "center",
          label: "ผลการประเมิน",
          field: (row) => row.perform_value,
          format: (val) => `${val}`,
        },
        { name: "actions", align: "left", label: "Action" },
      ],
      selected: ref([]),
      selected_reference: ref([]),
      selfAssessments1: [],
      selfAssessments1_: [],
      qa_plan_career_id: "",
      qa_plan_career_: {
        options: [],
      },
      qa_plan_career: {
        options: [],
      },
      plan_career_id: "",
      plan_career_: {
        options: [],
      },
      plan_career: {
        options: [],
      },
      expansionsToggled: false,
      filter: ref(""),
      filter_reference: ref(""),
      loading: ref(false),
      self_assessment_date: "",
      self_assessment_id: "",
      perform_id: "",
      perform_: {
        options: [],
      },
      perform: {
        options: [],
      },
      $q: useQuasar(),
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

      const status = exportFile("individual.csv", "\ufeff" + content, {
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

    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";

      console.log("ยกเลิกการบันทึกข้อมูล");
      this.plan_career_id = "";
      this.qa_plan_career_id = "";
      this.self_assessment_date = "";
      this.perform_id = "";
    },
    getAllUser() {
      console.log(" แสดงข้อมูลทั้งหมด ");
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getall",
        })
        .then(function (res) {
          console.log(res);
          self.selfAssessments = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    submitRef() {
      if (!this.isEdit) {
        console.log("บันทึกข้อมูล ref");
        var length = this.selfAssessments1.length;
        var last_self_assessment_id =
          this.selfAssessments1[length - 1].self_assessment_id;
        console.log("self_assessment_id:", last_self_assessment_id);
        // const newReference = {
        //   self_assessment_id: last_self_assessment_id,
        //   references: this.references1_,
        // };
        // this.$emit("saveData", newReference);
        // axios
        //   .post(this.url_api_self_assessment, {
        //     action: "insert_reference",
        //     self_assessment_id: last_self_assessment_id,
        //     references: this.references1_,
        //   })
        //   .then((res) => {
        //     console.log("Insert:", res.data);
        //   })
        //   .catch(function (error) {
        //     console.log(error);
        //   });
      }
      // else {
      //   console.log("Update ข้อมูล ref");
      //   axios
      //     .post(this.url_api_self_assessment, {
      //       action: "update",
      //     })
      //     .then((response) => {
      //       console.log("Update:", response.data);
      //     })
      //     .catch(function (error) {
      //       console.log(error);
      //     });
      // }
    },
    submitForm() {
      if (!this.isEdit) {
        // if (this.confirm("คุณต้องการเพิ่มข้อมูลการประเมินตนเองหรือไม่ ?")) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการเพิ่มข้อมูลการประเมินตนเองหรือไม่ ?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            console.log("บันทึกข้อมูล");
            console.log("qualification:", this.selfAssessment);
            const newSelfAssessment = {
              self_assessment_date: this.self_assessment_date,
              qa_plan_career_id: this.qa_plan_career_id,
              perform_id: this.perform_id,
            };
            this.$emit("saveData", newSelfAssessment);
            axios
              .post(this.url_api_self_assessment, {
                action: "insert",
                self_assessment_date: this.self_assessment_date,
                qa_plan_career_id: this.qa_plan_career_id,
                perform_id: this.perform_id,
              })
              .then((res) => {
                console.log("Insert:", res.data);
                this.getUpdate(this.member_id);
                this.submitRef();
              })
              .catch(function (error) {
                console.log(error);
              });
          });
      } else {
        // if (this.confirm("คุณต้องการแก้ไขข้อมูลการประเมินตนเองหรือไม่ ?")) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการแก้ไขข้อมูลการประเมินตนเองหรือไม่ ?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            console.log("Update ข้อมูลการประเมินตนเอง");
            axios
              .post(this.url_api_self_assessment, {
                action: "update",
                self_assessment_id: this.self_assessment_id,
                qa_plan_career_id: this.qa_plan_career_id,
                perform_id: this.perform_id,
                self_assessment_date: this.self_assessment_date,
              })
              .then((response) => {
                console.log("update:", response.data);
                this.isEdit = false;
                console.log("isEdit:", this.isEdit);
                this.btnLabel = "เพิ่มข้อมูล";

                this.getUpdate(this.member_id);
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
    OnEdit(self_assessment_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      console.log("Edit", this.self_assessment_id);
      axios
        .post(this.url_api_self_assessment, {
          action: "edit",
          self_assessment_id: self_assessment_id,
        })
        .then(function (response) {
          console.log("Edit:", response.data);
          self.self_assessment_id = response.data.self_assessment_id;
          // อาชีพเป้าหมาย
          self.plan_career_id = response.data.plan_career_id;
          self.getQualification(self.plan_career_id);
          // คุณสมบัติตามอาชีพเป้าหมาย
          self.qa_plan_career_id = response.data.qa_plan_career_id;
          // ประเมินผลตนเอง
          self.perform_id = response.data.perform_id;
          // วันที่ประเมินผล
          self.self_assessment_date = response.data.self_assessment_date;
          // เอกสารอ้างอิง/ผลงาน
          self.getReferenceBySelf_Assessment_id(self.self_assessment_id);
        })
        .catch(function (error) {
          console.log(error);
        });
    },

    onDelete(self_assessment_id, self_assessment_date) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบผลการประเมินวัน " +
            self_assessment_date +
            " หรือไม่ ?",
          persistent: true,
          cancel: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_self_assessment, {
              action: "delete",
              self_assessment_id: self_assessment_id,
            })
            .then(function (response) {
              console.log(response);
              self.getUpdate(self.member_id);
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    onNext() {
      this.$router.replace({ name: "FormPivotTable" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormPlan" });
    },
    // checked
    getUpdate(member_id) {
      console.log(" แสดงข้อมูล การประเมินตนเอง ", member_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAll",
          member_id: member_id,
        })
        .then(function (res) {
          self.selfAssessments1 = res.data;
          self.selfAssessments1_ = res.data;
          console.log("self_assessment:", self.selfAssessments1);
          // var leng = self.selfAssessments1.length;
          // console.log(
          //   "self_assessment[last].self_assessment_id:",
          //   self.selfAssessments1[leng - 1].self_assessment_id
          // );
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getUpdateReference(member_id) {
      console.log(" แสดงข้อมูลการอ้างอิงทั้งหมด ", member_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAllReference",
          member_id: member_id,
        })
        .then(function (res) {
          self.references1 = res.data;
          console.log("reference:", self.references1);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // checked
    getCareer(member_id) {
      console.log(" ข้อมูลอาชีพ สมาชิค", member_id);
      var self = this;
      axios
        .post(this.url_api_plan_career, {
          action: "get_plan_career_by_member_id",
          member_id: member_id,
        })
        .then(function (res) {
          var plan_career_id = res.data.map((item) => item.plan_career_id);
          var career_name = res.data.map((item) => item.career_name);
          self.plan_career.options.splice(0);
          for (var i = 0; i < plan_career_id.length; i++) {
            self.plan_career.options.push({
              label: career_name[i],
              value: plan_career_id[i],
            });
          }
          self.plan_career_.options = self.plan_career.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // checked
    getQualification(plan_career_id) {
      console.log(" แสดงข้อมูลคุณสมบัติ รหัสแผนอาชีพ: ", plan_career_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_plan_career_id",
          plan_career_id: plan_career_id,
        })
        .then(function (res) {
          console.log("qa_plan_career:", res.data);
          var qa_plan_career_id = res.data.map(
            (item) => item.qa_plan_career_id
          );
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          var level_name = res.data.map((item) => item.level_description);
          var target_name = res.data.map((item) => item.target_name);
          self.qa_plan_career.options.splice(0);
          for (var i = 0; i < qa_plan_career_id.length; i++) {
            self.qa_plan_career.options.push({
              label: qualification_name[i],
              value: qa_plan_career_id[i],
              description: level_name[i] + " " + target_name[i],
            });
          }
          self.qa_plan_career_.options = self.qa_plan_career.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getPerform() {
      console.log(" ข้อมูลค่าเป้าหมาย ");
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getPerform",
        })
        .then(function (res) {
          var perform_id = res.data.map((item) => item.perform_id);
          var perform_name = res.data.map((item) => item.perform_name);
          self.perform.options.splice(0);
          for (var i = 0; i < perform_id.length; i++) {
            self.perform.options.push({
              label: perform_name[i],
              value: perform_id[i],
            });
          }
          self.perform_.options = self.perform.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterPerform(val, update) {
      if (val === "") {
        update(() => {
          this.perform.options = this.perform_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.perform.options = this.perform_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterQa_plan_career(val, update) {
      if (val === "") {
        update(() => {
          this.qa_plan_career.options = this.qa_plan_career_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qa_plan_career.options = this.qa_plan_career_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterPlan_career(val, update) {
      if (val === "") {
        update(() => {
          this.plan_career.options = this.plan_career_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.plan_career.options = this.plan_career_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    // start ตารางแหล่งอ้างอิง/ผลงาน
    addRow() {
      if (this.isEdit) {
        console.log(" เพิ่มข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
        var self = this;
        axios
          .post(this.url_api_self_assessment, {
            action: "add_reference_by_self_assessment_id",
            self_assessment_id: self_assessment_id,
          })
          .then(function (res) {
            console.log("reference:", res.data);
            self.references1 = res.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        if (this.editedIndex > -1) {
          Object.assign(
            this.references1[this.editedIndex],
            this.editedReference
          );
        } else {
          this.references1.push(this.editedReference);
          this.references1_.push(this.editedReference.reference_description);
          console.log(
            "references1:",
            this.editedReference.reference_description
          );
        }
        this.close();
      }
    },
    deleteItem(item) {
      if (this.isEdit) {
        var self_assessment_id = item.self_assessment_id;
        var reference_id = item.reference_id;
        console.log(
          " ลบข้อมูลรหัสการประเมินตนเอง/เอกสารอ้างอิง: ",
          self_assessment_id + "/" + reference_id
        );
        this.$q
          .dialog({
            title: "ยืนยัน",
            message:
              "คุณต้องการลบรหัสการประเมินตนเอง/ผลงาน " +
              self_assessment_id +
              "/" +
              reference_id +
              " หรือไม่ ?",
            persistent: true,
            cancel: true,
          })
          .onOk(() => {
            var self = this;
            axios
              .post(this.url_api_self_assessment, {
                action: "delete_reference_by_reference_id",
                reference_id: reference_id,
              })
              .then(function (res) {
                console.log("reference:", res.data);
                // self.references1 = res.data;
              })
              .catch(function (error) {
                console.log(error);
              });
          });
      } else {
        console.log("item:", item.reference_description);
        const index = this.references1.indexOf(item);
        confirm("คุณต้องการลบ: " + item.reference_description) &&
          this.references1.splice(index, 1);
      }
    },
    editItem(item) {
      if (this.isEdit) {
        console.log(" แก้ไขข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
        var self = this;
        axios
          .post(this.url_api_self_assessment, {
            action: "edit_reference_by_self_assessment_id",
            self_assessment_id: self_assessment_id,
          })
          .then(function (res) {
            console.log("reference:", res.data);
            self.references1 = res.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      } else {
        console.log("item:", this.references1.indexOf(item));
        this.editedIndex = this.references1.indexOf(item);
        console.log("object", Object.assign({}, item));
        this.editedReference = Object.assign({}, item);
        this.show_dialog = true;
      }
    },
    close() {
      this.show_dialog = false;
      setTimeout(() => {
        this.editedReference = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    // end
    // ตารางย่อยตารางอ้างอิง
    toggleExpansions() {
      console.log("toggleExpansions:", this.selfAssessments1);
      this.expansionsToggled = !this.expansionsToggled;
      let rowArray = [];
      if (this.expansionsToggled) {
        rowArray = this.selfAssessments1_.map((row) => row.self_assessment_id);
        console.log("self_assessment_id:", rowArray);
        this.getUpdateReference(this.member_id);
      } else {
        rowArray = [];
      }
      this.$refs.myTable.setExpanded(rowArray);
    },
    subRow(self_assessment_id) {
      console.log("self_assessment_id:", self_assessment_id);
      this.getReferenceBySelf_Assessment_id(self_assessment_id);
    },
    getReferenceBySelf_Assessment_id(self_assessment_id) {
      console.log(" แสดงข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "get_reference_by_self_assessment_id",
          self_assessment_id: self_assessment_id,
        })
        .then(function (res) {
          console.log("reference:", res.data);
          self.references1 = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getReferenceBySelf_Assessment_id1(self_assessment_id, expand) {
      if (expand) {
        console.log(" แสดงข้อมูลแหล่งอ้างอิง: ", self_assessment_id);
      } else {
        console.log("Not expand");
      }
      return self_assessment_id;
    },
    // end
  },
  mounted() {
    this.getUpdate(this.member_id);
    this.getCareer(this.member_id);
    this.getPerform();
  },

  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1/self_assessment_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;
    if (!www) {
      this.url = local_ + "api-member.php";
      this.url_api_career_qualification = local_ + "api-qa-plan-career.php";
      this.url_api_self_assessment = local_ + "api-self-assessment.php";
      this.url_api_plan = local_ + "api-plan.php";
      this.url_api_plan_career = local_ + "api-plan-career.php";
      this.url_api_qa_plan_career = local_ + "api-qa-plan-career.php";
    } else {
      this.url = www_ + "api-member.php";
      this.url_api_career_qualification = www_ + "api-qa-plan-career.php";
      this.url_api_self_assessment = www_ + "api-self-assessment.php";
      this.url_api_plan = www_ + "api-plan.php";
      this.url_api_plan_career = www_ + "api-plan-career.php";
      this.url_api_qa_plan_career = www_ + "api-qa-plan-career.php";
    }
  },
};
</script>

<style scoped></style>
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
