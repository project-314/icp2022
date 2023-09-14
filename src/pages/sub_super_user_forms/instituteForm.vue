<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="bg-grey-2">
      <div @keyup="nextItem">
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
                <div class="q-pa-md">
                  <div class="col-md-12 col-xs-12 q-pa-xs">
                    <q-form
                      @submit.prevent="submitForm()"
                      @reset="resetForm()"
                      method="post"
                      class="q-gutter-md"
                    >
                      <!-- สถาบัน -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <q-input
                          color="white"
                          bg-color="blue-5"
                          standout
                          bottom-slots
                          v-model="individual.institute_name"
                          label="สถาบันการศึกษา *"
                          clearable
                          :rules="[
                            (val) =>
                              (val && val.length > 0) ||
                              'ต้องใส่สถาบันการศึกษา',
                          ]"
                        >
                          <template v-slot:prepend>
                            <q-icon name="work_history" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                      <!-- ปุ่มตวบคุม -->
                      <div class="row">
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
                          <!-- กลับฟอร์มการลงทะเบียน -->
                        </div>
                      </div>
                    </q-form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-xs-12 q-pa-xs">
                    <div class="q-pa-xs">
                      <q-table
                        title="ข้อมูลสถาบัน"
                        :rows="individuals1"
                        :columns="columns"
                        row-key="name"
                        :filter="filter"
                        :loading="loading"
                        :visible-columns="visibleColumns"
                      >
                        <template v-slot:top-right="props">
                          <q-input
                            borderless
                            dense
                            debounce="300"
                            v-model="filter"
                            placeholder="ค้นหาข้อมูลสถาบัน"
                          >
                            <template v-slot:append>
                              <q-icon name="search" />
                            </template>
                          </q-input>
                          <!-- ส่งออกไฟล์ -->
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
                          <q-btn
                            flat
                            icon-right="archive"
                            label="ส่งออกไฟล์"
                            @click="exportTable()"
                          />
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
                        </template>
                        <template v-slot:body-cell-actions="props">
                          <q-td :props="props">
                            <q-btn
                              icon="mode_edit"
                              label="แก้ไข"
                              @click="editUser(props.row.institute_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.institute_id,
                                  props.row.institute_name
                                )
                              "
                            ></q-btn>
                          </q-td>
                        </template>
                      </q-table>
                    </div>
                  </div>
                </div>
              </q-card>
            </div>
          </div>
        </q-page>
      </div>
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
  name: "FormComponent",
  components: {},
  data() {
    return {
      file_export: "",
      pdpa: ref(false),
      picked: new Date(),
      url_api_individual: "",
      url_api_institute: "",
      url_api_disability: "",
      url_api_project: "",
      url_api_advisor: "",
      url_api_member: "",
      // ------------------------------------------------------------------------------
      title: "ข้อมูลสถาบัน(ผู้ดูแลกลุ่ม)",
      email: "",
      username: "",
      password: "",
      repassword: "",
      register: false,
      passwordFieldType: "password",
      btnLabel: "เพิ่มข้อมูล",
      visibility: false,
      visibilityIcon: "visibility",
      input: {
        username: "",
        password: "",
      },
      individuals: Array,
      individuals_: Array,
      individual: {
        individual_id: "",
        // ข้อมูลส่วนตัว
        member_id: this.$store.getters.myMember_id,
        name: this.$store.getters.myName,
        birthday: "",
        // card_id: "",
        telephone: "",
        // ข้อมูลการศึกษา
        institute_id: "",
        institute_name: "",
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
      isEdit: false,
      status: "บันทึก",
      visibleColumns: ref(["actions", "institute_id", "institute_name"]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "institute_id",
          align: "center",
          label: "รหัสสถาบัน",
          field: (row) => row.institute_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "institute_name",
          align: "left",
          label: "สถาบัน",
          field: "institute_name",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      individuals1: [],
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
      institutes_: {
        options: [],
      },
      institutes: {
        options: [],
      },
      institute: ref({
        label: "",
        value: "",
      }),
      facultys: {
        options: [],
      },
      facultys_: {
        options: [],
      },
      faculty: ref({
        label: "",
        value: "",
      }),
      degrees_: {
        options: [],
      },
      degrees: {
        options: [],
      },
      degree: ref({
        label: "",
        value: "",
      }),
      departments_: {
        options: [],
      },
      departments: {
        options: [],
      },
      department: ref({
        label: "",
        value: "",
      }),
      disabilitys_: {
        options: [],
      },
      disabilitys: {
        options: [],
      },
      disability: ref({
        label: "",
        value: "",
      }),
      projects_: {
        options: [],
      },
      projects: {
        options: [],
      },
      project: ref({
        label: "",
        value: "",
      }),
      advisors_: {
        options: [],
      },
      advisors: {
        options: [],
      },
      advisor: ref({
        label: "",
        value: "",
      }),
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
    onTelephone() {
      console.log("next-telephone");
    },
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      console.log("ยกเลิกการบันทึกข้อมูล");
      // ข้อมูลส่วนตัว
      this.individual.institute_id = "";
      this.individual.institute_name = "";
    },
    submitForm() {
      if (!this.isEdit) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการเพิ่มข้อมูลหรือไม่?",
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            console.log("บันทึกข้อมูล member:", this.member.value);
            const newindividual = {
              institute_name: this.individual.institute_name,
            };
            this.$emit("saveData", newindividual);

            axios
              .post(this.url_api_individual, {
                action: "insert",
                institute_name: this.individual.institute_name,
              })
              .then((res) => {
                console.log("บันทึกข้อมูล:", res.data);
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
            message: "คุณต้องการบันทึกการเแก้ไขข้อมูลหรือไม่?",
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            console.log("บันทึกการแก้ไข project:", this.member.value);
            axios
              .post(this.url_api_individual, {
                action: "update",
                institute_id: this.individual.institute_id,
                institute_name: this.individual.institute_name,
              })
              .then((response) => {
                console.log("บันทึกการแก้ไข:", response.data);
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
    editUser(institute_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "edit",
          institute_id: institute_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.individual.institute_id = response.data.institute_id;
          self.individual.institute_name = response.data.institute_name;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(institute_id, institute_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " +
            institute_id +
            "-" +
            institute_name +
            " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_individual, {
              action: "delete",
              institute_id: institute_id,
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
    getUpdate() {
      console.log("get update-member_id:");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "getall",
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
    getMember() {
      console.log("Get Member:");
      var self = this;
      axios
        .post(this.url_api_individual, {
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
    onNext() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    onPrevious() {},
    getInstitutes() {
      console.log(" แสดงข้อมูลสถาบัน ");
      var self = this;
      axios
        .post(this.url_api_institute, {
          action: "getInstitutes",
        })
        .then(function (res) {
          console.log("ข้อมูลสถาบัน:", res.data);
          var institute_id = res.data.map((item) => item.institute_id);
          var institute_name = res.data.map((item) => item.institute_name);
          self.institutes.options.splice(0);
          for (var i = 0; i < institute_id.length; i++) {
            self.institutes.options.push({
              label: institute_name[i],
              value: institute_id[i],
            });
          }
          self.institutes_.options = self.institutes.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getFacultys() {
      console.log(" แสดงข้อมูลคณะ: ", this.institute.value);
      var self = this;
      axios
        .post(this.url_api_institute, {
          action: "getFacultys",
          institute_id: this.institute.value,
        })
        .then(function (res) {
          console.log("ข้อมูลคณะ:", res.data);
          var faculty_id = res.data.map((item) => item.faculty_id);
          var faculty_name = res.data.map((item) => item.faculty_name);
          self.facultys.options.splice(0);
          for (var i = 0; i < faculty_id.length; i++) {
            self.facultys.options.push({
              label: faculty_name[i],
              value: faculty_id[i],
            });
          }
          self.facultys_.options = self.facultys.options;
          console.log("ข้อมูล ชื่อคณะ:", self.facultys.options[0].label);
          console.log("ข้อมูล รหัสคณะ:", self.facultys.options[0].value);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getDegrees() {
      console.log(" แสดงข้อมูลระดับการศึกษา ");
      var self = this;
      axios
        .post(this.url_api_institute, {
          action: "getDegrees",
          institute_id: this.institute.value,
          faculty_id: this.faculty.value,
        })
        .then(function (res) {
          console.log("ข้อมูลระดับการศึกษา:", res.data);
          var degree_id = res.data.map((item) => item.degree_id);
          var degree_name = res.data.map((item) => item.degree_name);
          self.degrees.options.splice(0);
          for (var i = 0; i < degree_id.length; i++) {
            self.degrees.options.push({
              label: degree_name[i],
              value: degree_id[i],
            });
          }
          self.degrees_.options = self.degrees.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getDepartments() {
      console.log(" แสดงข้อมูลสาขาวิชา ");
      var self = this;
      axios
        .post(this.url_api_institute, {
          action: "getDepartments",
          degree_id: this.degree.value,
          institute_id: this.institute.value,
          faculty_id: this.faculty.value,
        })
        .then(function (res) {
          console.log("ข้อมูลสาขาวิชา:", res.data);
          var department_id = res.data.map((item) => item.department_id);
          var department_name = res.data.map((item) => item.department_name);
          self.departments.options.splice(0);
          for (var i = 0; i < department_id.length; i++) {
            self.departments.options.push({
              label: department_name[i],
              value: department_id[i],
            });
          }
          self.departments_.options = self.departments.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getDisabilitys() {
      console.log(" แสดงข้อมูลสาขาวิชา ");
      var self = this;
      axios
        .post(this.url_api_disability, {
          action: "getDisabilitys",
        })
        .then(function (res) {
          console.log("ข้อมูลชนิดความพิการ:", res.data);
          var disability_id = res.data.map((item) => item.disability_id);
          var disability_name = res.data.map((item) => item.disability_name);
          self.disabilitys.options.splice(0);
          for (var i = 0; i < disability_id.length; i++) {
            self.disabilitys.options.push({
              label: disability_name[i],
              value: disability_id[i],
            });
          }
          self.disabilitys_.options = self.disabilitys.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getProjects() {
      console.log(" แสดงข้อมูลโครงการ ");
      var self = this;
      axios
        .post(this.url_api_project, {
          action: "getProjects",
        })
        .then(function (res) {
          console.log("ข้อมูลโครงการ:", res.data);
          var project_id = res.data.map((item) => item.project_id);
          var project_name = res.data.map((item) => item.project_name);
          self.projects.options.splice(0);
          for (var i = 0; i < project_id.length; i++) {
            self.projects.options.push({
              label: project_name[i],
              value: project_id[i],
            });
          }
          self.projects_.options = self.projects.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getAdvisors_() {
      console.log(" แสดงข้อมูลอาจารย์ที่ปรึกษา ");
      var self = this;
      axios
        .post(this.url_api_advisor, {
          action: "getAdvisors",
        })
        .then(function (res) {
          console.log("ข้อมูลอาจารย์ที่ปรึกษา:", res.data);
          var advisor_id = res.data.map((item) => item.advisor_id);
          var advisor_name = res.data.map((item) => item.advisor_name);
          self.advisors.options.splice(0);
          for (var i = 0; i < advisor_id.length; i++) {
            self.advisors.options.push({
              label: advisor_name[i],
              value: advisor_id[i],
            });
          }
          self.advisors_.options = self.advisors.options;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getAdvisors() {
      console.log(" แสดงข้อมูลอาจารย์ที่ปรึกษา ");
      var self = this;
      axios
        .post(this.url_api_advisor, {
          action: "getAdvisors",
        })
        .then(function (res) {
          console.log("ข้อมูลอาจารย์ที่ปรึกษา:", res.data);
          var advisor_id = res.data.map((item) => item.member_id);
          var advisor_name = res.data.map((item) => item.full_name);
          self.advisors.options.splice(0);
          for (var i = 0; i < advisor_id.length; i++) {
            self.advisors.options.push({
              label: advisor_name[i],
              value: advisor_id[i],
            });
          }
          self.advisors_.options = self.advisors.options;
          console.log("ข้อมูลอาจารย์ที่ปรึกษา1:", self.advisors_.options);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    onBirthday(val) {
      console.log("Thai Date:", val);
    },
    onInstituteValueChange(val) {
      console.log("เลือกสถาบัน:", val.label);
      console.log("รหัสสถาบัน:", val.value);
      this.getFacultys();
    },
    onFacultyValueChange(val) {
      console.log("เลือกคณะ:", val.label);
      console.log("รหัสคณะ:", val.value);
      this.getDegrees();
    },
    onDegreeValueChange(val) {
      console.log("เลือกระดับการศึกษา:", val.label);
      console.log("รหัสระดับการศึกษา:", val.value);
      this.getDepartments();
    },
    onDepartmentValueChange(val) {
      console.log("เลือกสาขาวิชา:", val.label);
      console.log("รหัสสาขาวิชา:", val.value);
    },
    onProjectValueChange(val) {
      console.log("เลือกโครงการ:", val.label);
      console.log("รหัสโครงการ:", val.value);
    },
    onDisabilityValueChange(val) {
      console.log("เลือกความพิการ:", val.label);
      console.log("รหัสความพิการ:", val.value);
    },
    filterInstitute(val, update) {
      if (val === "") {
        update(() => {
          this.institutes.options = this.institutes_.options;
          console.log("institutes_.options:", this.institutes_.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.institutes.options = this.institutes_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("institutes_.options:", this.institutes_.options);
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
    filterFaculty(val, update) {
      if (val === "") {
        update(() => {
          this.facultys.options = this.facultys_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.facultys.options = this.facultys_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterDegree(val, update) {
      if (val === "") {
        update(() => {
          this.degrees.options = this.degrees_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.degrees.options = this.degrees_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterDepartment(val, update) {
      if (val === "") {
        update(() => {
          this.departments.options = this.departments_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.departments.options = this.departments_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterDisability(val, update) {
      if (val === "") {
        update(() => {
          this.disabilitys.options = this.disabilitys_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.disabilitys.options = this.disabilitys_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterProject(val, update) {
      if (val === "") {
        update(() => {
          this.projects.options = this.projects_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.projects.options = this.projects_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
    filterAdvisor(val, update) {
      if (val === "") {
        update(() => {
          this.advisors.options = this.advisors_.options;
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.advisors.options = this.advisors_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
      });
    },
  },
  mounted() {
    this.getUpdate();
    this.getMember();
    this.getInstitutes();
    this.getFacultys();
    this.getDegrees();
    this.getDepartments();
    this.getDisabilitys();
    this.getProjects();
    this.getAdvisors();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_sub_admin/institute/";
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
