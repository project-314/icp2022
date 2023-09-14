<template>
  <q-layout view="hHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title>
          {{
            $store.getters.myStatus != "" ? $store.getters.myStatus + ":" : ""
          }}
          <label for="myName">{{ $store.getters.myName }}</label>
        </q-toolbar-title>
        <div>
          <q-toolbar-title shrink class="text-weight-bold">
            <q-btn
              v-if="!$store.getters.myAuthenticate"
              label="เข้าสู่ระบบ"
              flat
              round
              dense
              icon="login"
              to="/LoginPage"
              ><q-tooltip :target="true"> เข้าสู่ระบบ </q-tooltip>
            </q-btn>
            <!-- ออกจากระบ -->
            <q-btn
              v-if="$store.getters.myAuthenticate"
              label="ออกจากระบบ"
              flat
              round
              dense
              icon="logout"
              to="/LogoutPage"
            >
              <q-tooltip :target="true"> ออกจากระบบ </q-tooltip>
            </q-btn>
          </q-toolbar-title>
        </div>
      </q-toolbar>
    </q-header>
    <!-- ผู้ใช้ทั่วไป -->
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      v-if="
        $store.getters.myAuthenticate == true &&
        $store.getters.myStatus == 'user'
      "
    >
      <q-list>
        <q-item-label header>
          Individual Career Planning (ผู้ใช้ระบบ)</q-item-label
        >
        <!-- ค่าคงที่ -->
        <q-item clickable dense>
          <q-item-section avatar>
            <q-icon name="settings_accessibility"
          /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการค่าคงที่</q-item-label>
            <q-item-label caption>สถาบัน คณะ ระดับ สาขา</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in userLinks"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
        <!-- เมนูหลัก -->
        <EssentialLink
          v-for="link in userLinks1"
          :key="link.title"
          v-bind="link"
        />
        <!-- รายงาน -->
        <q-item clickable dense>
          <q-item-section avatar> <q-icon name="receipt" /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการรายงาน</q-item-label>
            <q-item-label caption>พัฒนาตนเอง ประเมินตนเอง</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in userLinks2"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
      </q-list>
    </q-drawer>
    <!-- ผู้ดูแลระบบ -->
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      v-if="
        $store.getters.myAuthenticate == true &&
        $store.getters.myStatus == 'admin'
      "
    >
      <q-list>
        <q-item-label header>
          Individual Career Planning (ผู้ดูแลระบบ)</q-item-label
        >
        <!-- ค่าคงที่ -->
        <q-item clickable dense>
          <q-item-section avatar>
            <q-icon name="settings_accessibility"
          /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการค่าคงที่</q-item-label>
            <q-item-label caption>สถาบัน คณะ ระดับ สาขา</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in administrationLinks"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
        <!-- เมนูหลัก -->
        <EssentialLink
          v-for="link in administrationLinks1"
          :key="link.title"
          v-bind="link"
        />
        <!-- รายงาน -->
        <q-item clickable dense>
          <q-item-section avatar> <q-icon name="receipt" /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการรายงาน</q-item-label>
            <q-item-label caption>พัฒนาตนเอง ประเมินตนเอง</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in administrationLinks2"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
      </q-list>
    </q-drawer>
    <!-- ผู้ดูแลกลุ่ม -->
    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      v-if="
        $store.getters.myAuthenticate == true &&
        $store.getters.myStatus == 'suser'
      "
    >
      <q-list>
        <q-item-label header>
          Individual Career Planning (ผู้ดูแลกลุ่ม)</q-item-label
        >
        <!-- ค่าคงที่ -->
        <q-item clickable dense>
          <q-item-section avatar>
            <q-icon name="settings_accessibility"
          /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการค่าคงที่</q-item-label>
            <q-item-label caption>สถาบัน คณะ ระดับ สาขา</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in superUserLinks"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
        <!-- เมนูหลัก -->
        <EssentialLink
          v-for="link in superUserLinks1"
          :key="link.title"
          v-bind="link"
        />
        <!-- รายงาน -->
        <q-item clickable dense>
          <q-item-section avatar> <q-icon name="receipt" /></q-item-section>
          <q-item-section>
            <q-item-label>การจัดการรายงาน</q-item-label>
            <q-item-label caption>พัฒนาตนเอง ประเมินตนเอง</q-item-label>
          </q-item-section>
          <q-item-section side>
            <q-icon name="keyboard_arrow_right"></q-icon>
          </q-item-section>
          <q-menu anchor="bottom right" self="bottom right">
            <q-list>
              <EssentialLink
                v-for="link in superUserLinks2"
                :key="link.title"
                v-bind="link"
              />
            </q-list>
          </q-menu>
        </q-item>
        <q-separator></q-separator>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import EssentialLink from "components/EssentialLink.vue";
import { date } from "quasar";
import { useQuasar } from "quasar";
import axios from "axios";

// ผู้ใช้ทั่วไป
const userList = [
  {
    title: "การตั้งค่าส่วนตัว",
    caption: "ข้อมูลการลงทะเบียน",
    icon: "perm_identity",
    link: "/FormRegistration",
  },
  {
    title: "การกำหนดข้อมูลพื้นฐาน",
    caption: "ข้อมูลอาชีพ คุณสมบัติ/ทักษะ",
    icon: "perm_identity",
    link: "/tapUserConstances",
  },
];
const userList1 = [
  {
    title: "กรอกข้อมูลส่วนตัว",
    caption: "การศึกษา ความพิการ",
    icon: "lock_outline",
    link: "/FormComponent",
  },
  {
    title: "กำหนดอาชีพเป้าหมาย",
    caption: "อาชีพในอนาคต",
    icon: "work_history",
    link: "/FormPlanCareer",
  },
  {
    title: "กำหนดคุณสมบัติ/ทักษะ",
    caption: "คุณสมบัติและทักษะ เป้าหมาย ระดับ",
    icon: "fact_check",
    link: "/FormQualification",
  },
  {
    title: "การพัฒนาตนเอง",
    caption: "ศึกษาเรียนรู้ ฝึกปฏิบัติ",
    icon: "post_add",
    link: "/FormPlan",
  },
  {
    title: "การประเมินตนเอง",
    caption: "เดือน ระดับ",
    icon: "checklist_rtl",
    link: "/FormSelfAssessment",
  },
  {
    title: "กำหนดวันเริ่มแจ้งเตือน",
    caption: "วันเริ่ม วัน สัปดาห์ เดือน",
    icon: "notifications_active",
    link: "/FormNotification",
  },
  {
    title: "แชท",
    caption: "การเเนะนำ",
    icon: "notifications_active",
    link: "/ChatBox1",
  },
];
const userList2 = [
  {
    title: "รายงานข้อมูลตามเงื่อนไข",
    caption: "รายงานข้อมูลตามเงื่อนไข",
    icon: "summarize",
    link: "/tapUserReports",
  },
];
// ผู้ดูแลระบบ
const adminList = [
  {
    title: "จัดการลงทะเบียน",
    caption: "ข้อมูลการลงทะเบียน",
    icon: "perm_identity",
    // link: "/AdminFormRegistration",
    link: "/tapRegistration",
  },
  {
    title: "จัดการข้อมูลการศึกษา",
    caption: "สถาบัน คณะ ปริญญา สาขาวิชา",
    icon: "perm_identity",
    link: "/tapFormInstitute",
  },
  {
    title: "จัดการค่าคงที่",
    caption:
      "โครงการ ความพิการ ค่าเป้าหมาย ค่าการประเมิน ค่าระดับความสำคัญ ความสำคัญ กลุ่มอาชีพ กลุ่มคุณสมบัติ/ทักษะ",
    icon: "perm_identity",
    link: "/tapAdminConstances",
  },
  {
    title: "จัดการอาชีพ/คุณสมบัติ/ทักษะ",
    caption: "อาชีพ คุณสมบัติ/ทักษะ",
    icon: "perm_identity",
    link: "/tapAdminConstances1",
  },
];
const adminList1 = [
  {
    title: "จัดการข้อมูลส่วนตัว",
    caption: "การศึกษา ความพิการ",
    icon: "lock_outline",
    // link: "/AdminFormComponent",
    link: "/tapIndividual",
  },
  {
    title: "จัดการอาชีพเป้าหมาย",
    caption: "อาชีพในอนาคต",
    icon: "work_history",
    // link: "/AdminFormPlanCareer",
    link: "/tapPlan_career",
  },
  {
    title: "จัดการคุณสมบัติ/ทักษะ",
    caption: "คุณสมบัติและทักษะ เป้าหมาย ระดับ",
    icon: "fact_check",
    // link: "/AdminFormQualification",
    link: "/tapQualification",
  },
  {
    title: "จัดการพัฒนาตนเอง",
    caption: "ศึกษาเรียนรู้ ฝึกปฏิบัติ",
    icon: "post_add",
    // link: "/AdminFormPlan",
    link: "/tapPlan",
  },
  {
    title: "จัดการประเมินตนเอง",
    caption: "เดือน ระดับ",
    icon: "checklist_rtl",
    // link: "/AdminFormSelfAssessment",
    link: "/tapSelf_assessment",
  },
  {
    title: "จัดการแจ้งเตือน",
    caption: "วันเริ่ม วัน สัปดาห์ เดือน",
    icon: "notifications_active",
    // link: "/AdminFormNotification",
    link: "/tapNotification",
  },
  {
    title: "แชท",
    caption: "วันเริ่ม วัน สัปดาห์ เดือน",
    icon: "notifications_active",
    // link: "/AdminFormNotification",
    link: "/ChatBox1",
  },
];
const adminList2 = [
  {
    title: "รายงานข้อมูลตามเงื่อนไข",
    caption: "รายงานข้อมูลตามเงื่อนไข",
    icon: "summarize",
    link: "/tapAdminReports",
  },
];
// ผู้ดูแลกลุ่ม
const superUserList = [
  {
    title: "จัดการลงทะเบียน",
    caption: "ข้อมูลการลงทะเบียน",
    icon: "perm_identity",
    link: "/SuserFormRegistration",
  },
  {
    title: "จัดการข้อมูลการศึกษา",
    caption: "สถาบัน คณะ ปริญญา สาขาวิชา",
    icon: "perm_identity",
    link: "/s_tapFormInstitute",
  },
  {
    title: "จัดการอาชีพ/คุณสมบัติ/ทักษะ",
    caption: "อาชีพ คุณสมบัติ/ทักษะ",
    icon: "perm_identity",
    link: "/tapSuperUserConstances",
  },
];
const superUserList1 = [
  {
    title: "จัดการข้อมูลส่วนตัว",
    caption: "การศึกษา ความพิการ",
    icon: "lock_outline",
    // link: "/SuserFormComponent",
    link: "/tapIndividual_",
  },
  {
    title: "จัดการอาชีพเป้าหมาย",
    caption: "อาชีพในอนาคต",
    icon: "work_history",
    // link: "/SuserFormPlanCareer",
    link: "/tapPlan_career_",
  },
  {
    title: "จัดการคุณสมบัติ/ทักษะ",
    caption: "คุณสมบัติและทักษะ เป้าหมาย ระดับ",
    icon: "fact_check",
    // link: "/SuserFormQualification",
    link: "/tapQualification_",
  },
  {
    title: "จัดการพัฒนาตนเอง",
    caption: "ศึกษาเรียนรู้ ฝึกปฏิบัติ",
    icon: "post_add",
    // link: "/SuserFormPlan",
    link: "/tapPlan_",
  },
  {
    title: "จัดการประเมินตนเอง",
    caption: "เดือน ระดับ",
    icon: "checklist_rtl",
    // link: "/SuserFormSelfAssessment",
    link: "/tapSelf_assessment_",
  },
  {
    title: "จัดการแจ้งเตือน",
    caption: "วันเริ่ม วัน สัปดาห์ เดือน",
    icon: "notifications_active",
    // link: "/SuserFormNotification",
    link: "/tapNotification_",
  },
];
const superUserList2 = [
  {
    title: "รายงานข้อมูลตามเงื่อนไข",
    caption: "รายงานข้อมูลตามเงื่อนไข",
    icon: "summarize",
    link: "/tapSuperReports",
  },
];
export default defineComponent({
  name: "MainLayout",
  data() {
    return {
      url_api_notification: "",
      member_id: this.$store.getters.myMember_id,
      $q: useQuasar(),
      loading: false,
    };
  },
  components: {
    EssentialLink,
  },
  methods: {
    onLogin() {
      this.$router.replace({ name: "LoginPage" });
    },
    onLogout() {
      this.$router.replace({ name: "LogoutPage" });
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
          var notification_date = res.data.notification_date;
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
          progress: true,
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
  setup() {
    const leftDrawerOpen = ref(false);

    return {
      userLinks: userList,
      userLinks1: userList1,
      userLinks2: userList2,
      administrationLinks: adminList,
      administrationLinks1: adminList1,
      administrationLinks2: adminList2,
      superUserLinks: superUserList,
      superUserLinks1: superUserList1,
      superUserLinks2: superUserList2,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
  created() {
    var www = this.$store.getters.myWWW;
    if (!www) {
      console.log("!www:", !www);
      this.url_api_notification =
        "http://localhost:85/icp2022/icp_v1/notification_form/api-notification.php";
    } else {
      console.log("www:", www);
      this.url_api_notification =
        "https://icp2022.net/icp_v1/notification_form/api-notification.php";
    }
  },
  mounted() {
    var member_id = this.member_id;
    this.getUpdateNotify(member_id);
  },
});
</script>
