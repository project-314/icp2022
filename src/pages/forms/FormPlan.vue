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
                          label="อาชีพเป้าหมาย *"
                          emit-value
                          map-options
                          @update:model-value="
                            (plan_career_id) =>
                              getQa_plan_career(plan_career_id)
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
                          <template v-if="plan_career_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="plan_career_id = null"
                              class="cursor-pointer"
                            />
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
                          label="คุณสมบัติที่ต้องการ *"
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
                          <template v-if="qa_plan_career_id" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="qa_plan_career_id = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
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
                      <div class="col-md-12 col-xs-12 q-pa-xs">
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
                      <!-- การแจ้งเตือน -->
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
                                  :locale="mylocale"
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
                    <!-- ความสำคัญ -->
                    <div class="row">
                      <!-- ความสำคัญ -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
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
                          label="กลับฟอร์มกำหนดคุณสมบัติ"
                          no-caps
                          flat
                          icon="skip_previous"
                          to="/FormQualification"
                        >
                          <q-tooltip class="bg-accent"
                            >กลับฟอร์มกำหนดคุณสมบัติ/ทักษะ</q-tooltip
                          >
                        </q-btn>
                        <!-- ไปฟอร์มการประเมินตนเอง -->
                        <q-btn
                          color="primary"
                          label="ไปฟอร์มการประเมินตนเอง"
                          no-caps
                          flat
                          icon="skip_next"
                          to="/FormSelfAssessment"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มการประเมินตนเอง</q-tooltip
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
                            <!-- ค้นหา+ส่งออก excel -->
                            <template v-slot:top-right="props">
                              <!-- ค้นหา -->
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
                              <div class="chatbox-container">
                                <div class="container">
                                  <h1>Ai Chat Bot</h1>
                                  <div class="messageBox mt-8">
                                    <template
                                      v-for="(message, index) in messages"
                                      :key="index"
                                    >
                                      <div
                                        :class="
                                          message.from == 'user'
                                            ? 'messageFromUser'
                                            : 'messageFromChatGpt'
                                        "
                                      >
                                        <div
                                          :class="
                                            message.from == 'user'
                                              ? 'userMessageWrapper'
                                              : 'chatGptMessageWrapper'
                                          "
                                        >
                                          <div
                                            :class="
                                              message.from == 'user'
                                                ? 'userMessageContent'
                                                : 'chatGptMessageContent'
                                            "
                                          >
                                            {{ message.data }}
                                          </div>
                                        </div>
                                      </div>
                                    </template>
                                  </div>
                                  <div class="inputContainer">
                                    <input
                                      v-model="currentMessage"
                                      type="text"
                                      class="messageInput"
                                      placeholder="Ask me anything..."
                                    />
                                    <button
                                      @click="sendMessage(currentMessage)"
                                      class="askButton"
                                    >
                                      Ask
                                    </button>

                                    <div>
                                      <audio
                                        id="player"
                                        ref="player"
                                        :src="mySource"
                                        type="audio/mpeg"
                                        controls
                                        hidden
                                      ></audio>
                                      <canvas ref="canvas" />
                                    </div>
                                  </div>
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
      url_api_plan: "",
      url_api_plan_career: "",
      url_api_qa_plan_career: "",

      message: "Form Plan Career",
      title: "การพัฒนาตนเอง",
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
          label: "รหัสแผนพัฒนาตนเอง",
          align: "center",
          field: (row) => row.plan_id,
          format: (val) => `${val}`,
          required: true,
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
        //   name: "frequency_id",
        //   label: "รหัสความถี่",
        //   align: "center",
        //   field: "frequency_id",
        //   sortable: true,
        // },
        // {
        //   name: "frequency_name",
        //   label: "ความถี่",
        //   align: "center",
        //   field: "frequency_name",
        //   sortable: true,
        // },
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
      $q: useQuasar(),
      mylocale: {
        /* starting with Sunday */
        days: "อาทิตย์_จันทร์_อังคาร_พุธ_พฤหัส_ศุกร์_เสาร์".split("_"),
        daysShort: "อา_จ_อ_พ_พฤ_ศ_ส".split("_"),
        months:
          "มกราคม_กุมภาพันธ์_มีนาคม_เมษายน_พฤศภาคม_มิถุนายน_กรกฏาคม_สิงหาคม_กันยายน_ตุลาคม_พฤศจิกายน_ธันวาคม".split(
            "_"
          ),
        monthsShort: "มค_กพ_มีค_เมย_พค_มิย_กค_สค_กย_ตค_พย_ธค".split("_"),
        firstDayOfWeek: 1, // 0-6, 0 - Sunday, 1 Monday, ...
        format24h: true,
        pluralDay: "dias",
      },
    };
  },
  methods: {
    // นำออกไฟล์ excel
    exportTable() {
      console.log("Export excel");
      var columns = this.columns;
      var rows = this.plans1;
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
    //---------------------------------------
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
              qa_plan_career_id: this.qa_plan_career_id,
              development_id: this.development_id,
              importance_id: this.importance_id,
              frequency_id: this.frequency_id,
              plan_title: this.plan.plan_title,
              plan_channel: this.plan.plan_channel,
              plan_start_date: start_date,
              plan_end_date: end_date,
            };
            this.$emit("saveData", newPlan);
            axios
              .post(this.url_api_plan, {
                action: "insert",
                qa_plan_career_id: this.qa_plan_career_id,
                development_id: this.development_id,
                importance_id: this.importance_id,
                frequency_id: this.frequency_id,
                plan_title: this.plan.plan_title,
                plan_channel: this.plan.plan_channel,
                plan_start_date: start_date,
                plan_end_date: end_date,
              })
              .then((res) => {
                console.log("insert", res.data);
                // this.resetForm();
                this.getUpdate(this.member_id);
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
                qa_plan_career_id: this.qa_plan_career_id,
                development_id: this.development_id,
                importance_id: this.importance_id,
                frequency_id: this.frequency_id,
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
          // อาชีพเป้าหมาย
          self.plan_career_id = response.data.plan_career_id;
          //คุณสมบัติตามอาชีพเป้าหมาย
          self.getQa_plan_career(self.plan_career_id);
          self.qa_plan_career_id = response.data.qa_plan_career_id;
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
              self.getUpdate(self.member_id);
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
    // checked
    getUpdate(member_id) {
      console.log(" แสดงข้อมูลการพัฒนาตนเอง สมาชิค: ", member_id);
      var self = this;
      axios
        .post(this.url_api_plan, {
          action: "getall",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("การพัฒนาตนเอง:", res.data);
          self.plans1 = res.data;
        })
        .finally(() => {
          self.loading = false;
        });
    },
    // checked
    getCareer(member_id) {
      console.log(" ข้อมูลอาชีพ สมาชิค:", member_id);
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
    getQa_plan_career(plan_career_id) {
      console.log(" แสดงข้อมูลคุณสมบัติของอาชีพ ", plan_career_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qa_plan_career_by_plan_career_id",
          plan_career_id: plan_career_id,
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
    getDayly() {
      var current = new Date();
      console.log("current:", current);
      console.log("Date:", current.getDate());
      console.log("Day:", current.getDay());
      console.log("Month:", current.getMonth());
      console.log("Full Year:", current.getFullYear());
    },
  },
  mounted() {
    this.getUpdate(this.member_id);
    this.getCareer(this.member_id);
    this.getDevelopment();
    this.getImportance();
    this.getFrequency();
    this.getDayly();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1/plan_form/";
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
