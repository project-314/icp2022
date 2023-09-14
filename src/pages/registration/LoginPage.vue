<template>
  <q-layout view="hHh Lpr lFf">
    <q-page-container class="bg-grey-2">
      <q-page
        padding
        class="row items-center justify-center"
        style="background: linear-gradient(#74c588, #0ad13c)"
      >
        <div class="row full-width">
          <div class="col-md-8 offset-md-2 col-xs-12 q-pa-xs">
            <q-card flat class="bg-white text-black">
              <q-card-section class="bg-deep-purple-7">
                <h4 class="text-h5 text-white q-my-xs text-center">
                  {{ title }}
                </h4>
              </q-card-section>
              <div class="row">
                <div class="col-md-5 col-xs-12 q-pa-xs">
                  <q-img
                    placeholder-src="~assets/images/pics_topic_103.jpg"
                    src="~assets/images/pics_topic_103.jpg"
                    spinner-color="white"
                  ></q-img>
                </div>

                <div class="col-md-7 col-xs-12">
                  <div class="q-pa-md">
                    <q-form @submit="OnLogin()" class="q-gutter-md">
                      <q-input
                        ref="email"
                        square
                        clearable
                        v-model="input.username"
                        type="email"
                        lazy-rules
                        :rules="[this.required, this.isEmail, this.short]"
                        label="อีเมล:"
                      >
                        <template v-slot:prepend>
                          <q-icon name="email" />
                        </template>
                      </q-input>
                      <q-input
                        ref="password"
                        square
                        clearable
                        v-model="input.password"
                        :type="passwordFieldType"
                        lazy-rules
                        :rules="[this.required, this.short]"
                        label="รหัสผ่าน:"
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
                      <q-input
                        ref="repassword"
                        v-if="register"
                        square
                        clearable
                        v-model="repassword"
                        :type="passwordFieldType"
                        lazy-rules
                        :rules="[this.required, this.short, this.diffPassword]"
                        label="ใส่รหัสผ่านซ้ำ"
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

                      <div>
                        <q-btn
                          icon="login"
                          label="เข้าระบบ"
                          type="submit"
                          color="primary"
                        />
                        <q-btn
                          icon="assignment_ind"
                          label="ลงทะเบียน"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/RegistrationPage"
                        />
                        <q-btn
                          icon="logout"
                          label="ออก"
                          color="primary"
                          flat
                          class="q-pa-xs"
                          to="/"
                        />
                      </div>
                    </q-form>
                  </div>
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

export default {
  name: "LoginPage2",
  data() {
    return {
      url_api_member: "",
      // ------------------------------------------------------------------------------
      title: "เข้าสู่ระบบ",
      email: "",
      username: "",
      password: "",
      repassword: "",
      register: false,
      passwordFieldType: "password",
      btnLabel: "กดปุ่ม",
      visibility: false,
      visibilityIcon: "visibility",
      myAuthenticate: ref(false),
      emp_id: Array,
      input: {
        username: "",
        password: "",
      },
      member: {
        member_id: 0,
        full_name: "",
        status: "",
      },
      $q: useQuasar(),
    };
  },

  methods: {
    OnLogin() {
      if (this.input.username != "" && this.input.password != "") {
        this.checkMember();
      } else {
        console.log("A username and password must be present");
      }
    },
    checkMember() {
      console.log(" ตรวจสอบข้อมูลสมาชิค ");
      var self = this;
      axios
        .post(this.url_api_member, {
          action: "checkMember",
          user: this.input.username,
          pass: this.input.password,
        })
        .then(function (res) {
          console.log("data:", res.data);
          if (res.data.length > 0) {
            var member_id = res.data.map((item) => item.member_id)[0];
            var full_name = res.data.map((item) => item.full_name)[0];
            var status = res.data.map((item) => item.status)[0];
            self.storeCommit(member_id, full_name, status);
          } else {
            console.log("The username and / or password is incorrect");
            self.$q
              .dialog({
                title: "คำเตือน",
                message:
                  "ชื่อผู้ใช้/รหัสผ่านไม่ถูกต้อง หรืออีเมลย์ยังไม่ได้รับการยืนยัน",
                persistent: true,
              })
              .onOk(() => {
                self.input.username = "";
                self.input.password = "";
              });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    storeCommit(member_id, full_name, status) {
      console.log("login:", member_id);
      console.log("login:", full_name);
      console.log("login:", status);
      if (member_id != 0 && full_name != "" && status != "") {
        this.myAuthenticate = true;
        this.$store.commit("setMyAuthenticate", this.myAuthenticate);
        this.$store.commit("setMyMember_id", member_id);
        this.$store.commit("setMyName", full_name);
        this.$store.commit("setMyStatus", status);
        this.$router.replace({ path: "/" });
      }
    },
    required(val) {
      return (val && val.length > 0) || "ช่องที่ต้องกรอก";
    },
    diffPassword(val) {
      const val2 = this.password;
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
    onSubmit() {
      console.log("click submit");
    },
    onReset() {
      this.name = null;
      this.password = null;
    },
    onRegister() {
      this.$router.replace({ name: "RegistrationPage" });
    },
    OnCancel() {
      this.$router.replace({ name: "home" });
    },
    createState() {
      this.$store.commit("setMyAuthenticate", false);
      this.$store.commit("setMyMember_id", 0);
      this.$store.commit("setMyEmployee_id", 0);
      this.$store.commit("setMyName", "");
      this.$store.commit("setMyStatus", "");
    },
  },
  mounted() {
    this.createState();
  },
  created() {
    var www = this.$store.getters.myWWW;
    var folder = "icp_v1/signin_form/";
    var local_ = "http://localhost:85/icp2022/" + folder;
    var www_ = "https://icp2022.net/" + folder;

    if (!www) {
      this.url_api_member = local_ + "api-member.php";
    } else {
      this.url_api_member = www_ + "api-member.php";
    }
  },
};
</script>
