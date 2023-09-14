import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

// ให้ลง chart.js ก่อนลง vue-charttick
import VueChartkick from "vue-chartkick";
import "chartkick/chart.js";
import VueApexCharts from "vue3-apexcharts";
import { Quasar, Dialog } from "quasar";
import "@fortawesome/fontawesome-free/css/all.css";
import "@fortawesome/fontawesome-free/js/all.js";
import Chart from "react-apexcharts";

const app = createApp(App);
app.use(VueApexCharts);
app.use(VueChartkick);
app.use(Chart);
app.use(store);
app.use(router);
app.use(Quasar, {
  plugins: {
    Dialog,
  },
});
app.mount("#app");
