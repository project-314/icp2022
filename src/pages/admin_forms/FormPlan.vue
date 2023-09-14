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
                          @update:model-value="
                            (plan_career_id) => onQa_plan_career(plan_career_id)
                          "
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
                                onQa_plan_career_((plan_career = null))
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
                      <!-- คุณสมบัติ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          use-input
                          @filter="filterQa_plan_career"
                          color="blue-3"
                          v-model="qa_plan_career"
                          :options="qa_plan_careers.options"
                          label="คุณสมบัติ/ทักษะ *"
                          stack-label
                          @update:model-value="
                            (qa_plan_career) => onQaualification(qa_plan_career)
                          "
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:selected>
                            คุณสมบัติ:
                            <q-chip
                              v-if="qa_plan_career"
                              dense
                              square
                              color="white"
                              text-color="primary"
                              class="q-pa-xs"
                            >
                              {{
                                qa_plan_career.label != ""
                                  ? qa_plan_career.label
                                  : ""
                              }}
                            </q-chip>
                            <q-badge v-else>*none*</q-badge>
                          </template>
                          <template v-if="qa_plan_career" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="
                                onQaualification((qa_plan_career = null))
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
                    </div>
                    <!-- ระดับความสำคัญ/ค่าเป้าหมาย -->
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <q-input
                          type="textarea"
                          color="blue-3"
                          standout
                          bottom-slots
                          v-model="qa_plan_career_description"
                          label="ความสำคัญ/เป้าหมาย"
                          clearable
                          autogrow
                          :disable="true"
                        >
                          <template v-slot:prepend>
                            <q-icon name="play_lesson" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                    </div>
                    <!-- การพัฒนา ความสำคัญ ความถี่-->
                    <div class="row">
                      <!-- การพัฒนา -->
                      <div class="col-md-4 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterDevelopment"
                          use-input
                          color="green"
                          v-model="development_id"
                          :options="development.options"
                          label="การพัฒนา *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="post_add" />
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
                          <template v-if="development_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="development_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                      <!-- เรื่อง -->
                      <div class="col-md-8 col-xs-12 q-pa-xs">
                        <q-input
                          standout
                          bottom-slots
                          v-model="plan.plan_title"
                          label="เรื่อง *"
                          clearable
                        >
                          <template v-slot:prepend>
                            <q-icon name="list_alt" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                    </div>
                    <div class="row">
                      <!-- ช่องทาง -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          standout
                          bottom-slots
                          v-model="plan.plan_channel"
                          label="ช่องทาง *"
                          clearable
                        >
                          <template v-slot:prepend>
                            <q-icon name="play_lesson" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                      <!-- ความสำคัญ -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-select
                          @filter="filterImportance"
                          use-input
                          color="green"
                          v-model="importance_id"
                          :options="importance.options"
                          label="ความสำคัญ *"
                          emit-value
                          map-options
                        >
                          <template v-slot:prepend>
                            <q-icon name="saved_search" />
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
                          <template v-if="importance_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="importance_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                    </div>
                    <div class="row">
                      <!-- วันเริ่มพัฒนา -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          filled
                          v-model="plan.plan_start_date"
                          label="วันเริ่มพัฒนา *"
                          mask="##/##/####"
                          fill-mask
                          hint="วัน/เดือน/ปี ค.ศ.(DD/MM/YYYY)"
                          today-btn
                          clearable
                        >
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy
                                cover
                                transition-show="scale"
                                transition-hide="scale"
                              >
                                <q-date
                                  v-model="plan.plan_start_date"
                                  mask="DD/MM/YYYY"
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
                      <!-- วันสิ้นสุดพัฒนา -->
                      <!-- v-model="plan.plan_end_date" -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          filled
                          v-model="plan.plan_end_date"
                          label="วันสิ้นสุดพัฒนา"
                          mask="##/##/####"
                          fill-mask
                          hint="วัน/เดือน/ปี ค.ศ.(DD/MM/YYYY)"
                          today-btn
                          clearable
                        >
                          <template v-slot:append>
                            <q-icon name="event" class="cursor-pointer">
                              <q-popup-proxy
                                cover
                                transition-show="scale"
                                transition-hide="scale"
                              >
                                <q-date
                                  v-model="plan.plan_end_date"
                                  mask="DD/MM/YYYY"
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
                    </div>
                    <!-- ปุ่มควบคุม -->
                    <div class="row">
                      <div
                        class="col-md-12 col-xs-12 q-pa-xs row justify-center"
                      >
                        <!-- ปุ่มบันทึก/แก้ไข -->
                        <q-btn
                          :label="btnLabel"
                          type="submit"
                          color="primary"
                          icon="save"
                        />
                        <!-- ปุ่มยกเลิก -->
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
                        <!-- กลับฟอร์มกำหนดคุณสมบัติ/ทักษะ -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_previous"
                          label="กลับฟอร์มกำหนดคุณสมบัติ/ทักษะ"
                          to="/AdminFormQualification"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มกำหนดคุณสมบัติ/ทักษะ</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปฟอร์มรายงานการพัฒนาตนเอง -->
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_next"
                          label="ไปฟอร์มการประเมินตนเอง"
                          to="/AdminFormSelfAssessment"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มรายงานการพัฒนาตนเอง</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <!-- ตาราง -->
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <div class="q-pa-xs">
                          <q-table
                            title="ข้อมูลการพัฒนาตนเอง"
                            :rows="plans1"
                            :columns="columns"
                            row-key="id"
                            :filter="filter"
                            :loading="loading"
                            :visible-columns="visibleColumns"
                          >
                            <template v-slot:top-right="props">
                              <div class="row">
                                <div class="col-md-3 col-xs-6 q-pa-xs">
                                  <q-input
                                    borderless
                                    dense
                                    debounce="300"
                                    v-model="filter"
                                    placeholder="ค้นหาการพัฒนาตนเอง"
                                  >
                                    <template v-slot:append>
                                      <q-icon name="search" />
                                    </template>
                                  </q-input>
                                </div>
                                <!-- ส่งออก excel -->
                                <div class="col-md-3 col-xs-6 q-pa-xs">
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
                                </div>
                                <div class="col-md-2 col-xs-5 q-pa-xs">
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
                                <div class="col-md-1 col-xs-2 q-pa-xs">
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
                                  @click="onEdit(props.row.plan_id)"
                                ></q-btn>
                                <q-btn
                                  icon="delete"
                                  label="ลบ"
                                  @click="
                                    onDelete(
                                      props.row.plan_id,
                                      props.row.plan_title
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
  name: "FormPlan",
  data() {
    return {
      file_export: "",
      date: new Date(),
      url_api_plan: "",
      url_api_plan_career: "",
      url_api_qa_plan_career: "",
      qa_plan_career_description: "",
      // ------------------------------------------------------------------------------
      message: "Form Plan Career",
      title: "การพัฒนาตนเอง(ผู้ดูแลระบบ)",
      plan: {
        plan_id: "",
        plan_title: "",
        plan_channel: "",
        plan_start_date: "",
        plan_end_date: "",
      },
      isEdit: false,
      btnLabel: "เพิ่มข้อมูล",
      status: "บันทึก",
      level1: "",
      visibleColumns: ref([
        "actions",
        "plan_id",
        "member_id",
        "full_name",
        "qa_plan_career_id",
        "qualification_name",
        "development_id",
        "development_description",
        "plan_title",
        "plan_channel",
        "frequency_id",
        "frequency_name",
        "importance_id",
        "importance_name",
        "plan_start_date",
        "plan_end_date",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "plan_id",
          required: true,
          label: "รหัสแผนพัฒนาตนเอง",
          align: "center",
          field: (row) => row.plan_id,
          format: (val) => `${val}`,
          sortable: true,
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
        //   name: "qa_plan_career_id",
        //   label: "รหัสคุณสมบัติอาชีพ",
        //   align: "center",
        //   field: "qa_plan_career_id",
        //   sortable: true,
        // },
        {
          name: "qualification_name",
          label: "คุณสมบัติ",
          align: "left",
          field: "qualification_name",
          sortable: true,
        },
        // {
        //   name: "development_id",
        //   label: "รหัสการพัฒนา",
        //   align: "center",
        //   field: "development_id",
        //   sortable: true,
        // },
        {
          name: "development_description",
          label: "ชนิดการพัฒนา",
          align: "left",
          field: "development_name",
          sortable: true,
        },
        {
          name: "plan_title",
          label: "เรื่อง",
          align: "left",
          field: "plan_title",
          sortable: true,
        },
        {
          name: "plan_channel",
          label: "ช่องทาง",
          align: "left",
          field: "plan_channel",
          sortable: true,
        },
        // {
        //   name: "importance_id",
        //   label: "รหัสความสำคัญ",
        //   align: "center",
        //   field: "importance_id",
        //   sortable: true,
        // },
        {
          name: "importance_name",
          label: "ความสำคัญ",
          align: "center",
          field: "importance_name",
          sortable: true,
        },
        {
          name: "plan_start_date",
          label: "วันเริ่ม",
          align: "center",
          field: "plan_start_date",
          sortable: true,
        },
        {
          name: "plan_end_date",
          label: "วันสิ้นสุด",
          align: "center",
          field: "plan_end_date",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      plans1: [],
      qa_plan_careers_: {
        options: [],
      },
      qa_plan_careers: {
        options: [],
      },
      qa_plan_career: ref({
        label: "",
        value: "",
        description: "",
      }),
      qa_plan_career_id: "",
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
      }),
      development_id: "",
      development_: {
        options: [],
      },
      development: {
        options: [],
      },
      importance_id: "",
      importance_: {
        options: [],
      },
      importance: {
        options: [],
      },
      frequency_id: "",
      frequency_: {
        options: [],
      },
      frequency: {
        options: [],
      },
      member_id: this.$store.getters.myMember_id,
      plan_career_full_name: "",
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
    yearToDay(day_to_year) {
      var year_to_day = day_to_year.split("/");
      return year_to_day[2] + "/" + year_to_day[1] + "/" + year_to_day[0];
    },
    dayToYear(year_to_day) {
      if (typeof year_to_day == "undefined") year_to_day = "0000/00/00";
      var day_to_year = year_to_day.split("/");
      return day_to_year[2] + "/" + day_to_year[1] + "/" + day_to_year[0];
    },
    //--------------------
    resetForm() {
      console.log("ยกเลิก");
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      this.qa_plan_career_id = "";
      this.plan_career_id = "";
      this.development_id = "";
      this.importance_id = "";
      this.frequency_id = "";
      this.plan.plan_title = "";
      this.plan.plan_channel = "";
      this.plan.plan_start_date = "";
      this.plan.plan_end_date = "";
      this.plan_career_full_name = "";
    },
    submitForm() {
      var start_date = this.yearToDay(this.plan.plan_start_date);
      var end_date = this.yearToDay(this.plan.plan_end_date);
      if (!this.isEdit) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการเพิ่มข้อมูลหรือไม่?",
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            console.log("บันทึกการเพิ่มข้อมูล");
            const newPlan = {
              qa_plan_career_id: this.qa_plan_career.value,
              development_id: this.development_id,
              importance_id: this.importance_id,
              plan_title: this.plan.plan_title,
              plan_channel: this.plan.plan_channel,
              plan_start_date: start_date,
              plan_end_date: end_date,
            };
            this.$emit("saveData", newPlan);
            axios
              .post(this.url_api_plan, {
                action: "insert",
                qa_plan_career_id: this.qa_plan_career.value,
                development_id: this.development_id,
                importance_id: this.importance_id,
                plan_title: this.plan.plan_title,
                plan_channel: this.plan.plan_channel,
                plan_start_date: start_date,
                plan_end_date: end_date,
              })
              .then((res) => {
                console.log("insert", res.data);
                // this.resetForm();
                this.getUpdate();
              })
              .catch(function (error) {
                console.log(error);
              });
          });
      } else {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการแก้ไขข้อมูลหรือไม่?",
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            axios
              .post(this.url_api_plan, {
                action: "update",
                plan_id: this.plan.plan_id,
                qa_plan_career_id: this.qa_plan_career.value,
                development_id: this.development_id,
                importance_id: this.importance_id,
                plan_title: this.plan.plan_title,
                plan_channel: this.plan.plan_channel,
                plan_start_date: start_date,
                plan_end_date: end_date,
              })
              .then((response) => {
                console.log("Update", response.data);
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
    onEdit(plan_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "edit",
          plan_id: plan_id,
        })
        .then(function (response) {
          console.log("การพัฒนาต้นเอง:", response.data);
          self.plan.plan_id = response.data.plan_id;
          // ชื่อ-สกุล
          self.member.label = response.data.full_name;
          self.member.value = response.data.member_id;
          self.member.description = response.data.status;
          // อาชีพเป้าหมาย
          self.plan_career.value = response.data.plan_career_id;
          self.plan_career.label = response.data.career_name;
          self.plan_career.description = response.data.start_date;
          //คุณสมบัติตามอาชีพเป้าหมาย
          // self.getQa_plan_career(self.plan_career_id);
          self.qa_plan_career.value = response.data.qa_plan_career_id;
          self.qa_plan_career.label = response.data.qualification_name;
          self.qa_plan_career.description =
            "- " +
            response.data.level_description +
            "\n" +
            "- " +
            response.data.target_name;
          self.qa_plan_career_description =
            "- " +
            response.data.level_description +
            "\n" +
            "- " +
            response.data.target_name;
          // ชนิดการพัฒนา
          self.development_id = response.data.development_id;
          // ความสำคัญ
          self.importance_id = response.data.importance_id;
          // ความถี่
          self.frequency_id = response.data.frequency_id;
          // ชื่อเรื่องที่พัฒนา
          self.plan.plan_title = response.data.plan_title;
          // ช่องทางการพัฒนา
          self.plan.plan_channel = response.data.plan_channel;
          // วันเริ่มต้น
          self.plan.plan_start_date = self.dayToYear(
            response.data.plan_start_date
          );
          // วันสิ้นสุด
          self.plan.plan_end_date = self.dayToYear(response.data.plan_end_date);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    onDelete(plan_id, plan_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message: "คุณต้องการลบการพัฒนา [" + plan_name + "] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          console.log("delete:", plan_id);
          var self = this;
          axios
            .post(this.url_api_plan, {
              action: "delete",
              plan_id: plan_id,
            })
            .then(function (response) {
              console.log(response);
              // self.resetForm();
              self.getUpdate();
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    onNext() {
      this.$router.replace({ name: "FormSelfAssessment" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormQualification" });
    },
    getUpdate() {
      console.log(" แสดงข้อมูลการพัฒนาตนเอง สมาชิค: ");
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "getall_",
        })
        .then(function (res) {
          console.log("การพัฒนาตนเอง:", res.data);
          self.plans1 = res.data;
        })
        .finally(() => {
          self.loading = false;
        });
    },
    getDevelopment() {
      console.log(" แสดงข้อมูลการพัฒนา ");
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "getDevelopment",
        })
        .then(function (res) {
          var development_id = res.data.map((item) => item.development_id);
          var development_name = res.data.map((item) => item.development_name);
          self.development.options.splice(0);
          for (var i = 0; i < development_id.length; i++) {
            self.development.options.push({
              label: development_name[i],
              value: development_id[i],
            });
          }
          self.development_.options = self.development.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getImportance() {
      console.log(" แสดงข้อมูลด้านความสำคัญ ");
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "getImportance",
        })
        .then(function (res) {
          var importance_id = res.data.map((item) => item.importance_id);
          var importance_name = res.data.map((item) => item.importance_name);
          self.importance.options.splice(0);
          for (var i = 0; i < importance_id.length; i++) {
            self.importance.options.push({
              label: importance_name[i],
              value: importance_id[i],
            });
          }
          self.importance_.options = self.importance.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getFrequency() {
      console.log(" แสดงข้อมูลด้านความถี่ ");
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "getFrequency",
        })
        .then(function (res) {
          var frequency_id = res.data.map((item) => item.frequency_id);
          var frequency_name = res.data.map((item) => item.frequency_name);
          self.frequency.options.splice(0);
          for (var i = 0; i < frequency_id.length; i++) {
            self.frequency.options.push({
              label: frequency_name[i],
              value: frequency_id[i],
            });
          }
          self.frequency_.options = self.frequency.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterDevelopment(val, update) {
      if (val === "") {
        update(() => {
          this.development.options = this.development_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.development.options = this.development_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterImportance(val, update) {
      if (val === "") {
        update(() => {
          this.importance.options = this.importance_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.importance.options = this.importance_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterFrequency(val, update) {
      if (val === "") {
        update(() => {
          this.frequency.options = this.frequency_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.frequency.options = this.frequency_.options.filter(
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
    filterQualification(val, update) {
      if (val === "") {
        update(() => {
          this.qualification.options = this.qualification_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualification.options = this.qualification_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    getMember() {
      console.log("Get Member:");
      var self = this;
      axios
        .post(this.url_api_plan, {
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
    onMemberNames(member) {
      console.log("member_id:", member.value);
      console.log("full_name:", member.label);
      console.log("status:", member.description);
      this.getPlan_career(member.value);
    },
    getPlan_career(member_id) {
      console.log(" ข้อมูลอาชีพ สมาชิค:");
      var self = this;
      axios
        .post(this.url_api_plan_career, {
          action: "get_plan_career_by_member_id",
          member_id: member_id,
        })
        .then(function (res) {
          var plan_career_id = res.data.map((item) => item.plan_career_id);
          var career_name = res.data.map((item) => item.career_name);
          var start_date = res.data.map((item) => item.start_date);
          self.plan_careers.options.splice(0);

          for (var i = 0; i < plan_career_id.length; i++) {
            self.plan_careers.options.push({
              label: career_name[i],
              value: plan_career_id[i],
              description: start_date[i],
            });
          }
          self.plan_careers_.options = self.plan_careers.options;
        })
        .catch(function (error) {
          console.log(error);
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
    onQa_plan_career(plan_career_id) {
      console.log(" plan_career_id: ", plan_career_id.value);
      console.log(" plan_career_name: ", plan_career_id.label);
      console.log(" plan_career_start_date ", plan_career_id.description);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_plan_career_id",
          plan_career_id: plan_career_id.value,
        })
        .then(function (res) {
          console.log("แสดงข้อมูลคุณสมบัติของอาชีพ1:", res.data);
          var qa_plan_career_id = res.data.map(
            (item) => item.qa_plan_career_id
          );
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          var level_name = res.data.map((item) => item.level_description);
          var target_name = res.data.map((item) => item.target_name);
          self.qa_plan_careers.options.splice(0);

          for (var i = 0; i < qa_plan_career_id.length; i++) {
            self.qa_plan_careers.options.push({
              label: qualification_name[i],
              value: qa_plan_career_id[i],
              description: "- " + level_name[i] + "\n" + "- " + target_name[i],
            });
          }
          self.qa_plan_careers_.options = self.qa_plan_careers.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterQa_plan_career(val, update) {
      if (val === "") {
        update(() => {
          this.qa_plan_careers.options = this.qa_plan_careers_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qa_plan_careers.options = this.qa_plan_careers_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
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
    onQa_plan_career_(plan_career) {
      if (!plan_career) {
        this.plan_career = ref({
          label: "",
          value: "",
          description: "",
        });
        console.log("Plan_career:", plan_career);
      } else {
        console.log("Plan_career:", plan_career);
      }
    },
    onQaualification(qa_plan_career) {
      if (qa_plan_career) {
        this.qa_plan_career_description = qa_plan_career.description;
        console.log("qa_plan_career.description", this.description);
      } else {
        this.qa_plan_career = ref({
          label: "",
          value: "",
          description: "",
        });
        this.qa_plan_career_description = "";
        console.log("qa_plan_career.description", this.description);
      }
    },
  },
  mounted() {
    this.getMember();
    this.getUpdate();
    this.getDevelopment();
    this.getImportance();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_admin/plan_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url_api_plan = local_ + "api-plan.php";
      this.url_api_plan_career = local_ + "api-plan-career.php";
      this.url_api_qa_plan_career = local_ + "api-qa-plan-career.php";
    } else {
      this.url_api_plan = www_ + "api-plan.php";
      this.url_api_plan_career = www_ + "api-plan-career.php";
      this.url_api_qa_plan_career = www_ + "api-qa-plan-career.php";
    }
  },
  computed: {
    buddhistDate: {
      get() {
        if (this.date) {
          const date = new Date(this.date);
          date.setFullYear(date.getFullYear() + 543);
          return date;
        }
        return null;
      },
      set(value) {
        if (value) {
          const date = new Date(value);
          date.setFullYear(date.getFullYear() - 543);
          this.date = date;
        }
        this.date = null;
      },
    },
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
