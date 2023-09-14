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
                        <div class="col-md-4 col-xs-12 q-pa-xs">
                          <q-input
                            color="white"
                            bg-color="blue-5"
                            standout
                            bottom-slots
                            v-model="individual.level_name"
                            label="ระดับความสำคัญ *"
                            clearable
                            :rules="[
                              (val) =>
                                (val && val.length > 0) ||
                                'ต้องใส่ระดับความสำคัญ',
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
                        <div class="col-md-5 col-xs-12 q-pa-xs">
                          <q-input
                            color="white"
                            bg-color="blue-5"
                            standout
                            bottom-slots
                            v-model="individual.level_description"
                            label="ความหมาย *"
                            clearable
                            :rules="[
                              (val) =>
                                (val && val.length > 0) || 'ต้องใส่ความหมาย',
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
                            v-model="individual.weigth"
                            label="ค่าระดับความสำคัญ *"
                            clearable
                            mask="#"
                            fill-mask
                            hint="ค่าระดับความสำคัญ:#"
                            :rules="[
                              (val) =>
                                (val && val > 0 && val < 4) ||
                                'ต้องใส่ค่าความสำคัญ',
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
                        title="ระดับความสำคัญ"
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
                            placeholder="ค้นหาระดับความสำคัญ"
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
                              @click="editUser(props.row.level_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.level_id,
                                  props.row.level_name
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
      title: "ระดับความสำคัญ(ผู้ดูแลระบบ)",
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
        level_id: "",
        level_name: "",
        level_description: "",
        weigth: "",
      },
      isEdit: false,
      status: "บันทึก",
      visibleColumns: ref([
        "actions",
        "level_id",
        "level_name",
        "level_description",
        "weigth",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "level_id",
          align: "center",
          label: "รหัสระดับความสำคัญ",
          field: (row) => row.level_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "level_name",
          align: "left",
          label: "ระดับความสำคัญ",
          field: "level_name",
          sortable: true,
        },
        {
          name: "level_description",
          align: "left",
          label: "ความหมาย",
          field: "level_description",
          sortable: true,
        },
        {
          name: "weigth",
          align: "center",
          label: "น้ำหนัก",
          field: "weigth",
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
      this.individual.importance_id = "";
      this.individual.importance_name = "";
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
              "บันทึกข้อมูล perform_name:",
              this.individual.level_name
            );
            const newindividual = {
              level_name: this.individual.level_name,
              level_description: this.individual.level_description,
              weigth: this.individual.weigth,
            };
            this.$emit("saveData", newindividual);

            axios
              .post(this.url_api_individual, {
                action: "insert",
                level_name: this.individual.level_name,
                level_description: this.individual.level_description,
                weigth: this.individual.weigth,
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
            console.log("บันทึกการแก้ไข level_id:", this.individual.level_id);
            axios
              .post(this.url_api_individual, {
                action: "update",
                level_id: this.individual.level_id,
                level_name: this.individual.level_name,
                level_description: this.individual.level_description,
                weigth: this.individual.weigth,
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
    editUser(level_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "edit",
          level_id: level_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.individual.level_id = response.data.level_id;
          self.individual.level_name = response.data.level_name;
          self.individual.level_description = response.data.level_description;
          self.individual.weigth = response.data.weigth;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(level_id, level_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " + level_id + "-" + level_name + " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_individual, {
              action: "delete",
              level_id: level_id,
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
    var folder = "icp_v1_admin_constances/LevelForm/api-individual.php";
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
