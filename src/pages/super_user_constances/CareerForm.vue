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
                        <div class="col-md-6 col-xs-12 q-pa-xs">
                          <q-select
                            v-model="career_"
                            use-input
                            use-chips
                            input-debounce="0"
                            @new-value="createValue"
                            @update:model-value="(val) => updateValue(val)"
                            :options="career.options"
                            @filter="filterCareer"
                            label="อาชีพ *"
                            stack-label
                            lazy-rules
                          >
                            <template v-slot:prepend>
                              <q-icon name="school" />
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
                            ref="name"
                            use-input
                            use-chips
                            input-debounce="0"
                            @filter="filterCareerGroup"
                            v-model="career_group_"
                            :options="career_group.options"
                            label="กลุ่มอาชีพ *"
                            stack-label
                            lazy-rules
                          >
                            <template v-slot:prepend>
                              <q-icon name="school" />
                            </template>
                            <!-- <template v-slot:selected>
                              กลุ่มอาชีพ:
                              <q-chip
                                v-if="career_group_"
                                dense
                                square
                                color="white"
                                text-color="primary"
                                class="q-pa-xs"
                              >
                                {{
                                  career_group_.label != ""
                                    ? career_group_.label
                                    : ""
                                }}
                              </q-chip>
                              <q-badge v-else>*none*</q-badge>
                            </template> -->
                            <!-- <template v-if="career_group_" v-slot:append>
                              <q-icon
                                name="cancel"
                                @click.stop.prevent="career_group_ = null"
                                class="cursor-pointer"
                              />
                            </template> -->
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
                        title="อาชีพ"
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
                            placeholder="ค้นหาอาชีพ"
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
                              @click="editUser(props.row.career_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.career_id,
                                  props.row.career_name
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
      career_: ref(null),
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
      title: "อาชีพ(ผู้ดูแลกลุ่ม)",
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
        career_id: "",
        career_name: "",
        career_group_id: "",
      },
      isEdit: false,
      status: "บันทึก",
      visibleColumns: ref([
        "actions",
        "career_id",
        "career_name",
        "career_group_id",
        "ca_group_name",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "career_id",
          align: "center",
          label: "รหัสอาชีพ",
          field: (row) => row.career_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        {
          name: "career_name",
          align: "left",
          label: "อาชีพ",
          field: "career_name",
          sortable: true,
        },
        // {
        //   name: "career_group_id",
        //   align: "center",
        //   label: "รหัสกลุ่มอาชีพ",
        //   field: "career_group_id",
        //   sortable: true,
        // },
        {
          name: "ca_group_name",
          align: "left",
          label: "กลุ่มอาชีพ",
          field: "ca_group_name",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      individuals1: [],
      career_group: {
        options: [],
      },
      career_groups: {
        options: [],
      },
      // career_group_: ref({
      //   label: "",
      //   value: "",
      // }),
      career_group_: ref(null),
      //---------------
      career: {
        options: [],
      },
      careers: {
        options: [],
      },

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
    filterCareerGroup(val, update) {
      if (val === "") {
        update(() => {
          this.career_group.options = this.career_groups.options;
          console.log("career_groups.options:", this.career_groups.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.career_group.options = this.career_groups.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("career_groups.options:", this.career_groups.options);
      });
    },
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      console.log("ยกเลิกการบันทึกข้อมูล");
      // ข้อมูลส่วนตัว
      this.individual.career_id = "";
      this.career_group_ = ref(null);
      this.career_ = ref(null);

      this.getCareer();
      this.getCareer_group();
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
              "บันทึกข้อมูล career_name1111:",
              this.individual.career_name
            );
            const newindividual = {
              career_name: this.individual.career_name,
              career_group_id: this.career_group_.value,
            };
            this.$emit("saveData", newindividual);

            axios
              .post(this.url_api_individual, {
                action: "insert",
                career_name: this.individual.career_name,
                career_group_id: this.career_group_.value,
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
            console.log("บันทึกการแก้ไข career_id:", this.individual.career_id);
            axios
              .post(this.url_api_individual, {
                action: "update",
                career_id: this.individual.career_id,
                career_name: this.individual.career_name,
                career_group_id: this.career_group_.value,
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
    editUser(career_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "edit",
          career_id: career_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.individual.career_id = response.data.career_id;
          self.individual.career_name = response.data.career_name;
          self.career_ = ref({
            value: response.data.career_id,
            label: response.data.career_name,
          });
          self.individual.career_id = response.data.career_id;
          self.individual.career_name = response.data.career_name;
          self.career_group_ = ref({
            value: response.data.career_group_id,
            label: response.data.ca_group_name,
          });
          console.log("self.individual.career_id:", self.individual.career_id);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(career_id, career_name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " + career_id + "-" + career_name + " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_individual, {
              action: "delete",
              career_id: career_id,
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
          self.getCareer();
        })
        .finally(() => {
          self.loading = false;
        });
    },
    getCareer_group() {
      console.log("get_career_group");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "get_career_group",
        })
        .then(function (res) {
          var career_group_id = res.data.map((item) => item.career_group_id);
          var ca_group_name = res.data.map((item) => item.ca_group_name);
          var ca_group_description = res.data.map(
            (item) => item.ca_group_description
          );
          self.career_group.options.splice(0);
          for (var i = 0; i < career_group_id.length; i++) {
            self.career_group.options.push({
              label: ca_group_name[i],
              value: career_group_id[i],
              description: ca_group_description[i],
            });
          }
          self.career_groups.options = self.career_group.options;
          console.log("career_group.options:", self.career_group.options);
          console.log("career_groups.options", self.career_groups.options);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    filterCareer(val, update) {
      if (val === "") {
        update(() => {
          this.career.options = this.careers.options;
          console.log("careers.options:", this.careers.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.career.options = this.careers.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("careers.options:", this.careers.options);
      });
    },
    getCareer() {
      console.log("get_career");
      var self = this;
      axios
        .post(this.url_api_individual, {
          action: "get_career",
        })
        .then(function (res) {
          console.log("q-table1:", res);
          var career_id = res.data.map((item) => item.career_id);
          var career_name = res.data.map((item) => item.career_name);
          self.career.options.splice(0);
          for (var i = 0; i < career_id.length; i++) {
            self.career.options.push({
              label: career_name[i],
              // value: career_id[i],
            });
          }
          self.careers.options = self.career.options;
          console.log("career.options:", self.career.options);
          console.log("careers.options", self.careers.options);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    onNext() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    onPrevious() {},
    createValue(val, done) {
      console.log("new career:", val);
      console.log("this.career.options:", this.careers.options);
      if (val.length > 0) {
        if (!this.careers.options.includes(val)) {
          this.individual.career_name = val;
          console.log(
            "this.individual.career_name:",
            this.individual.career_name
          );
          done(val, "add-unique");
        }
      }
    },
    updateValue(val) {
      if (is.object(val)) {
        this.individual.career_name = val.label;
        console.log("Object", this.individual.career_name);
      } else {
        this.individual.career_name = val;
        console.log("Not object", this.individual.career_name);
      }
      console.log("this.individual.career_name:", this.individual.career_name);
    },
  },
  mounted() {
    this.getUpdate();
    this.getCareer();
    this.getCareer_group();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_user_constances/CareerForm/api-individual.php";
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
