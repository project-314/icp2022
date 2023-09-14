import MainLayout from "layouts/MainLayout.vue";
import IndexPage from "layouts/IndexPage.vue";
// User
// Mangagement
import FormComponent from "pages/forms/FormComponent.vue";
import FormPivotTable from "pages/forms/FormPivotTable.vue";
import FormPlan from "pages/forms/FormPlan.vue";
import FormPlanCareer from "pages/forms/FormPlanCareer.vue";
import FormQualification from "pages/forms/FormQualification.vue";
import FormSelfAssessment from "pages/forms/FormSelfAssessment.vue";
import FormReference from "pages/forms/FormReference.vue";
import FormReport from "pages/forms/FormReport.vue";
import FormRegistration from "pages/forms/FormRegistration.vue";
import FormNotification from "pages/forms/FormNotification.vue";
import ChatBox1 from "pages/forms/ChatBox1.vue";
// registration
import LoginPage from "src/pages/registration/LoginPage.vue";
import LogoutPage from "pages/registration/LogoutPage.vue";
import RegistrationPage from "pages/registration/RegistrationPage.vue";

// Admin
import AdminFormComponent from "pages/admin_forms/FormComponent.vue";
import AdminFormPivotTable from "pages/admin_forms/FormPivotTable.vue";
import AdminFormPlan from "pages/admin_forms/FormPlan.vue";
import AdminFormPlanCareer from "pages/admin_forms/FormPlanCareer.vue";
import AdminFormQualification from "pages/admin_forms/FormQualification.vue";
import AdminFormSelfAssessment from "pages/admin_forms/FormSelfAssessment.vue";
import AdminFormReport from "pages/admin_forms/FormReport.vue";
import AdminFormRegistration from "pages/admin_forms/FormRegistration.vue";
import AdminFormNotification from "pages/admin_forms/FormNotification.vue";
import ImportExcel from "pages/admin_forms/ImportExcel.vue";
// registration
import AdminLoginPage from "src/pages/admin_registration/LoginPage.vue";
import AdminLogoutPage from "pages/admin_registration/LogoutPage.vue";
import AdminRegistrationPage from "pages/admin_registration/RegistrationPage.vue";

// SUPER USER - SUSER
import SuserFormComponent from "pages/super_user_forms/FormComponent.vue";
import SuserFormPivotTable from "pages/super_user_forms/FormPivotTable.vue";
import SuserFormPlan from "pages/super_user_forms/FormPlan.vue";
import SuserFormPlanCareer from "pages/super_user_forms/FormPlanCareer.vue";
import SuserFormQualification from "pages/super_user_forms/FormQualification.vue";
import SuserFormSelfAssessment from "pages/super_user_forms/FormSelfAssessment.vue";
import SuserFormReport from "pages/super_user_forms/FormReport.vue";
import SuserFormRegistration from "pages/super_user_forms/FormRegistration.vue";
import SuserFormNotification from "pages/super_user_forms/FormNotification.vue";

//sub admin forms
import instituteForm from "pages/sub_admin_forms/instituteForm.vue";
import facultyForm from "pages/sub_admin_forms/facultyForm.vue";
import degreeForm from "pages/sub_admin_forms/degreeForm.vue";
import departmentForm from "pages/sub_admin_forms/departmentForm.vue";
import tapFormInstitute from "pages/sub_admin_forms/tapFormInstitute.vue";

//sub admin forms
import s_instituteForm from "pages/sub_super_user_forms/instituteForm.vue";
import s_facultyForm from "pages/sub_super_user_forms/facultyForm.vue";
import s_degreeForm from "pages/sub_super_user_forms/degreeForm.vue";
import s_departmentForm from "pages/sub_super_user_forms/departmentForm.vue";
import s_tapFormInstitute from "pages/sub_super_user_forms/tapFormInstitute.vue";

import tapAdminConstances from "pages/admin_constances/tapAdminConstances.vue";
import tapUserConstances from "pages/user_constances/tapUserConstances.vue";
import tapAdminReports from "pages/admin_reports/tapAdminReports.vue";
import tapSuperUserConstances from "pages/super_user_constances/tapSuperUserConstances.vue";
import tapAdminConstances1 from "pages/admin_constances1/tapAdminConstances1.vue";
import tapSuperReports from "pages/super_reports/tapSuperReports.vue";
import tapUserReports from "pages/user_reports/tapUserReports.vue";

// admin-form-report
import tapRegistration from "pages/admin_forms/registration_reports/tapRegistration.vue";
import tapIndividual from "pages/admin_forms/individual_reports/tapIndividual.vue";
import tapPlan_career from "pages/admin_forms/plan_career_reports/tapPlan_career.vue";
import tapQualification from "pages/admin_forms/qualification_reports/tapQualification.vue";
import tapPlan from "pages/admin_forms/plan_reports/tapPlan.vue";
import tapSelf_assessment from "pages/admin_forms/self_assessment_reports/tapSelf_assessment.vue";
import tapNotification from "pages/admin_forms/Notification_reports/tapNotification.vue";
// super_user_forms
import tapRegistration_ from "pages/super_user_forms/registration_reports/tapRegistration.vue";
import tapIndividual_ from "pages/super_user_forms/individual_reports/tapIndividual.vue";
import tapPlan_career_ from "pages/super_user_forms/plan_career_reports/tapPlan_career.vue";
import tapQualification_ from "pages/super_user_forms/qualification_reports/tapQualification.vue";
import tapPlan_ from "pages/super_user_forms/plan_reports/tapPlan.vue";
import tapSelf_assessment_ from "pages/super_user_forms/self_assessment_reports/tapSelf_assessment.vue";
import tapNotification_ from "pages/super_user_forms/Notification_reports/tapNotification.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: MainLayout,
    children: [
      {
        path: "/",
        name: "IndexPage",
        component: IndexPage,
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
  // User forms
  {
    path: "/FormRegistration",
    name: "FormRegistration",
    component: FormRegistration,
  },
  {
    path: "/FormComponent",
    name: "FormComponent",
    component: FormComponent,
  },
  {
    path: "/FormPivotTable",
    name: "FormPivotTable",
    component: FormPivotTable,
  },
  {
    path: "/FormReport",
    name: "FormReport",
    component: FormReport,
  },
  {
    path: "/FormPlan",
    name: "FormPlan",
    component: FormPlan,
  },
  {
    path: "/FormPlanCareer",
    name: "FormPlanCareer",
    component: FormPlanCareer,
  },
  {
    path: "/FormQualification",
    name: "FormQualification",
    component: FormQualification,
  },
  {
    path: "/FormSelfAssessment",
    name: "FormSelfAssessment",
    component: FormSelfAssessment,
  },
  {
    path: "/LoginPage",
    name: "LoginPage",
    component: LoginPage,
  },
  {
    path: "/LogoutPage",
    name: "LogoutPage",
    component: LogoutPage,
  },
  {
    path: "/RegistrationPage",
    name: "RegistrationPage",
    component: RegistrationPage,
  },
  {
    path: "/FormReference",
    name: "FormReference",
    component: FormReference,
  },
  {
    path: "/FormNotification",
    name: "FormNotification",
    component: FormNotification,
  },
  {
    path: "/ChatBox1",
    name: "ChatBox1",
    component: ChatBox1,
  },
  // Admin forms
  {
    path: "/AdminFormRegistration",
    name: "AdminFormRegistration",
    component: AdminFormRegistration,
  },
  {
    path: "/AdminFormComponent",
    name: "AdminFormComponent",
    component: AdminFormComponent,
  },
  {
    path: "/AdminFormPivotTable",
    name: "AdminFormPivotTable",
    component: AdminFormPivotTable,
  },
  {
    path: "/AdminFormReport",
    name: "AdminFormReport",
    component: AdminFormReport,
  },
  {
    path: "/AdminFormPlan",
    name: "AdminFormPlan",
    component: AdminFormPlan,
  },
  {
    path: "/AdminFormPlanCareer",
    name: "AdminFormPlanCareer",
    component: AdminFormPlanCareer,
  },
  {
    path: "/AdminFormQualification",
    name: "AdminFormQualification",
    component: AdminFormQualification,
  },
  {
    path: "/AdminFormSelfAssessment",
    name: "AdminFormSelfAssessment",
    component: AdminFormSelfAssessment,
  },
  {
    path: "/AdminFormNotification",
    name: "AdminFormNotification",
    component: AdminFormNotification,
  },
  {
    path: "/AdminLoginPage",
    name: "AdminLoginPage",
    component: AdminLoginPage,
  },
  {
    path: "/AdminLogoutPage",
    name: "AdminLogoutPage",
    component: AdminLogoutPage,
  },
  {
    path: "/AdminRegistrationPage",
    name: "AdminRegistrationPage",
    component: AdminRegistrationPage,
  },
  {
    path: "/ImportExcel",
    name: "ImportExcel",
    component: ImportExcel,
  },
  // Super user forms
  {
    path: "/SuserFormRegistration",
    name: "SuserFormRegistration",
    component: SuserFormRegistration,
  },
  {
    path: "/SuserFormComponent",
    name: "SuserFormComponent",
    component: SuserFormComponent,
  },
  {
    path: "/SuserFormPivotTable",
    name: "SuserFormPivotTable",
    component: SuserFormPivotTable,
  },
  {
    path: "/SuserFormReport",
    name: "SuserFormReport",
    component: SuserFormReport,
  },
  {
    path: "/SuserFormPlan",
    name: "SuserFormPlan",
    component: SuserFormPlan,
  },
  {
    path: "/SuserFormPlanCareer",
    name: "SuserFormPlanCareer",
    component: SuserFormPlanCareer,
  },
  {
    path: "/SuserFormQualification",
    name: "SuserFormQualification",
    component: SuserFormQualification,
  },
  {
    path: "/SuserFormSelfAssessment",
    name: "SuserFormSelfAssessment",
    component: SuserFormSelfAssessment,
  },
  {
    path: "/SuserFormNotification",
    name: "SuserFormNotification",
    component: SuserFormNotification,
  },
  // sub admin education
  {
    path: "/instituteForm",
    name: "instituteForm",
    component: instituteForm,
  },
  {
    path: "/tapFormInstitute",
    name: "tapFormInstitute",
    component: tapFormInstitute,
  },
  {
    path: "/facultyForm",
    name: "facultyForm",
    component: facultyForm,
  },
  {
    path: "/degreeForm",
    name: "degreeForm",
    component: degreeForm,
  },
  {
    path: "/departmentForm",
    name: "departmentForm",
    component: departmentForm,
  },
  // sub_super_user education
  {
    path: "/s_instituteForm",
    name: "s_instituteForm",
    component: s_instituteForm,
  },
  {
    path: "/s_tapFormInstitute",
    name: "s_tapFormInstitute",
    component: s_tapFormInstitute,
  },
  {
    path: "/s_facultyForm",
    name: "s_facultyForm",
    component: s_facultyForm,
  },
  {
    path: "/s_degreeForm",
    name: "s_degreeForm",
    component: s_degreeForm,
  },
  {
    path: "/s_departmentForm",
    name: "s_departmentForm",
    component: s_departmentForm,
  },
  // Admin constance
  {
    path: "/tapAdminConstances",
    name: "tapAdminConstances",
    component: tapAdminConstances,
  },
  ,
  // User constances
  {
    path: "/tapUserConstances",
    name: "tapUserConstances",
    component: tapUserConstances,
  },
  // User constances
  {
    path: "/tapAdminReports",
    name: "tapAdminReports",
    component: tapAdminReports,
  },
  //  Super User Constances
  {
    path: "/tapSuperUserConstances",
    name: "tapSuperUserConstances",
    component: tapSuperUserConstances,
  },
  // User constances
  {
    path: "/tapAdminConstances1",
    name: "tapAdminGroupConstances",
    component: tapAdminConstances1,
  },
  // Super user report
  {
    path: "/tapSuperReports",
    name: "tapSuperReports",
    component: tapSuperReports,
  },
  // User report
  {
    path: "/tapUserReports",
    name: "tapUserReports",
    component: tapUserReports,
  },
  // tap Registration
  {
    path: "/tapRegistration",
    name: "tapRegistration",
    component: tapRegistration,
  },
  //tap Individual
  {
    path: "/tapIndividual",
    name: "tapIndividual",
    component: tapIndividual,
  },
  // tap plan career
  {
    path: "/tapPlan_career",
    name: "tapPlan_career",
    component: tapPlan_career,
  },
  //tap qualification
  {
    path: "/tapQualification",
    name: "tapQualification",
    component: tapQualification,
  },
  //tap plan
  {
    path: "/tapPlan",
    name: "tapPlan",
    component: tapPlan,
  },
  //tap self assessment
  {
    path: "/tapSelf_assessment",
    name: "tapSelf_assessment",
    component: tapSelf_assessment,
  },
  //tap Notification
  {
    path: "/tapNotification",
    name: "tapNotification",
    component: tapNotification,
  },
  //----------------------------------
  // tap Registration
  {
    path: "/tapRegistration_",
    name: "tapRegistration_",
    component: tapRegistration_,
  },
  //tap Individual
  {
    path: "/tapIndividual_",
    name: "tapIndividual_",
    component: tapIndividual_,
  },
  // tap plan career
  {
    path: "/tapPlan_career_",
    name: "tapPlan_career_",
    component: tapPlan_career_,
  },
  //tap qualification
  {
    path: "/tapQualification_",
    name: "tapQualification_",
    component: tapQualification_,
  },
  //tap plan
  {
    path: "/tapPlan_",
    name: "tapPlan_",
    component: tapPlan_,
  },
  //tap self assessment
  {
    path: "/tapSelf_assessment_",
    name: "tapSelf_assessment_",
    component: tapSelf_assessment_,
  },
  //tap Notification
  {
    path: "/tapNotification_",
    name: "tapNotification_",
    component: tapNotification_,
  },
];

export default routes;
