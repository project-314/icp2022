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
                    @submit="submitForm"
                    @reset="resetForm"
                    method="post"
                    class="q-gutter-md"
                  >
                    <!-- ชื่อ -->
                    <div class="row">
                      <!-- ชื่อ-สกุล -->
                      <div class="col-md-12 col-xs-12 q-pa-xs">
                        <q-input
                          color="white"
                          bg-color="blue-5"
                          standout
                          bottom-slots
                          v-model="member.full_name"
                          label="ชื่อ-สกุล *"
                          clearable
                          :rules="[
                            (val) =>
                              (val && val.length > 0) || 'ต้องใส่ชื่อ-สกุล',
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
                    <!-- บทบาทของผู้ใช้ระบบ -->
                    <div class="row">
                      <!-- บทบาทของผู้ใช้ระบบ +อีเมล + ยืนยัน-->
                      <div class="col-md-4 col-xs-12 q-pa-xs">
                        <q-select
                          color="blue-3"
                          v-model="member_role"
                          :options="member_roles.options"
                          label="บทบาท *"
                          stack-label
                        >
                          <template v-slot:prepend>
                            <q-icon name="school" />
                          </template>
                          <template v-slot:selected>
                            <q-chip
                              v-if="member_role"
                              dense
                              square
                              color="white"
                              text-color="primary"
                              class="q-pa-xs"
                            >
                              {{ member_role.label }}
                            </q-chip>
                            <q-badge v-else>*none*</q-badge>
                          </template>
                          <template v-if="member_role" v-slot:append>
                            <q-icon
                              name="cancel"
                              @click.stop.prevent="member_role = null"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-select>
                      </div>
                      <!-- อีเมล -->
                      <div class="col-md-5 col-xs-12 q-pa-xs">
                        <q-input
                          color="white"
                          bg-color="blue-5"
                          standout
                          bottom-slots
                          v-model="member.email"
                          label="อีเมล *"
                          clearable
                          type="email"
                          lazy-rules
                          :rules="[this.required, this.isEmail, this.short]"
                        >
                          <template v-slot:prepend>
                            <q-icon name="work_history" />
                          </template>
                          <template v-slot:append>
                            <q-icon name="favorite" />
                          </template>
                        </q-input>
                      </div>
                      <!-- ยืนยันอีเมลย์ -->
                      <div class="col-md-3 col-xs-12 q-pa-xs">
                        <q-checkbox
                          v-model="member.is_verified"
                          val="member.is_verified"
                          label="ยืนยันอีเมลย์"
                          color="teal"
                          true-value="1"
                          false-value="0"
                        />
                      </div>
                    </div>
                    <div class="row">
                      <!-- รหัสผ่าน -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          ref="password"
                          v-if="register"
                          square
                          clearable
                          v-model="member.password"
                          :type="passwordFieldType"
                          lazy-rules
                          :rules="[this.required, this.short]"
                          label="รหัสผ่าน *"
                        >
                          <template v-slot:prepend>
                            <q-icon name="lock" />
                          </template>
                          <template v-slot:append>
                            <q-icon
                              :name="visibilityIcon"
                              @click="switchVisibility"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-input>
                      </div>
                      <!-- ยืนยันรหัสผ่าน -->
                      <div class="col-md-6 col-xs-12 q-pa-xs">
                        <q-input
                          ref="repassword"
                          v-if="register"
                          square
                          clearable
                          v-model="member.repassword"
                          :type="passwordFieldType"
                          lazy-rules
                          :rules="[
                            this.required,
                            this.short,
                            this.diffPassword,
                          ]"
                          label="ยืนยันรหัสผ่าน *"
                        >
                          <template v-slot:prepend>
                            <q-icon name="lock" />
                          </template>
                          <template v-slot:append>
                            <q-icon
                              :name="visibilityIcon"
                              @click="switchVisibility"
                              class="cursor-pointer"
                            />
                          </template>
                        </q-input>
                      </div>
                    </div>
                    <!-- ปุ่มควบคุม -->
                    <div class="row">
                      <div
                        class="col-md-12 col-xs-12 q-pa-xs row justify-center"
                      >
                        <q-btn
                          :label="btnLabel"
                          type="submit"
                          color="primary"
                          icon="save"
                        />
                        <q-btn
                          label="ยกเลิก"
                          type="reset"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          icon="clear"
                        />
                        <q-btn
                          icon="logout"
                          label="ออก"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/"
                        />
                        <q-btn
                          color="primary"
                          no-caps
                          flat
                          icon="skip_next"
                          label="ไปฟอร์มการจัดการข้อมูลการศึกษา"
                          class="q-pa-xs"
                          to="/tapFormInstitute"
                        >
                          <q-tooltip class="bg-accent"
                            >ไปฟอร์มกรอกข้อมูลส่วนตัว</q-tooltip
                          >
                        </q-btn>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-xs-12 q-ma-xs">
                        <div class="q-pa-xs">
                          <div class="row items-center q-gutter-sm q-mb-md">
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
                          </div>
                          <q-table
                            title="ข้อมูลสมาชิค"
                            :rows="members1"
                            :columns="columns"
                            virtual-scroll
                            v-model:pagination="pagination"
                            :rows-per-page-options="[0]"
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
                                placeholder="ค้นหาสมาชิค"
                              >
                                <template v-slot:append>
                                  <q-icon name="search" />
                                </template>
                              </q-input>
                              <!-- <q-select
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
                              /> -->
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
                                  @click="editUser(props.row.member_id)"
                                ></q-btn>
                                <q-btn
                                  icon="delete"
                                  label="ลบ"
                                  @click="
                                    deleteUser(
                                      props.row.member_id,
                                      props.row.full_name
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

  <div class="vue-tempalte"></div>
  <div class="py-2"></div>
</template>

<script>
import axios from "axios";
import { useQuasar } from "quasar";
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
  data() {
    return {
      file_export: "",
      url_api_member: "",
      // ------------------------------------------------------------------------------
      title: "การลงทะเบียน(ผู้ดูแลระบบ)",
      members: Array,
      register: true,
      filter: ref(""),
      loading: ref(false),
      member: {
        member_id: this.$store.getters.myMember_id,
        full_name: "",
        email: "",
        password: "",
        repassword: "",
        status: "",
        is_verified: ref("0"),
      },
      visibleColumns: ref([
        "actions",
        "mem_id",
        "full_name",
        "e-mail",
        "password",
        "status",
        "is_verified",
      ]),
      columns: [
        { name: "actions", align: "center", label: "Action" },
        {
          name: "mem_id",
          required: true,
          label: "รหัสสมาชิค",
          align: "center",
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
          name: "e-mail",
          align: "left",
          label: "อีเมลย์",
          field: "email",
          sortable: true,
        },
        {
          name: "password",
          align: "left",
          label: "รหัสผ่าน",
          field: "password",
          sortable: true,
        },
        {
          name: "status",
          align: "center",
          label: "บทบาท",
          field: "status",
          sortable: true,
        },
        // is_verified
        {
          name: "is_verified",
          align: "center",
          label: "ยืนยันอีเมลย์",
          field: "is_verified",
          sortable: true,
        },
      ],
      members1: [],
      $q: useQuasar(),
      passwordFieldType: "password",
      visibility: false,
      visibilityIcon: "visibility",
      checkUser: ref(false),
      // กำหนดทางเลือกตาราง
      admin: ref(true),
      suser: ref(true),
      user: ref(true),
      member_roles_: {
        options: [],
      },
      member_roles: {
        options: [
          {
            label: "ผู้ดูแลระบบ",
            value: "admin",
          },
          {
            label: "ผู้ดูแลกลุ่ม",
            value: "suser",
          },
          {
            label: "ผู้ใช้ระบบ",
            value: "user",
          },
        ],
      },
      member_role: ref({
        label: "",
        value: "",
      }),
      btnLabel: "เพิ่มข้อมูล",
    };
  },
  components: {},
  methods: {
    verifiedEmail() {
      console.log("ยืนยัน E-mail");
      console.log("ยืนยัน:", this.member.email);
      var msg =
        "กรุณากดลิงก์การยืนยันอีเมลย์เข้าสู่ระบบ ICP : <a href=" +
        this.url_api_verified_mail +
        '?email="' +
        this.member.email +
        '">กดเพื่อยืนยันตัวตน</a>';
      console.log("ข้อความ:", msg);
      axios
        .post(this.url_api_mail, {
          action: "email",
          to_email: this.member.email,
          subject: "การยืนยันอีเมลย์ การสมัครสมาชิคระบบ ICP",
          from_email: "somnuk.sin2@gmail.com",
          message: msg,
        })
        .then((res) => {
          console.log("ทดสอบการส่ง E-mail:", res.data);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    exportTable() {
      console.log("Export excel");
      var columns = this.columns;
      var rows = this.$refs.tbMember.filteredSortedRow;
      console.log("columns:", columns);
      console.log("rows:", rows);
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
            this.checkNewMemeber(this.member.email);
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
              .post(this.url_api_member, {
                action: "update",
                member_id: this.member.member_id,
                full_name: this.member.full_name,
                email: this.member.email,
                password: this.member.password,
                status: this.member_role.value,
                is_verified: this.member.is_verified,
              })
              .then((response) => {
                console.log("update:", response.data);
                this.isEdit = false;
                console.log("isEdit:", this.isEdit);
                this.btnLabel = "เพิ่มข้อมูล";
                this.getUpdate();
                if (this.member.is_verified == 0) this.checkNewMemeber(email);
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
    getAllUser() {
      console.log(" แสดงข้อมูลทั้งหมด ");
      var self = this;
      axios
        .post(this.url_api_member, {
          action: "getall",
        })
        .then(function (res) {
          console.log("Registration:", res.data);
          self.members = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    addNewMember() {
      if (this.member.is_verified) {
        console.log("is_verified:", this.member.is_verified);
        this.$q
          .dialog({
            title: "แจ้งเพื่อทราบ",
            message: "อีเมล:" + this.member.email + " ถูกยืนยันแล้ว",
            persistent: true,
          })
          .onOk(() => {});
      } else {
        console.log("is_verified:", this.member.is_verified);
        this.$q
          .dialog({
            title: "แจ้งเพื่อทราบ",
            message: "อีเมล:" + this.member.email + " ยังไม่ถูกยืนยัน",
            persistent: true,
          })
          .onOk(() => {});
      }

      const newMember = {
        full_name: this.member.full_name,
        email: this.member.email,
        password: this.member.password,
        status: this.member_role.value,
        is_verified: this.member.is_verified,
      };
      this.$emit("saveData", newMember);
      console.log("บันทึกข้อมูล", newMember);
      axios
        .post(this.url_api_member, {
          action: "insert",
          full_name: this.member.full_name,
          email: this.member.email,
          password: this.member.password,
          status: this.member_role.value,
          is_verified: this.member.is_verified,
        })
        .then((res) => {
          console.log(res);
          this.getUpdate();
          if (this.member.is_verified == "0") {
            this.verifiedEmail();
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    checkNewMemeber(email) {
      console.log(" ตรวจสอบผู้ใช้ ", email);
      var self = this;
      axios
        .post(this.url_api_member, {
          action: "checkUser",
          email: email,
        })
        .then(function (res) {
          var isMember = res.data.length > 0;
          if (isMember) {
            console.log("Is member:", isMember);
            self.$q
              .dialog({
                title: "แจ้งเพื่อทราบ",
                message: "อีเมล:" + email + " เป็นสมาชิคแล้ว",
                persistent: true,
              })
              .onOk(() => {});
          } else {
            console.log("New Member");
            self.addNewMember();
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    getUpdate() {
      console.log(
        "แสดงข้อมูลสมาชิค:",
        (this.admin ? "admin" : "") +
          "-" +
          (this.suser ? "suser" : "") +
          "-" +
          (this.user ? "user" : "")
      );
      var self = this;
      axios
        .post(this.url_api_member, {
          admin: this.admin ? "admin" : "",
          suser: this.suser ? "suser" : "",
          user: this.user ? "user" : "",
          action: "getall",
        })
        .then(function (res) {
          console.log("Registration:", res.data);
          self.members1 = res.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    editUser(id) {
      console.log("Edit data");
      this.btnLabel = "แก้ไขข้อมูล";
      this.isEdit = true;
      var self = this;
      axios
        .post(this.url_api_member, {
          action: "edit",
          id: id,
        })
        .then(function (response) {
          console.log("Edit:", response.data);
          self.member.member_id = response.data.member_id;
          self.member.full_name = response.data.full_name;
          self.member.email = response.data.email;
          self.member.password = response.data.password;
          self.member.status = response.data.status;
          self.member_role.value = response.data.status;
          self.member.is_verified = response.data.is_verified == 1 ? "1" : "0";
          if (self.member_role.value == "admin") {
            self.member_role.label = "ผู้ดูแลระบบ";
          } else if (self.member_role.value == "suser") {
            self.member_role.label = "ที่ปรึกษา";
          } else if (self.member_role.value == "user") {
            self.member_role.label = "ผู้ใช้ระบบ";
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    resetForm() {
      console.log("ยกเลิกการบันทึกข้อมูล");
      this.member.member_id = 0;
      this.member.full_name = "";
      this.member.email = "";
      this.member.password = "";
      this.member.repassword = "";
      this.member_role.value = "";
      this.member_role.label = "";
      this.member.is_verified = ref("0");

      this.member_roles = {
        options: [
          {
            label: "ผู้ดูแลระบบ",
            value: "admin",
          },
          {
            label: "ผู้ดูแลกลุ่ม",
            value: "suser",
          },
          {
            label: "ผู้ใช้ระบบ",
            value: "user",
          },
        ],
      };
    },
    deleteUser(id, name) {
      this.$q
        .dialog({
          title: "ยืนยัน",
          message: "คุณต้องการลบ [" + name + "] หรือไม่ ?",
          cancel: true,
          persistent: true,
        })
        .onOk(() => {
          var self = this;
          axios
            .post(this.url_api_member, {
              action: "delete",
              id: id,
            })
            .then(function (response) {
              console.log(response);
              // self.resetForm();
              // self.getAllUser();
              self.getUpdate();
            })
            .catch(function (error) {
              console.log(error);
            });
        });
    },
    required(val) {
      return (val && val.length > 0) || "ช่องที่ต้องกรอก";
    },
    diffPassword(val) {
      const val2 = this.member.password;
      return (val && val === val2) || "รหัสผ่านไม่ตรงกัน!";
    },
    short(val) {
      return (val && val.length > 3) || "ค่าสั้นเกินไป";
    },
    isEmail(val) {
      const emailPattern =
        /^(?=[a-zA-Z0-9@._%+-]{6,254}$)[a-zA-Z0-9._%+-]{1,64}@(?:[a-zA-Z0-9-]{1,63}\.){1,8}[a-zA-Z]{2,63}$/;
      return emailPattern.test(val) || "กรุณาใส่อีเมลที่ถูกต้อง";
    },
    switchTypeForm() {
      this.register = !this.register;
      this.title = this.register ? "ผู้ใช้ใหม่" : "การอนุญาต";
      this.btnLabel = this.register ? "การลงทะเบียน" : "ทางเข้า";
    },
    switchVisibility() {
      this.visibility = !this.visibility;
      this.passwordFieldType = this.visibility ? "text" : "password";
      this.visibilityIcon = this.visibility ? "visibility_off" : "visibility";
    },
    OnCancel() {
      this.$router.replace({ name: "home" });
    },
    filterMember_role(val, update) {
      if (val === "") {
        update(() => {
          this.member_roles.options = this.member_roles_.options;
          console.log("member_roles_.options:", this.member_roles_.options);
        });
        return;
      }
      update(() => {
        const needle = val.toLowerCase();
        console.log("needle:", needle);
        this.member_roles.options = this.member_roles_.options.filter(
          (v) => v.label.indexOf(needle) > -1
        );
        console.log("member_roles_.options:", this.member_roles_.options);
      });
    },
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1_admin/registration_form/";
    var folder1 = "icp_v1_admin/registration_form1/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;
    var local_1 = "http://localhost:85/icp2022/" + folder1;
    var www_1 = "https://icp2022.net/" + folder1;
    if (!www) {
      this.url_api_member = local_ + "api-member.php";
      this.url_api_mail = local_1 + "mail.php";
      this.url_api_verified_mail = local_ + "verified_mail.php";
    } else {
      this.url_api_member = www_ + "api-member.php";
      this.url_api_mail = www_1 + "mail.php";
      this.url_api_verified_mail = www_ + "verified_mail.php";
    }
  },
  setup() {
    return {
      pagination: ref({
        rowsPerPage: 0,
      }),
    };
  },
  mounted() {
    this.getUpdate();
  },
};
</script>
<style scoped></style>
