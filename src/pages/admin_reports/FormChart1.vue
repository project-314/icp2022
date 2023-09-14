<template>
  <div class="app">
    <apexcharts
      ref="realtimeChart"
      width="550"
      type="bar"
      :options="chartOptions"
      :series="series"
    ></apexcharts>
    <div>
      <button @click="updateChart">Update!</button>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
export default {
  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        chart: {
          height: 350,
          type: "line",
          stacked: false,
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          width: [1, 1, 4],
        },
        title: {
          text: "XYZ - Stock Analysis (2009 - 2016)",
          align: "left",
          offsetX: 110,
        },
        xaxis: {
          categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016],
        },
        yaxis: [
          {
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#008FFB",
            },
            labels: {
              style: {
                colors: "#008FFB",
              },
            },
            title: {
              text: "Income (thousand crores)",
              style: {
                color: "#008FFB",
              },
            },
            tooltip: {
              enabled: true,
            },
          },
          {
            seriesName: "Income",
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#00E396",
            },
            labels: {
              style: {
                colors: "#00E396",
              },
            },
            title: {
              text: "Operating Cashflow (thousand crores)",
              style: {
                color: "#00E396",
              },
            },
          },
          {
            seriesName: "Revenue",
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: "#FEB019",
            },
            labels: {
              style: {
                colors: "#FEB019",
              },
            },
            title: {
              text: "Revenue (thousand crores)",
              style: {
                color: "#FEB019",
              },
            },
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: "topLeft", // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 30,
            offsetX: 60,
          },
        },
        legend: {
          horizontalAlign: "left",
          offsetX: 40,
        },
      },
      series: [
        {
          name: "Income",
          type: "column",
          data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6],
        },
        {
          name: "Cashflow",
          type: "column",
          data: [1.1, 3, 3.1, 4, 4.1, 4.9, 6.5, 8.5],
        },
        {
          name: "Revenue",
          type: "line",
          data: [20, 29, 37, 36, 44, 45, 50, 58],
        },
      ],
    };
  },
  methods: {
    updateChart() {
      const max = 90;
      const min = 20;

      const newData = this.series[0].data.map(() => {
        return Math.floor(Math.random() * (max - min + 1)) + min;
      });
      const newData1 = this.series[1].data.map(() => {
        return Math.floor(Math.random() * (max - min + 1)) + min;
      });
      const newData2 = this.series[2].data.map(() => {
        return Math.floor(Math.random() * (max - min + 1)) + min;
      });
      // In the same way, update the series option
      this.series = [
        {
          name: "Income",
          data: newData,
        },
        {
          name: "Cashflow",
          data: newData1,
        },
        {
          name: "Revenue",
          data: newData2,
        },
      ];
    },
  },
};
</script>
<style scoped>
button {
  background: #26e6a4;
  border: 0;
  font-size: 16px;
  color: "#fff";
  padding: 10px;
  margin-left: 28px;
}
</style>
