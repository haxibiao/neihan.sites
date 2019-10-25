<template>
  <card class="flex flex-col items-center justify-center">
    <div class="px-3 py-3">
      <h1 class="text-center text-3xl text-80 font-light">{{card.name}}</h1>
      <bar-charts :chartLabels="chartLabels" :chart-data="chartData"></bar-charts>
    </div>
  </card>
</template>


<script>
import BarCharts from "./BarCharts.js";
export default {
  props: ["card"],
  data() {
    return {
      chartLabels: [],
      chartData: []
    };
  },
  components: {
    BarCharts
  },
  methods: {
    initView: function() {
      this.chartLabels = this.card.data.name;
      this.chartData = this.card.data.data;
    }
  },
  created() {
    this.initView();
  }
};
</script>
