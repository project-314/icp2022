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
                      <div class="row">
                        <!-- <div class="col-md-12 col-xs-12 q-pa-xs">
                          <q-input
                            color="white"
                            bg-color="blue-5"
                            standout
                            bottom-slots
                            v-model="individual.qualification_name"
                            label="คุณสมบัติ/ทักษะ *"
                            clearable
                            :rules="[
                              (val) =>
                                (val && val.length > 0) ||
                                'ต้องใส่คุณสมบัติ/ทักษะ',
                            ]"
                          >
                            <template v-slot:prepend>
                              <q-icon name="work_history" />
                            </template>
                            <template v-slot:append>
                              <q-icon name="favorite" />
                            </template>
                          </q-input>
                        </div> -->
                        <!-- @update:model-value="(val) => onMemberNames(val)" -->
                        <!-- :rules="[
                              (val) =>
                                (val && val.length > 0) || 'เลือกกลุ่มอาชีพ',
                            ]" -->
                        <div class="col-md-6 col-xs-12 q-pa-xs">
                          <q-select
                            v-model="qualification_"
                            use-input
                            use-chips
                            input-debounce="0"
                            @new-value="createValue"
                            @update:model-value="(val) => updateValue(val)"
                            :options="qualification.options"
                            @filter="filterQualification"
                            label="อาชีพ *"
                            stack-label
                            lazy-rules
                          >
                            <template v-slot:prepend>
                              <q-icon name="pending_actions" />
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
                        <div class="col-md-6 col-xs-12 q-pa-xs">
                          <q-select
                            v-model="qualification_group_"
                            use-input
                            use-chips
                            input-debounce="0"
                            :options="qualification_group.options"
                            @filter="filterQualificationGroup"
                            label="กลุ่มอาชีพ *"
                            stack-label
                            lazy-rules
                          >
                            <template v-slot:prepend>
                              <q-icon name="pending_actions" />
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
                        title="คุณสมบัติ/ทักษะ"
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
                            placeholder="ค้นหาคุณสมบัติ/ทักษะ"
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
                              @click="editUser(props.row.qualification_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.qualification_id,
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
import { is } from "quasar";
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
      title: "คุณสมบัติ/ทักษะ(ผู้ดูแลระบบ)",
      email: "",
      username: "",
      password: "",
      repassword: "",
      register: false,
      passwordFieldType: "password",
      btnLabel: "เพิ่มข้อมูล",
      visibility: false,
      visibilityIcon: "visibility",
      individuals: Array,
      individuals_: Array,
      individual: {
        qualification_id: "",
        qualification_name: "",
        qualification_group_id: "",
        qualification_group_name: "",
      },
      isEdit: false,
      status: "บันทึก",
      visibleColumns: ref([
        "actions",
        "qualification_id",
        "qualification_name",
        "qualification_group_id",
        "qualification_group_name",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "qualification_id",
          align: "center",
          label: "รหัสคุณสมบัติ",
          field: (row) => row.qualification_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "qualification_name",
          align: "left",
          label: "คุณสมบัติ/ทักษะ",
          field: "qualification_name",
          sortable: true,
        },
        // {
        //   name: "qualification_group_id",
        //   align: "center",
        //   label: "รหัสกลุ่มคุณสมบัติ",
        //   field: "qualification_group_id",
        //   sortable: true,
        // },
        {
          name: "qualification_group_name",
          align: "left",
          label: "กลุ่มอาชีพ",
          field: "qualification_group_name",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      individuals1: [],
      qualification_group: {
        options: [],
      },
      qualification_groups: {
        options: [],
      },
      qualification_group_: ref(null),
      //-------------------------
      qualification: {
        options: [],
      },
      qualifications: {
        options: [],
      },
      qualification_: ref(null),

      $q: useQuasar(),
    };
  },

  methods: {
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
    onTelephone() {
      console.log("next-telephone");
    },
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      console.log("ยกเลิกการบันทึกข้อมูล");
      // ข้อมูลส่วนตัว
      this.individual.qualification_id = "";
      this.individual.qualification_name = "";
      this.qualification_group_ = ref(null);
      this.qualification_ = ref(null);
      this.getQualification();
      this.getQualification_group();
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
            console.log(
              "บันทึกข้อมูล qualification name:",
              this.individual.qualification_name
            );
            const newindividual = {
              qualification_name: this.individual.qualification_name,
              qualification_group_id: this.qualification_group_.value,
            };
            this.$emit("saveData", newindividual);

            axios
              .post(this.url_api_individual, {
                action: "insert",
                qualification_name: this.individual.qualification_name,
                qualification_group_id: this.qualification_group_.value,
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
            console.log(
              "บันทึกการแก้ไข career_id:",
              this.individual.qualification_id
            );
            axios
              .post(this.url_api_individual, {
                action: "update",
                qualification_id: this.individual.qualification_id,
                qualification_name: this.individual.qualification_name,
                qualification_group_id: this.qualification_group_.value,
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
    editUser(qualification_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "edit",
          qualification_id: qualification_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.individual.qualification_id = response.data.qualification_id;
          self.individual.qualification_name = response.data.qualification_name;
          self.qualification_ = ref({
            value: response.data.qualification_id,
            label: response.data.qualification_name,
          });
          self.qualification_group_ = ref({
            value: response.data.qualification_group_id,
            label: response.data.qualification_group_name,
          });
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(qualification_id, qualification_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " +
            qualification_id +
            "-" +
            qualification_name +
            " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_individual, {
              action: "delete",
              qualification_id: qualification_id,
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
          console.log("q-table1:", res);
          self.individuals1 = res.data;
          console.log("individuals2:", self.individuals1);
          self.getQualification();
        })
        .finally(() => {
          self.loading = false;
        });
    },
    getQualification_group() {
      console.log("get_qualification_group");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "get_qualification_group",
        })
        .then(function (res) {
          console.log("qualification:", res.data);
          var qualification_group_id = res.data.map(
            (item) => item.qualification_group_id
          );
          var qualification_group_name = res.data.map(
            (item) => item.qualification_group_name
          );
          var qualification_group_description = res.data.map(
            (item) => item.qualification_group_description
          );
          self.qualification_group.options.splice(0);
          for (var i = 0; i < qualification_group_id.length; i++) {
            self.qualification_group.options.push({
              label: qualification_group_name[i],
              value: qualification_group_id[i],
              description: qualification_group_description[i],
            });
          }
          self.qualification_groups.options = self.qualification_group.options;
          console.log(
            "qualification_group.options:",
            self.qualification_group.options
          );
          console.log(
            "qualification_groups.options",
            self.qualification_groups.options
          );
        })
        .finally(() => {
          self.loading = false;
        });
    },
    filterQualificationGroup(val, update) {
      if (val === "") {
        update(() => {
          this.qualification_group.options = this.qualification_groups.options;
          console.log(
            "qualification_groups.options:",
            this.qualification_groups.options
          );
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualification_group.options =
          this.qualification_groups.options.filter(
            (v) => v.label.indexOf(needle) > -1
          );
        console.log(
          "qualification_groups.options:",
          this.qualification_groups.options
        );
      });
    },
    //----------------------------------------
    getQualification() {
      console.log("get_qualification");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "getall",
        })
        .then(function (res) {
          console.log("qualification:", res.data);
          var qualification_id = res.data.map((item) => item.qualification_id);
          var qualification_name = res.data.map(
            (item) => item.qualification_name
          );
          self.qualification.options.splice(0);
          for (var i = 0; i < qualification_id.length; i++) {
            self.qualification.options.push({
              label: qualification_name[i],
              value: qualification_id[i],
            });
          }
          self.qualifications.options = self.qualification.options;
          console.log("qualification.options:", self.qualification.options);
          console.log("qualifications.options", self.qualifications.options);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    filterQualification(val, update) {
      if (val === "") {
        update(() => {
          this.qualification.options = this.qualifications.options;
          console.log("qualifications.options:", this.qualifications.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.qualification.options = this.qualifications.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("qualifications.options:", this.qualifications.options);
      });
    },
    onNext() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    onPrevious() {},
    createValue(val, done) {
      console.log("new qualification:", val);
      console.log("this.qualifications.options:", this.qualifications.options);
      if (val.length > 0) {
        if (!this.qualifications.options.includes(val)) {
          this.individual.qualification_name = val;
          console.log(
            "this.individual.qualification_name:",
            this.individual.qualification_name
          );
          done(val, "add-unique");
        }
      }
    },
    updateValue(val) {
      if (is.object(val)) {
        this.individual.qualification_name = val.label;
        console.log("Object", this.individual.qualification_name);
      } else {
        this.individual.qualification_name = val;
        console.log("Not object", this.individual.qualification_name);
      }
      console.log(
        "this.individual.qualification_name:",
        this.individual.qualification_name
      );
    },
  },
  mounted() {
    this.getUpdate();
    this.getQualification();
    this.getQualification_group();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_user_constances/QualificationForm/api-individual.php";
    if (!www) {
      this.url_api_individual = "http://localhost:85/icp2022/" + folder;
    } else {
      this.url_api_individual = "https://icp2022.net/" + folder;
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
