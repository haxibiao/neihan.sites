<template>
  <card class="flex flex-col items-center justify-center">
    <div class="px-3 py-3">
      <h1 class="mr-3 text-base text-80 font-bold text-center">{{ card.name }}</h1>
      <doughnut-charts :chart-data="chartData" :chart-options="chartOptions"></doughnut-charts>
    </div>
  </card>
</template>

<script>
import DoughnutCharts from "./DoughnutCharts";

const maxData = 5;
export default {
  props: ["card"],

  components: {
    DoughnutCharts
  },

  data() {
    return {
      chartData: [
        {
          backgroundColor: [
            "#41B883",
            "#E46651",
            "#00D8FF",
            "#DD1B16",
            "#f67e0f"
          ],
          data: []
        }
      ],
      chartOptions: []
    };
  },

  methods: {
    initView: function() {
      this.chartData[0].data = this.card.data.value;
      this.chartOptions = this.card.data.options;
      //填充background
      if (this.chartOptions.length > maxData) {
        this.chartData[0].backgroundColor = [];
        for (var i = 0; i < this.chartOptions.length; i++) {
          this.chartData[0].backgroundColor.push(this.dynamicColors());
        }
      }
    },
    dynamicColors: function() {
      var r = Math.floor(Math.random() * 255);
      var g = Math.floor(Math.random() * 255);
      var b = Math.floor(Math.random() * 255);
      return "rgb(" + r + "," + g + "," + b + ")";
    }
  },

  created() {
    this.initView();
  }
};
</script>
