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
                <div class="row full-width">
                  <div class="col-md-12 col-xs-12 q-pa-xs">
                    <q-form
                      @submit.prevent="submitForm()"
                      @reset="resetForm()"
                      method="post"
                      class="q-gutter-md"
                    >
                      <!-- จบการศึกษา + ปีที่สำเร็จการศึกษา + ชั้นปีที่กำลังศึกษา -->
                      <div class="row">
                        <!-- สถานะการแจ้งเตือน วันที่เริ่มแจ้งเตือน ประเภทการแจ้งเตือน -->
                        <div class="col-md-4 col-xs-12 q-pa-xs">
                          <q-checkbox
                            v-model="is_notification"
                            val="is_notification"
                            label="ฉันต้องการให้มีการแจ้งเตือน"
                            color="teal"
                            true-value="1"
                            false-value="0"
                          />
                        </div>
                        <!-- วันที่เริ่มแจ้งเตือน -->
                        <div class="col-md-4 col-xs-12 q-pa-xs">
                          <q-input
                            filled
                            v-model="notification_date"
                            label="วันเริ่มแจ้งเตือน *"
                            :disable="is_notification == '1' ? false : true"
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
                                    v-model="notification_date"
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
                        <!-- ประเภทการแจ้งเตือน -->
                        <div class="col-md-4 col-xs-12 q-pa-xs">
                          <q-select
                            @filter="filterFrequency"
                            use-input
                            color="green"
                            v-model="frequency.options.value"
                            :options="frequency.options"
                            label="การแจ้งเตือน *"
                            emit-value
                            map-options
                            :disable="is_notification == '1' ? false : true"
                          >
                            <template v-slot:prepend>
                              <q-icon name="notifications_active" />
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
                            <template
                              v-if="frequency.options.value"
                              v-slot:append
                            >
                              <q-icon
                                name="cancel"
                                @click.stop.prevent="
                                  frequency.options.value = null
                                "
                                class="cursor-pointer"
                              />
                            </template>
                          </q-select>
                        </div>
                      </div>
                      <!-- ข้อความแจ้งเตือน -->
                      <div class="row">
                        <div class="col-md-12 col-xs-12 q-pa-xs">
                          <q-input
                            type="textarea"
                            color="blue-3"
                            standout
                            bottom-slots
                            v-model="message"
                            label="ข้อความแจ้งเตือน"
                            clearable
                            autogrow
                            :disable="is_notification == '1' ? false : true"
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
                          <q-btn
                            color="primary"
                            label="กลับฟอร์มการลงทะเบียน"
                            no-caps
                            flat
                            icon="skip_previous"
                            to="/RegistrationPage"
                          >
                            <q-tooltip class="bg-accent"
                              >กลับฟอร์มการลงทะเบียน</q-tooltip
                            >
                          </q-btn>
                          <!-- ไปฟอร์มกำหนดอาชีพเป้าหมาย -->
                          <q-btn
                            color="primary"
                            label="ไปฟอร์มกำหนดอาชีพเป้าหมาย"
                            no-caps
                            flat
                            icon="skip_next"
                            to="/FormPlanCareer"
                          >
                            <q-tooltip class="bg-accent"
                              >ไปฟอร์มกำหนดอาชีพเป้าหมาย</q-tooltip
                            >
                          </q-btn>
                        </div>
                      </div>
                    </q-form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-xs-12 q-pa-xs">
                    <div class="q-pa-xs">
                      <q-table
                        title="ข้อมูลส่วนตัว"
                        :rows="notification1"
                        :columns="columns"
                        row-key="name"
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
                                placeholder="ค้นหาข้อมูลการแจ้งเตือน"
                              >
                                <template v-slot:append>
                                  <q-icon name="search" />
                                </template>
                              </q-input>
                            </div>
                            <!-- ส่งออกไฟล์ -->
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
                              @click="editUser(props.row.notification_id)"
                            ></q-btn>
                            <q-btn
                              icon="delete"
                              label="ลบ"
                              @click="
                                deleteUser(
                                  props.row.notification_id,
                                  props.row.notification_date
                                )
                              "
                            ></q-btn>
                            <q-btn
                              icon="notification_important"
                              @click="getUpdateNotify(props.row.member_id)"
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
import { date } from "quasar";

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
      url_api_plan: "",
      url_api_individual: "",
      url_api_notification: "",
      // ---------------------------------------------------------------------------
      title: "การแจ้งเตือน",
      isEdit: false,
      btnLabel: "เพิ่มข้อมูล",
      status: "บันทึก",
      visibleColumns: ref([
        "actions",
        "notification_id",
        "member_id",
        "full_name",
        "is_notification",
        "message",
        "notification_date",
        "frequency_id",
        "frequency_name",
      ]),
      columns: [
        // ข้อมูลส่วนตัว
        { name: "actions", align: "center", label: "Action" },
        {
          name: "notification_id",
          label: "รหัสการแจ้งเตือน",
          align: "center",
          field: (row) => row.notification_id,
          format: (val) => `${val}`,
          required: true,
          sortable: true,
        },
        // {
        //   name: "member_id",
        //   label: "mem_id",
        //   align: "center",
        //   field: "member_id",
        //   sortable: true,
        // },
        {
          name: "is_notification",
          align: "center",
          label: "ต้องการแจ้งเตือน",
          field: "is_notification",
          sortable: true,
        },
        {
          name: "message",
          align: "center",
          label: "message",
          field: "message",
          sortable: true,
        },
        {
          name: "notification_date",
          align: "center",
          label: "วันเริ่มแจ้งเตือน",
          field: "notification_date",
          sortable: true,
        },
        // {
        //   name: "frequency_id",
        //   align: "center",
        //   label: "รหัสความถี่",
        //   field: "frequency_id",
        //   sortable: true,
        // },
        {
          name: "frequency_name",
          align: "center",
          label: "ชื่อความถี่",
          field: "frequency_name",
          sortable: true,
        },
      ],
      filter: ref(""),
      loading: ref(false),
      $q: useQuasar(),
      member_id: this.$store.getters.myMember_id,
      name: this.$store.getters.myName,
      notification_id: "",
      is_notification: "0",
      frequency_id: "",
      frequency_: {
        options: [],
      },
      frequency: {
        options: [],
      },
      notification_date: "",
      notification1: [],
      message: "",
    };
  },

  methods: {
    yearToDay(day_to_year) {
      var year_to_day = day_to_year.split("/");
      return year_to_day[2] + "/" + year_to_day[1] + "/" + year_to_day[0];
    },
    dayToYear(year_to_day) {
      var day_to_year = year_to_day.split("/");
      return day_to_year[2] + "/" + day_to_year[1] + "/" + day_to_year[0];
    },
    // นำออกไฟล์ excel
    exportTable() {
      console.log("Export excel");
      var columns = this.columns;
      var rows = this.notification1;
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
    resetForm() {
      this.isEdit = false;
      console.log("isEdit:", this.isEdit);
      this.btnLabel = "เพิ่มข้อมูล";
      console.log("ยกเลิกการบันทึกข้อมูล");

      this.is_notification = "0";
      this.notation_date = "";
      this.notification_date = "";
      this.frequency.options.label = "";
      this.frequency.options.value = "";
      this.message = "";
    },
    submitForm() {
      var start_date = this.yearToDay(this.notification_date);
      console.log("start_date:", start_date);
      if (!this.isEdit) {
        this.$q
          .dialog({
            title: "ยืนยัน",
            message: "คุณต้องการบันทึกการเพิ่มข้อมูลหรือไม่?",
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            console.log("บันทึกข้อมูล mem_id:", this.member_id);
            console.log("บันทึกข้อมูล not_date:", start_date);
            console.log("บันทึกข้อมูล not_type:", this.frequency.options.value);
            console.log("บันทึกข้อมูล is_not:", this.is_notification);

            const newNotification = {
              is_notification: this.is_notification,
              member_id: this.member_id,
              notification_date: start_date,
              notification_type: this.frequency.options.value,
              is_notification: this.is_notification,
              message: this.message,
            };
            this.$emit("saveData", newNotification);

            axios
              .post(this.url_api_notification, {
                action: "insert",
                notification_id: this.notification_id,
                member_id: this.member_id,
                notification_date: start_date,
                notification_type: this.frequency.options.value,
                is_notification: this.is_notification,
                message: this.message,
              })
              .then((res) => {
                console.log("บันทึกข้อมูล:", res.data);
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
            message:
              "คุณต้องการบันทึกการเแก้ไขข้อมูล : " + this.notification_date,
            cancel: true,
            persistent: true,
          })
          .onOk(() => {
            console.log("บันทึกการแก้ไข project:", start_date);
            axios
              .post(this.url_api_notification, {
                action: "update",
                notification_id: this.notification_id,
                member_id: this.member_id,
                notification_date: start_date,
                notification_type: this.frequency.options.value,
                is_notification: this.is_notification,
                message: this.message,
              })
              .then((response) => {
                console.log("บันทึกการแก้ไข:", response.data);
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
    editUser(notification_id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_notification, {
          action: "edit",
          notification_id: notification_id,
        })
        .then(function (response) {
          console.log("Edit data:", response.data);
          self.notification_id = response.data.notification_id;
          self.is_notification = String(response.data.is_notification);
          self.notification_date = self.dayToYear(
            response.data.notification_date
          );
          self.member_id = response.data.member_id;
          self.frequency.options.label = response.data.frequency_name;
          self.frequency.options.value = response.data.frequency_id;
          self.message = response.data.message;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    deleteUser(notification_id, notification_date) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message:
            "คุณต้องการลบ [ " +
            notification_id +
            "-" +
            notification_date +
            " ] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_notification, {
              action: "delete",
              notification_id: notification_id,
            })
            .then(function (response) {
              console.log("delete:", response.data);
              self.getUpdate(self.member_id);
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    getUpdate(member_id) {
      console.log("get update-member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_notification, {
          action: "getall",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("notification:", res);
          self.notification1 = res.data;
          console.log("notification1:", self.notification1);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    onNext() {
      this.$router.replace({ name: "FormPlanCareer" });
    },
    onPrevious() {
      this.$router.replace({ name: "FormPlanCareer" });
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
    getUpdateNotify(member_id) {
      console.log("get update-member_id:", member_id);
      var self = this;
      axios
        .post(this.url_api_notification, {
          action: "getallNotify",
          member_id: member_id,
        })
        .then(function (res) {
          console.log("notification:", res);
          var notification_date = self.dayToYear(res.data.notification_date);
          var frequency_name = res.data.frequency_name;
          var message = res.data.message;
          console.log("notification_date:", notification_date);
          console.log("frequency_name:", frequency_name);
          console.log("message:", message);
          // ตรวจสอบวันและทำการแจ้งเตือน
          self.currentDate(notification_date, frequency_name, message);
        })
        .finally(() => {
          self.loading = false;
        });
    },
    currentDate(specific_, frequency, message) {
      console.log("specific-:", specific_);
      var current = new Date();
      var specific = date.extractDate(specific_, "DD/MM/YYYY");
      var unit = "days";

      console.log("specific:", specific_);
      console.log("current.getTime():", current.getTime());
      console.log("specific.getTime():", specific.getTime());

      var diff_days = date.getDateDiff(current, specific, unit);
      console.log(current + "-" + specific + "=" + diff_days);

      var freq = 0;
      if (frequency == "วัน") {
        freq = 1;
        frequency = frequency + " : " + message;
        console.log(frequency);
      } else if (frequency == "สัปดาห์") {
        freq = 7;
        frequency = frequency + " : " + message;
        console.log(frequency);
      } else if (frequency == "เดือน") {
        freq = 30;
        frequency = frequency + " : " + message;
        console.log(frequency);
      }

      console.log("diff_days % freq", parseInt(diff_days) % freq);
      if (parseInt(diff_days) % freq == 0) {
        console.log(
          " แจ้งเตือนราย " + frequency + "(" + freq + "):" + diff_days
        );
        this.$q.notify({
          message: "ครบกำหนดการแจ้งเตือนราย" + frequency,
          color: "primary",
          actions: [
            {
              label: "Reply",
              color: "yellow",
              handler: () => {},
            },
            {
              label: "Dismiss",
              color: "white",
              handler: () => {},
            },
          ],
        });
      } else {
        console.log("ไม่ได้แจ้งเตือน");
      }
    },
  },
  mounted() {
    this.getFrequency();
    var member_id = this.member_id;
    this.getUpdate(member_id);
    this.getUpdateNotify(member_id);
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1/notification_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url_api_plan =
        "http://localhost:85/icp2022/icp_v1/plan_form/api-plan.php";
      this.url_api_individual =
        "http://localhost:85/icp2022/icp_v1/individual_form/api-individual.php";
      this.url_api_notification = local_ + "api-notification.php";
    } else {
      this.url_api_plan = "https://icp2022.net/icp_v1/plan_form/api-plan.php";
      this.url_api_individual =
        "https://icp2022.net/icp_v1/individual_form/api-individual.php";
      this.url_api_notification = www_ + "api-notification.php";
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
