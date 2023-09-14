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
                      <!-- ค่าเป้าหมาย -->
                      <div class="row">
                        <div class="col-md-9 col-xs-12 q-pa-xs">
                          <q-input
                            color="white"
                            bg-color="blue-5"
                            standout
                            bottom-slots
                            v-model="individual.target_name"
                            label="เป้าหมาย *"
                            clearable
                            :rules="[
                              (val) =>
                                (val && val.length > 0) || 'ต้องใส่เป้าหมาย',
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
                        <div class="col-md-3 col-xs-12 q-pa-xs">
                          <q-input
                            color="white"
                            bg-color="blue-5"
                            standout
                            bottom-slots
                            v-model="individual.target_value"
                            label="ค่าเป้าหมาย *"
                            :rules="[
                              (val) =>
                                (val && val > 0 && val < 6) ||
                                'ต้องใส่ค่าเป้าหมาย',
                            ]"
                            clearable
                            mask="#"
                            fill-mask
                            hint="ค่าเป้าหมาย:#"
                          >
                            <template v-slot:prepend>
                              <q-icon name="work_history" />
                            </template>
                            <template v-slot:append>
                              <q-icon name="favorite" />
                            </template>
                          </q-input>
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
                        title="ข้อมูลค่าเป้าหมาย"
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
                            placeholder="ค้นหาค่าเป้าหมาย"
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
                              @click="editUser(props.row.target_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.target_id,
                                  props.row.target_name
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
      title: "ค่าเป้าหมาย(ผู้ดูแลระบบ)",
      email: "",
      username: "",
      password: "",
      repassword: "",
      register: false,
      passwordFieldType: "password",
      btnLabel: "เพิ่มข้อมูล",
      visibility: false,
      visibilityIcon: "visibility",
      // input: {
      //   username: "",
      //   password: "",
      // },
      individuals: Array,
      individuals_: Array,
      individual: {
        target_id: "",
        target_name: "",
        target_value: "",
        target_description: "",
      },
      isEdit: false,
      status: "บันทึก",
      visibleColumns: ref([
        "actions",
        "target_id",
        "target_name",
        "target_value",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "target_id",
          align: "center",
          label: "รหัสค่าเป้าหมาย",
          field: (row) => row.target_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "target_name",
          align: "left",
          label: "เป้าหมาย",
          field: "target_name",
          sortable: true,
        },
        {
          name: "target_value",
          align: "center",
          label: "ค่าเป้าหมาย",
          field: "target_value",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      individuals1: [],
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
            console.log(
              "บันทึกข้อมูล target_name:",
              this.individual.target_name
            );
            console.log(
              "บันทึกข้อมูล target_value:",
              this.individual.target_value
            );
            const newindividual = {
              target_name: this.individual.target_name,
              target_value: this.individual.target_value,
            };
            this.$emit("saveData", newindividual);

            axios
              .post(this.url_api_individual, {
                action: "insert",
                target_name: this.individual.target_name,
                target_value: this.individual.target_value,
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
              "บันทึกการแก้ไข target_name:",
              this.individual.target_name
            );
            axios
              .post(this.url_api_individual, {
                action: "update",
                target_id: this.individual.target_id,
                target_name: this.individual.target_name,
                target_value: this.individual.target_value,
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
    editUser(target_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "edit",
          target_id: target_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.individual.target_id = response.data.target_id;
          self.individual.target_name = response.data.target_name;
          self.individual.target_value = response.data.target_value;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(target_id, target_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " + target_id + "-" + target_name + " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_individual, {
              action: "delete",
              target_id: target_id,
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

    onNext() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    onPrevious() {},
  },
  mounted() {
    this.getUpdate();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_admin_constances/TargetForm/";
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
