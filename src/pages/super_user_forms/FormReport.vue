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
                  <q-form method="post" class="q-gutter-md">
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
                            (val) => onPlanCareerSelected(val)
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
                          v-model="qualification_id"
                          :options="qa_plan_career.options"
                          label="คุณสมบัติที่ต้องการ"
                          emit-value
                          map-options
                          @update:model-value="
                            (val) => onQualificationSelected(val)
                          "
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
                    <!-- การเปลี่ยนหน้า -->
                    <div class="row">
                      <!-- ปุ่มควบคุม -->
                      <div
                        class="col-md-12 col-xs-12 q-pa-xs row justify-center"
                      >
                        <!-- ออก -->
                        <q-btn
                          icon="logout"
                          label="ออก"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/"
                        />
                        <!-- กลับการพัฒนาตนเอง -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_previous"
                          label="กลับฟอร์มการพัฒนาตนเอง"
                          to="/SuserFormNotification"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปการประเมินตนเอง -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_next"
                          label="ไปฟอร์มการประเมินตนเอง"
                          to="/SuserFormPivotTable"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มรางานการประเมินตนเอง</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <!-- ตารางรายงานการประเมินตนเอง -->
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            class="my-sticky-header-table"
                            ref="myTable"
                            title="คุณสมบัติตามอาชีพเป้าหมาย"
                            :rows="qa_plan_careers"
                            :columns="main_columns"
                            row-key="qa_plan_career_id"
                            selection="multiple"
                            v-model:selected="selected"
                            :filter="filter"
                          >
                            <template v-slot:top-right>
                              <!-- ปุ่มค้นหา -->
                              <q-input
                                borderless
                                dense
                                debounce="300"
                                v-model="filter"
                                placeholder="ค้นหาข้อมูลส่วนตัว"
                              >
                                <template v-slot:append>
                                  <q-icon name="search" />
                                </template>
                              </q-input>
                              <!-- ส่งออก excel -->
                              <q-btn
                                flat
                                icon-right="archive"
                                label="ส่งออกไฟล์"
                                @click="exportTable()"
                              />
                            </template>
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
                            <template v-slot:body="props">
                              <q-tr :props="props">
                                <q-td auto-width>
                                  <q-toggle
                                    v-model="props.expand"
                                    checked-icon="add"
                                    unchecked-icon="remove"
                                    :label="`Index: ${props.row.qa_plan_career_id}`"
                                    @update:model-value="
                                      subRow(props.row.qa_plan_career_id)
                                    "
                                  />
                                </q-td>
                                <q-td
                                  v-for="col in props.cols"
                                  :key="col.name"
                                  :props="props"
                                >
                                  {{ col.value }}
                                </q-td>
                              </q-tr>
                              <q-tr v-show="props.expand" :props="props">
                                <q-td colspan="100%">
                                  <div class="text-left">
                                    รหัสคุณสมบัติตามอาชีพเป้าหมาย:
                                    {{ props.row.qa_plan_career_id }}
                                    <q-table
                                      class="my-sticky-header-table"
                                      title="การพัฒนาตนเอง"
                                      :rows="plans"
                                      :columns="columns"
                                      row-key="plan_id"
                                      selection="multiple"
                                      v-model:selected="selected_plan"
                                    >
                                    </q-table>
                                  </div>
                                </q-td>
                              </q-tr>
                            </template>
                          </q-table>
                        </div>
                      </div>
                    </div>
                    <!-- ตารางรายงานการประเมินตนเองรวม -->
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            class="my-sticky-header-table"
                            ref="myTable"
                            title="คุณสมบัติตามอาชีพเป้าหมาย"
                            :rows="qa_plan_careers_"
                            :columns="main_columns_"
                            row-key="qa_plan_career_id"
                            selection="multiple"
                            v-model:selected="selected"
                            :filter="filter"
                          >
                            <template v-slot:top-right>
                              <!-- ปุ่มค้นหา -->
                              <q-input
                                borderless
                                dense
                                debounce="300"
                                v-model="filter"
                                placeholder="ค้นหาข้อมูลส่วนตัว"
                              >
                                <template v-slot:append>
                                  <q-icon name="search" />
                                </template>
                              </q-input>
                              <!-- ส่งออก excel -->
                              <q-btn
                                flat
                                icon-right="archive"
                                label="ส่งออกไฟล์"
                                @click="exportTable1()"
                              />
                            </template>
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
                            <template v-slot:body="props">
                              <q-tr :props="props">
                                <q-td auto-width>
                                  <q-toggle
                                    v-model="props.expand"
                                    checked-icon="add"
                                    unchecked-icon="remove"
                                    :label="`Index: ${props.row.qa_plan_career_id}`"
                                    @update:model-value="
                                      subRow(props.row.qa_plan_career_id)
                                    "
                                  />
                                </q-td>
                                <q-td
                                  v-for="col in props.cols"
                                  :key="col.name"
                                  :props="props"
                                >
                                  {{ col.value }}
                                </q-td>
                              </q-tr>
                              <q-tr v-show="props.expand" :props="props">
                                <q-td colspan="100%">
                                  <div class="text-left">
                                    รหัสคุณสมบัติตามอาชีพเป้าหมาย:
                                    {{ props.row.qa_plan_career_id }}
                                    <q-table
                                      class="my-sticky-header-table"
                                      title="การพัฒนาตนเอง"
                                      :rows="plans"
                                      :columns="columns"
                                      row-key="plan_id"
                                      selection="multiple"
                                      v-model:selected="selected_plan"
                                    >
                                    </q-table>
                                  </div>
                                </q-td>
                              </q-tr>
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
  name: "FormPivotTable",
  components: {},
  data() {
    return {
      expansionsToggled: false,
      url_api_pivot: "",
      url: "",
      url_api_career_qualification: "",
      url_api_self_assessment: "",
      url_api_plan: "",
      url_api_plan_career: "",
      url_api_qa_plan_career: "",
      // ------------------------------------------------------------------------------
      title: "รายงานการพัฒนาตนเอง(ผู้ดูแลระบบ)",
      message: "Form Report",
      selected: ref([]),
      selected_plan: ref([]),
      columns: [
        {
          required: true,
          name: "plan_id",
          align: "center",
          label: "รหัสการพัฒนา",
          field: (row) => row.plan_id,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "qa_plan_career_id",
          align: "center",
          label: "รหัสคุณสมบัติอาชีพ",
          field: (row) => row.qa_plan_career_id,
          format: (val) => `${val}`,
        },
        {
          name: "development_id",
          align: "center",
          label: "รหัสชนิดการพัฒนา",
          field: (row) => row.development_id,
          format: (val) => `${val}`,
        },
        {
          name: "development_name",
          align: "center",
          label: "ชนิดการพัฒนา",
          field: (row) => row.development_name,
          format: (val) => `${val}`,
        },
        {
          name: "plan_title",
          align: "left",
          label: "เรื่อง",
          field: (row) => row.plan_title,
          format: (val) => `${val}`,
        },
        {
          name: "plan_channel",
          align: "left",
          label: "ช่องทาง",
          field: (row) => row.plan_channel,
          format: (val) => `${val}`,
        },
        {
          name: "importance_id",
          align: "center",
          label: "รหัสความสำคัญ",
          field: (row) => row.importance_id,
          format: (val) => `${val}`,
        },
        {
          name: "importance_name",
          align: "center",
          label: "ความสำคัญ",
          field: (row) => row.importance_name,
          format: (val) => `${val}`,
        },
        {
          name: "frequency_id",
          align: "center",
          label: "รหัสความถี่",
          field: (row) => row.frequency_id,
          format: (val) => `${val}`,
        },
        {
          name: "frequency_name",
          align: "center",
          label: "ความถี่",
          field: (row) => row.frequency_name,
          format: (val) => `${val}`,
        },
        {
          name: "plan_start_date",
          align: "center",
          label: "วันเริ่มต้น",
          field: (row) => row.plan_start_date,
          format: (val) => `${val}`,
        },
        {
          name: "plan_end_date",
          align: "center",
          label: "วันสิ้นสุด",
          field: (row) => row.plan_end_date,
          format: (val) => `${val}`,
        },
      ],
      main_columns: [
        {
          name: "qa_plan_career_id",
          align: "center",
          label: "รหัสคุณสมบัติอาชีพ",
          field: (row) => row.qa_plan_career_id,
          format: (val) => `${val}`,
        },
        {
          name: "member_id",
          align: "center",
          label: "รหัสสมาชิค",
          field: (row) => row.member_id,
          format: (val) => `${val}`,
        },
        {
          name: "full_name",
          align: "center",
          label: "ชื่อ-สกุล",
          field: (row) => row.full_name,
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
          name: "qualification_id",
          align: "center",
          label: "รหัสคุณสมบัติ",
          field: (row) => row.qualification_id,
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
          name: "level_id",
          align: "center",
          label: "รหัสระดับ",
          field: (row) => row.level_id,
          format: (val) => `${val}`,
        },
        {
          name: "level_description",
          align: "left",
          label: "ระดับ",
          field: (row) => row.level_description,
          format: (val) => `${val}`,
        },
      ],
      main_columns_: [
        {
          name: "qa_plan_career_id",
          align: "center",
          label: "รหัสคุณสมบัติอาชีพ",
          field: (row) => row.qa_plan_career_id,
          format: (val) => `${val}`,
        },
        {
          name: "member_id",
          align: "center",
          label: "รหัสสมาชิค",
          field: (row) => row.member_id,
          format: (val) => `${val}`,
        },
        {
          name: "full_name",
          align: "center",
          label: "ชื่อ-สกุล",
          field: (row) => row.full_name,
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
          name: "qualification_id",
          align: "center",
          label: "รหัสคุณสมบัติ",
          field: (row) => row.qualification_id,
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
          name: "level_id",
          align: "center",
          label: "รหัสระดับ",
          field: (row) => row.level_id,
          format: (val) => `${val}`,
        },
        {
          name: "level_description",
          align: "left",
          label: "ระดับ",
          field: (row) => row.level_description,
          format: (val) => `${val}`,
        },
        {
          name: "plan_title",
          align: "left",
          label: "ชื่อแผน",
          field: (row) => row.plan_title,
          format: (val) => `${val}`,
        },
        {
          name: "plan_channel",
          align: "left",
          label: "ช่องทาง",
          field: (row) => row.plan_channel,
          format: (val) => `${val}`,
        },
        {
          name: "plan_start_date",
          align: "left",
          label: "วันเริ่ม",
          field: (row) => row.plan_start_date,
          format: (val) => `${val}`,
        },
        {
          name: "plan_end_date",
          align: "left",
          label: "วันสิ้นสุด",
          field: (row) => row.plan_end_date,
          format: (val) => `${val}`,
        },
        {
          name: "development_name",
          align: "left",
          label: "การพัฒนา",
          field: (row) => row.development_name,
          format: (val) => `${val}`,
        },
        {
          name: "importance_name",
          align: "left",
          label: "ความสำคัญ",
          field: (row) => row.importance_name,
          format: (val) => `${val}`,
        },
      ],
      qualification_id: "",
      qa_plan_careers: [],
      qa_plan_careers_: [],
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
      plans: [],
      filter: ref(""),
    };
  },
  methods: {
    // นำออกไฟล์ excel
    exportTable() {
      console.log("Export excel");
      var columns = this.main_columns;
      var rows = this.qa_plan_careers;
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
    exportTable1() {
      console.log("Export excel");
      var columns = this.main_columns;
      var rows = this.qa_plan_careers_;
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

    // การเปลี่ยนตำแหน่งฟอร์ม
    onNext() {
      this.$router.replace({ name: "FormProgress" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormSelfAssessment" });
    },
    // ข้อมูลรายงานการประเมินตนเอง
    getUpdate() {
      console.log(" แสดงข้อมูลทั้งหมด ");
      var self = this;
      axios
        .post(this.url_api_self_assessment, {
          action: "getAll",
        })
        .then(function (res) {
          console.log("self_assessment:", res);
          self.plans = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    toggleExpansions() {
      console.log("toggleExpansions");
      this.expansionsToggled = !this.expansionsToggled;
      let rowArray = [];
      if (this.expansionsToggled) {
        rowArray = this.qa_plan_careers.map((row) => row.qa_plan_career_id);
        console.log("qa_plan_career_id:", rowArray);
        //depends on what your key is set up to be - here it is row.name
      } else {
        rowArray = [];
      }
      this.$refs.myTable.setExpanded(rowArray);
    },
    subRow(qa_plan_career_id) {
      console.log("qa_plan_career_id:", qa_plan_career_id);
      this.getPlanByQaPlanCareerId(qa_plan_career_id);
    },
    getCareer() {
      console.log(" ข้อมูลอาชีพ ");
      var self = this;
      axios
        .post(this.url_api_plan_career, {
          action: "get_plan_career",
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
    onPlanCareerSelected(val) {
      console.log("เลือกอาชีพเป้าหมาย:", val.label);
      console.log("รหัสอาชีพเป้าหมาย:", val.value);
      this.getQaPlanCareerByPlanCareerId();
    },
    // คุณสมบัติ
    getQaPlanCareerByPlanCareerId() {
      console.log(" แสดงข้อมูลคุณสมบัติ ");
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_plan_career_id",
          plan_career_id: this.plan_career_id,
        })
        .then(function (res) {
          console.log("qa_plan_careers:", res.data);
          self.qa_plan_careers = res.data;
          var qualification_id = res.data.map((item) => item.qualification_id);
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          var level_name = res.data.map((item) => item.level_name);
          var target_name = res.data.map((item) => item.target_name);
          self.qa_plan_career.options.splice(0);
          for (var i = 0; i < qualification_id.length; i++) {
            self.qa_plan_career.options.push({
              label: qualification_name[i],
              value: qualification_id[i],
              description: level_name[i] + ":" + target_name[i],
            });
          }
          self.qa_plan_career_.options = self.qa_plan_career.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getQaPlanCareer() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("qa_plan_careers:+", res.data);
          self.qa_plan_careers = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getQaPlanCareer_() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "qa_plan_career_plan",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("qa_plan_careers_:+", res.data);
          self.qa_plan_careers_ = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    onQualificationSelected(val) {
      console.log("เลือกคุณสมบัติ:", val.label);
      console.log("รหัสคุณสมบัติ:", val.value);
      this.getQaPlanCareerByQualificationId();
    },
    getQaPlanCareerByQualificationId() {
      console.log(
        " แสดงข้อมูลคุณสมบัติ plan_career_id/qualification_id:",
        this.plan_career_id.toString() + "/" + this.qualification_id.toString()
      );
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_qualification_id",
          plan_career_id: this.plan_career_id,
          qualification_id: this.qualification_id,
        })
        .then(function (res) {
          console.log("qa_plan_careers:", res.data);
          self.qa_plan_careers = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getPlanByQaPlanCareerId(qa_plan_career_id) {
      console.log(" แสดงข้อมูลการพัฒนาตนเอง: ", qa_plan_career_id);
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "get_plan_by_qa_plan_career_id",
          qa_plan_career_id: qa_plan_career_id,
        })
        .then(function (res) {
          console.log("plan:", res.data);
          self.plans = res.data;
        })
        .catch(function (error) {
          console.log(error);
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
  },
  mounted() {
    this.getQaPlanCareer_();
    this.getQaPlanCareer();
    this.getUpdate();
    this.getCareer();
  },
  created() {
    var www = this.$store.getters.myWWW;
    if (!www) {
      // ------------------------------------------------------------------------------
      this.url_api_pivot =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-pivot.php";
      this.url =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-member.php";
      this.url_api_career_qualification =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-qa-plan-career.php";
      this.url_api_self_assessment =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-self-assessment.php";
      this.url_api_plan =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-plan.php";
      this.url_api_plan_career =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-plan-career.php";
      this.url_api_qa_plan_career =
        "http://localhost:85/icp2022/icp_v1_suser/plan_report/api-qa-plan-career.php";
      // ------------------------------------------------------------------------------
      // ------------------------------------------------------------------------------
    } else {
      this.url_api_pivot =
        "https://icp2022.net/icp_v1_suser/plan_report/api-pivot.php";
      this.url = "https://icp2022.net/icp_v1_suser/plan_report/api-member.php";
      this.url_api_career_qualification =
        "https://icp2022.net/icp_v1_suser/plan_report/api-qa-plan-career.php";
      this.url_api_self_assessment =
        "https://icp2022.net/icp_v1_suser/plan_report/api-self-assessment.php";
      this.url_api_plan =
        "https://icp2022.net/icp_v1_suser/plan_report/api-plan.php";
      this.url_api_plan_career =
        "https://icp2022.net/icp_v1_suser/plan_report/api-plan-career.php";
      this.url_api_qa_plan_career =
        "https://icp2022.net/icp_v1_suser/plan_report/api-qa-plan-career.php";
    }
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
<style scoped>
.myclass td:hover:before {
  display: none;
}
</style>
