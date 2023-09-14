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
                    <div class="q-pa-md">
                      <!-- <div class="row items-center q-gutter-sm q-mb-md">
                        <q-toggle
                          color="blue"
                          label="ผู้ดูแลระบบ"
                          v-model="admin"
                          val="admin"
                          @update:model-value="getUpdate()"
                        />
                        <q-toggle
                          color="green"
                          label="ผู้แลกลุ่ม"
                          v-model="suser"
                          val="suser"
                          @update:model-value="getUpdate()"
                        />
                        <q-toggle
                          color="red"
                          label="ผู้ใช้ระบบ"
                          v-model="user"
                          val="user"
                          @update:model-value="getUpdate()"
                        />
                      </div> -->
                      <q-table
                        ref="tbYear"
                        title="รายงานข้อมูลส่วนตัว"
                        :rows="individuals1"
                        :columns="columns"
                        virtual-scroll
                        v-model:pagination="pagination"
                        :rows-per-page-options="[0]"
                        row-key="name"
                        :filter="filter"
                        :loading="loading"
                      >
                        <template v-slot:top-right="props">
                          <div class="col-md-4 col-xs-4 q-pa-xs">
                            <q-input
                              borderless
                              dense
                              debounce="300"
                              v-model="filter"
                              placeholder="ค้นหาข้อมูลส่วนตัว"
                              outlined
                            >
                              <template v-slot:append>
                                <q-icon name="search" />
                              </template>
                            </q-input>
                          </div>
                          <div class="col-md-4 col-xs-4 q-pa-xs">
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
                          <div class="col-md-4 col-xs-4 q-pa-xs">
                            <q-btn
                              flat
                              icon-right="archive"
                              label="ส่งออกไฟล์"
                              @click="exportTable()"
                            />
                          </div>
                          <div class="col-md-4 col-xs-4 q-pa-xs">
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
                        </template>
                      </q-table>
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

import { ref } from "vue";
import axios from "axios";
import { exportFile } from "quasar";
// import VueApexCharts from "vue3-apexcharts";

export default {
  components: {
    // apexcharts: VueApexCharts,
  },
  data() {
    return {
      title: "รายงานข้อมูลส่วนตัว(ผู้ดูแลกลุ่ม)",
      filter: ref(""),
      loading: ref(false),
      url_api_pivot: "",
      url: "",
      url_api_career_qualification: "",
      url_api_self_assessment: "",
      url_api_plan: "",
      url_api_plan_career: "",
      url_api_qa_plan_career: "",
      main_year_columns: [
        {
          required: true,
          name: "member_id",
          align: "center",
          label: "รหัสคุณสมบัติ",
          field: (row) => row.member_id,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "full_name",
          align: "left",
          label: "ชื่อ-สกุล",
          field: "full_name",
          sortable: true,
        },
        {
          name: "Year",
          align: "center",
          label: "ปี",
          field: "Year",
          sortable: true,
        },
        {
          name: "career_name",
          align: "left",
          label: "อาชีพ",
          field: "career_name",
          sortable: true,
        },
        {
          name: "qualification_name",
          align: "left",
          label: "คุณสมบัติ",
          field: "qualification_name",
          sortable: true,
        },
        {
          name: "level_name",
          align: "left",
          label: "ระดับ",
          field: "level_name",
          sortable: true,
        },
        {
          name: "target_value",
          align: "center",
          label: "ค่าเป้าหมาย",
          field: "target_value",
          sortable: true,
        },
        {
          name: "min_day",
          align: "center",
          label: "ประเมินครั้งแรก",
          field: "min_day",
          sortable: true,
        },
        {
          name: "min_perform_value",
          align: "center",
          label: "ค่าการประเมิน",
          field: "min_perform_value",
          sortable: true,
        },
        {
          name: "max_day",
          align: "center",
          label: "ประเมินครั้งล่าสุด",
          field: "max_day",
          sortable: true,
        },
        {
          name: "max_perform_value",
          align: "center",
          label: "ค่าการประเมิน",
          field: "max_perform_value",
          sortable: true,
        },
      ],
      main_month_columns: [
        {
          required: true,
          name: "member_id",
          align: "center",
          label: "รหัสการประเมินตนเอง",
          field: (row) => row.member_id,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "full_name",
          align: "left",
          label: "ชื่อ-สกุล",
          field: "full_name",
          sortable: true,
        },
        {
          name: "Year",
          align: "center",
          label: "ปี",
          field: "Year",
          sortable: true,
        },
        {
          name: "Month",
          align: "left",
          label: "เดือน",
          field: "Month",
          sortable: true,
        },
        {
          name: "career_name",
          align: "left",
          label: "อาชีพ",
          field: "career_name",
          sortable: true,
        },
        {
          name: "qualification_name",
          align: "left",
          label: "คุณสมบัติ",
          field: "qualification_name",
          sortable: true,
        },
        {
          name: "level_name",
          align: "left",
          label: "ระดับ",
          field: "level_name",
          sortable: true,
        },
        {
          name: "target_value",
          align: "center",
          label: "ค่าเป้าหมาย",
          field: "target_value",
          sortable: true,
        },
        {
          name: "maxDay",
          align: "center",
          label: "ประเมินครั้งล่าสุด",
          field: "maxDay",
          sortable: true,
        },
        {
          name: "max_perform_value",
          align: "center",
          label: "ค่าการประเมิน",
          field: "max_perform_value",
          sortable: true,
        },
      ],
      columns: [
        // ข้อมูลส่วนตัว
        { name: "actions", align: "center", label: "Action" },
        {
          name: "individual_id",
          label: "รหัสข้อมูลส่วนตัว",
          align: "center",
          field: (row) => row.individual_id,
          format: (val) => `${val}`,
          required: true,
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
          name: "status",
          align: "left",
          label: "บทบาท",
          field: "status",
          sortable: true,
        },
        {
          name: "full_name",
          align: "left",
          label: "ชื่อ-สกุล",
          field: "full_name",
          sortable: true,
        },
        {
          name: "birthday",
          align: "center",
          label: "วันเกิด",
          field: "birthday",
        },
        {
          name: "telephone",
          align: "center",
          label: "โทรศัพท์",
          field: "telephone",
        },
        // ข้อมูลการศึกษา
        // {
        //   name: "institute_id",
        //   align: "center",
        //   label: "รหัสสถาบัน",
        //   field: "institute_id",
        // },
        {
          name: "institute_name",
          align: "left",
          label: "สถาบัน",
          field: "institute_name",
        },
        {
          name: "faculty_name",
          align: "center",
          label: "คณะ",
          field: "faculty_name",
        },
        {
          name: "degree_name",
          align: "center",
          label: "ระดับ",
          field: "degree_name",
        },
        // {
        //   name: "department_id",
        //   align: "center",
        //   label: "รหัสสาขา",
        //   field: "department_id",
        // },
        {
          name: "department_name",
          align: "center",
          label: "สาขา",
          field: "department_name",
        },
        {
          name: "is_graduate",
          align: "center",
          label: "จบการศึกษา",
          field: "is_graduate",
        },
        {
          name: "date",
          align: "center",
          label: "ปีที่สำเร็จการศึกษา",
          field: "date",

          sortable: true,
        },
        {
          name: "year",
          align: "center",
          label: "ปีที่กำลังศึกษา",
          field: "year",

          sortable: true,
        },
        // ข้อมูลความพิการ
        {
          name: "is_disability",
          align: "center",
          label: "ภาวะความพิการ",
          field: "is_disability",
        },
        // {
        //   name: "disability_id",
        //   align: "center",
        //   label: "รหัสความพิการ",
        //   field: "disability_id",
        // },
        {
          name: "disability_name",
          align: "left",
          label: "ความพิการ",
          field: "disability_name",
        },
        {
          name: "dis_describtion",
          align: "left",
          label: "รายละเอียดความพิการ",
          field: "dis_description",
        },
        // เข้าร่วมจากโครงการ
        // {
        //   name: "project_id",
        //   align: "center",
        //   label: "รหัสโครงการ",
        //   field: "project_id",
        // },
        {
          name: "project_name",
          align: "left",
          label: "โครงการ",
          field: "project_name",
        },
        // {
        //   name: "advisor_id",
        //   align: "center",
        //   label: "รหัสผู้ดูแลกลุ่ม",
        //   field: "advisor_id",
        // },
        {
          name: "advisor_name",
          align: "left",
          label: "ผู้ดูแลกลุ่ม",
          field: "advisor_name",
        },
      ],
      individual: {
        individual_id: "",
        // ข้อมูลส่วนตัว
        member_id: this.$store.getters.myMember_id,
        name: this.$store.getters.myName,
        birthday: "",
        // card_id: "",
        telephone: "",
        // ข้อมูลการศึกษา
        institute_id: 1,
        university: "",
        faculty_id: "",
        study_faculty: "",
        degree_id: "",
        degree: "",
        department_id: "",
        department: "",
        is_graduate: "0",
        year: "",
        date: "",
        // ข้อมูลความพิการ
        is_disability: "0",
        disability_id: "",
        disability_type: "",
        dis_description: "",
        // เข้าร่วมจากโครงการ
        project_id: "",
      },
      // กำหนดทางเลือกตาราง
      admin: ref(true),
      suser: ref(true),
      user: ref(true),
      // members1: [],
      individuals1: [],
      year_table: [],
      month_table: [],
      file_export: "",
      //----------------
      filterConditions: {
        // ข้อมูลส่วนตัว
        full_name: "",
        birthday: "",
        telephone: "",
        // ข้อมูลการศึกษา
        institute_name: "",
        faculty_name: "",
        degree_name: "",
        department_name: "",
        graduate_year: "",
        study_year: "",
        // ข้อมูลความพิการ
        disability_name: "",
        project_name: "",
        advisor_name: "",
        // ข้อมูลอาชีพ
        career_group_name: "",
        career_name: "",
        start_date: "",
        // ข้อมูลคุณสมบัติ
        qualification_group_name: "",
        qualification_name: "",
        qa_plan_career: "",
        target_name: "",
        level_name: "",
        // การพัฒนา
        development_name: "",
        plan_title: "",
        importance_name: "",
        plan_channel: "",
        plan_start_date: "",
        plan_end_date: "",
        // การประเมินตนเอง
        perform_name: "",
        self_assessment_date: "",
        year: "",
        month: "",
      },
      full_names_id: "",
      full_names: {
        options: [],
      },
      full_names_: {
        options: [],
      },
      career_names_id: "",
      career_names: {
        options: [],
      },
      career_names_: {
        options: [],
      },
      qualification_names_id: "",
      qualification_names: {
        options: [],
      },
      qualification_names_: {
        options: [],
      },
      years_id: "",
      years: {
        options: [],
      },
      years_: {
        options: [],
      },
      months_id: "",
      months: {
        options: [],
      },
      months_: {
        options: [],
      },
      //--------------------------
      chartOptions: {
        chart: {
          width: "100%",
          type: "line",
          stacked: false,
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          width: [1, 1, 4],
        },
        title: {
          text: "กราฟแสดงผลการพัฒนาตนเองรายปี",
          align: "left",
          offsetX: 110,
        },
        xaxis: {
          // categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016],
          categories: [],
        },
        yaxis: [
          {
            seriesName: "first",
            min: 0,
            max: 5,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#008FFB",
            },
            labels: {
              style: {
                colors: "#008FFB",
              },
            },
            title: {
              text: "ระดับการประเมินครั้งแรก",
              style: {
                color: "#008FFB",
              },
            },
            tooltip: {
              enabled: true,
            },
          },
          {
            seriesName: "final",
            min: 0,
            max: 5,
            opposite: true,

            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#00E396",
            },
            labels: {
              style: {
                colors: "#00E396",
              },
            },
            title: {
              text: "ระดับการประเมินครั้งล่าสุด",
              style: {
                color: "#00E396",
              },
            },
          },
          {
            seriesName: "target",
            min: 0,
            max: 5,
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#FEB019",
            },
            labels: {
              style: {
                colors: "#FEB019",
              },
            },
            title: {
              text: "ระดับค่าเป้าหมาย",
              style: {
                color: "#FEB019",
              },
            },
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 5,
            offsetX: 60,
          },
        },
        legend: {
          horizontalAlign: "left",
          offsetX: 40,
        },
      },
      series: [
        {
          name: "ประเมินครั้งแรก",
          type: "column",
          // data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6],
          data: [],
        },
        {
          name: "ประเมินครั้งล่าสุด",
          type: "column",
          data: [],
        },
        {
          name: "ค่าเป้าหมาย",
          type: "line",
          data: [],
        },
      ],
      chart_database: {
        categories: [],
        firsts: [],
        finals: [],
        targets: [],
      },
      //--------------------------
      chartOptions_: {
        chart: {
          width: "100%",
          type: "line",
          stacked: false,
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          width: [1, 1, 4],
        },
        title: {
          text: "กราฟแสดงผลการพัฒนาตนเองรายเดือน",
          align: "left",
          offsetX: 110,
        },
        xaxis: {
          // categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016],
          categories: [],
        },
        yaxis: [
          {
            seriesName: "first",
            min: 0,
            max: 5,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#008FFB",
            },
            labels: {
              style: {
                colors: "#008FFB",
              },
            },
            title: {
              text: "ระดับการประเมินครั้งแรก",
              style: {
                color: "#008FFB",
              },
            },
            tooltip: {
              enabled: true,
            },
          },
          {
            seriesName: "final",
            min: 0,
            max: 5,
            opposite: true,

            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#00E396",
            },
            labels: {
              style: {
                colors: "#00E396",
              },
            },
            title: {
              text: "ระดับการประเมินครั้งล่าสุด",
              style: {
                color: "#00E396",
              },
            },
          },
          {
            seriesName: "target",
            min: 0,
            max: 5,
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#FEB019",
            },
            labels: {
              style: {
                colors: "#FEB019",
              },
            },
            title: {
              text: "ระดับค่าเป้าหมาย",
              style: {
                color: "#FEB019",
              },
            },
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 5,
            offsetX: 60,
          },
        },
        legend: {
          horizontalAlign: "left",
          offsetX: 40,
        },
      },
      series_: [
        {
          name: "ประเมินครั้งแรก",
          type: "column",
          // data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6],
          data: [],
        },
        {
          name: "ประเมินครั้งล่าสุด",
          type: "column",
          data: [],
        },
        {
          name: "ค่าเป้าหมาย",
          type: "line",
          data: [],
        },
      ],
      chart_database_: {
        categories: [],
        firsts: [],
        finals: [],
        targets: [],
      },
    };
  },
  methods: {
    exportTable() {
      console.log("Export excel");
      var columns = this.columns;
      var rows = this.$refs.tbYear.filteredSortedRows;
      console.log("columns:", columns);
      console.log("rows:", rows);
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
    exportTable1() {
      console.log("Export excel");
      var columns = this.main_month_columns;
      var rows = this.$refs.tbMonth.filteredSortedRows;
      console.log("columns:", columns);
      console.log("rows:", rows);
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
    get_Year_QaPlanCareer() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_year_table",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("year_table:+", res.data);
          self.year_table = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    get_Month_QaPlanCareer() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_month_table",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("month_table:+", res.data);
          self.month_table = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    //----------------
    on_full_name_() {
      this.full_names_id = "";
      this.on_full_name("");
    },
    on_full_name(full_name) {
      console.log(" full_name:", full_name);
      this.filterConditions.full_name = full_name;
    },
    getFullName() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" full_name:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_full_name",
          // member_id: member_id,
        })
        .then(function (response) {
          console.log("FullName:", response.data);
          self.full_names.options = response.data.map((item) => ({
            label: item.full_name,
            value: item.full_name,
          }));
          self.full_names_.options = self.full_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterFull_name(val, update) {
      if (val === "") {
        update(() => {
          this.full_names.options = this.full_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.full_names.options = this.full_names_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_career_names_() {
      this.career_names_id = "";
      this.on_career_names("");
    },
    on_career_names(career_name) {
      console.log(" career:", career_name);
      this.filterConditions.career_name = career_name;
    },
    getCareer_name() {
      // var member_id = Number(this.$store.getters.myMember_id);
      // console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_career",
          // member_id: member_id,
        })
        .then(function (res) {
          console.log("career_name:", res.data);
          self.career_names.options = res.data.map((item) => ({
            label: item.career_name,
            value: item.career_name,
          }));
          self.career_names_.options = self.career_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterCareer_name(val, update) {
      if (val === "") {
        update(() => {
          this.career_names.options = this.career_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.career_names.options = this.career_names_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_qualification_names_() {
      this.qualification_names_id = "";
      this.on_qualification_names("");
    },
    on_qualification_names(qualification_name) {
      console.log(" qualification_name:", qualification_name);
      this.filterConditions.qualification_name = qualification_name;
    },
    getQualification_name() {
      // var member_id = Number(this.$store.getters.myMember_id);
      // console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_qualification_name",
          // member_id: member_id,
        })
        .then(function (res) {
          console.log("Qualification_name:", res.data);
          self.qualification_names.options = res.data.map((item) => ({
            label: item.qualification_name,
            value: item.qualification_name,
          }));
          self.qualification_names_.options = self.qualification_names.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterQualification_name(val, update) {
      if (val === "") {
        update(() => {
          this.qualification_names.options = this.qualification_names_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualification_names.options =
          this.qualification_names_.options.filter(
            (v) => v.label.indexOf(needle) > -1
          );
      });
    },
    on_years_() {
      this.years_id = "";
      this.on_years("");
    },
    on_years(year) {
      console.log(" year:", year);
      this.filterConditions.year = year;
    },
    getYear() {
      // var member_id = Number(this.$store.getters.myMember_id);
      // console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_year",
          // member_id: member_id,
        })
        .then(function (res) {
          console.log("Year:", res.data);
          self.years.options = res.data.map((item) => ({
            label: item.Year,
            value: item.Year,
          }));
          self.years_.options = self.years.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterYear(val, update) {
      if (val === "") {
        update(() => {
          this.years.options = this.years_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.years.options = this.years_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    on_months_() {
      this.months_id = "";
      this.on_months("");
    },
    on_months(month) {
      console.log(" month:", month);
      this.filterConditions.month = month;
    },
    getMonth() {
      // var member_id = Number(this.$store.getters.myMember_id);
      // console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_month",
          // member_id: member_id,
        })
        .then(function (res) {
          console.log("Year:", res.data);
          self.months.options = res.data.map((item) => ({
            label: item.Month,
            value: item.Month,
          }));
          self.months_.options = self.months.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    filterMonth(val, update) {
      if (val === "") {
        update(() => {
          this.months.options = this.months_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.months.options = this.months_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    getFilterYear() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" member_id:", member_id);
      console.log(" full_name:", this.filterConditions.full_name);
      console.log(" career_name:", this.filterConditions.career_name);
      console.log(
        " qualification_name:",
        this.filterConditions.qualification_name
      );
      console.log(" year:", this.filterConditions.year);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_filter_year",
          member_id: member_id,
          full_name:
            this.filterConditions.full_name == ""
              ? "%"
              : this.filterConditions.full_name,
          career_name:
            this.filterConditions.career_name == ""
              ? "%"
              : this.filterConditions.career_name,
          qualification_name:
            this.filterConditions.qualification_name == ""
              ? "%"
              : this.filterConditions.qualification_name,
          year:
            this.filterConditions.year == "" ? "%" : this.filterConditions.year,
        })
        .then(function (res) {
          console.log("table:+", res.data);
          self.year_table = res.data;
          //----------------------------------------------
          self.chart_database.categories = res.data.map(
            (item) => item.qualification_name
          );
          self.chart_database.firsts = res.data.map(
            (item) => item.min_perform_value
          );
          self.chart_database.finals = res.data.map(
            (item) => item.max_perform_value
          );
          self.chart_database.targets = res.data.map(
            (item) => item.target_value
          );
          console.log("chart_database:", self.chart_database.categories);
          console.log("chart_database:", self.chart_database.firsts);
          console.log("chart_database:", self.chart_database.finals);
          console.log("chart_database:", self.chart_database.targets);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getFilterMonth() {
      var member_id = Number(this.$store.getters.myMember_id);
      console.log(" แสดงข้อมูลคุณสมบัติ member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_qa_plan_career, {
          action: "get_filter_month",
          member_id: member_id,
          full_name:
            this.filterConditions.full_name == ""
              ? "%"
              : this.filterConditions.full_name,
          career_name:
            this.filterConditions.career_name == ""
              ? "%"
              : this.filterConditions.career_name,
          qualification_name:
            this.filterConditions.qualification_name == ""
              ? "%"
              : this.filterConditions.qualification_name,
          year:
            this.filterConditions.year == "" ? "%" : this.filterConditions.year,
          month:
            this.filterConditions.month == ""
              ? "%"
              : this.filterConditions.month,
        })
        .then(function (res) {
          console.log("table:+", res.data);
          self.month_table = res.data;
          //------------------------------------------------------------------
          self.chart_database_.categories = res.data.map((item) => item.Month);
          // self.chart_database_.firsts = res.data.map(
          //   (item) => item.min_perform_value
          // );
          self.chart_database_.finals = res.data.map(
            (item) => item.max_perform_value
          );
          self.chart_database_.targets = res.data.map(
            (item) => item.target_value
          );
          console.log("chart_database:", self.chart_database_.categories);
          console.log("chart_database:", self.chart_database_.firsts);
          console.log("chart_database:", self.chart_database_.finals);
          console.log("chart_database:", self.chart_database_.targets);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    //-------------------------
    updateChart() {
      console.log("chart_database:", this.chart_database.categories);
      console.log("chart_database:", this.chart_database.firsts);
      console.log("chart_database:", this.chart_database.finals);
      console.log("chart_database:", this.chart_database.targets);
      this.$refs.realtimeChart.updateOptions({
        xaxis: {
          categories: this.chart_database.categories,
        },
      });

      this.$refs.realtimeChart.updateSeries([
        {
          data: this.chart_database.firsts,
        },
        {
          data: this.chart_database.finals,
        },
        {
          data: this.chart_database.targets,
        },
      ]);
    },
    updateChart_() {
      console.log("chart_database:", this.chart_database.categories);
      console.log("chart_database:", this.chart_database.firsts);
      console.log("chart_database:", this.chart_database.finals);
      console.log("chart_database:", this.chart_database.targets);
      this.$refs.realtimeChart_.updateOptions({
        xaxis: {
          categories: this.chart_database_.categories,
        },
      });

      this.$refs.realtimeChart_.updateSeries([
        {
          data: this.chart_database_.firsts,
        },
        {
          data: this.chart_database_.finals,
        },
        {
          data: this.chart_database_.targets,
        },
      ]);
    },
    getUpdate() {
      console.log("get update-member_id:");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "getall",
          member_id: this.individual.member_id,
        })
        .then(function (res) {
          console.log("q-table:", res);
          self.individuals1 = res.data;
          console.log("individuals1:", self.individuals1);
        })
        .finally(() => {
          self.loading = false;
        });
    },
  },
  mounted() {
    this.getUpdate();
  },
  setup() {
    return {
      pagination: ref({
        rowsPerPage: 0,
      }),
    };
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_suser/individual_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url_api_individual = local_ + "api-individual.php";
      this.url_api_institute = local_ + "api-institute.php";
      this.url_api_disability = local_ + "api-disability.php";
      this.url_api_project = local_ + "api-project.php";
      this.url_api_advisor = local_ + "api-advisor.php";
      this.url_api_member = local_ + "api-member.php";
    } else {
      this.url_api_individual = www_ + "api-individual.php";
      this.url_api_institute = www_ + "api-institute.php";
      this.url_api_disability = www_ + "api-disability.php";
      this.url_api_project = www_ + "api-project.php";
      this.url_api_advisor = www_ + "api-advisor.php";
      this.url_api_member = www_ + "api-member.php";
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
div.chart-wrapper {
  align-items: center;
  justify-content: center;
}
</style>
