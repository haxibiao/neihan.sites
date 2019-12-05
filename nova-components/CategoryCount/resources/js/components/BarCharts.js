import {
  Bar
} from 'vue-chartjs';
export default {
  extends: Bar,
  props: {
    chartLabels: null,
    chartData: null,
    chartLegend:null,
    chartColor:null
  },
  mounted: function mounted() {
    // console.log('barchat测试Legend' + this.chartLabels);
    // console.log('barchat测试color' + this.chartColor);
    this.renderChart({
      labels: this.chartLabels,
      datasets: [{
        label: this.chartLegend,
        backgroundColor: this.chartColor,
        data: this.chartData
      }]
    }, {
      responsive: true,
      maintainAspectRatio: false
    });
  }
};