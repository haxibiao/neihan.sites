import {
  Bar
} from 'vue-chartjs';
export default {
  extends: Bar,
  props: {
    chartLabels: null,
    chartData: null
  },
  mounted: function mounted() {
    console.log('barchat测试name' + this.chartLabels);
    console.log('barchat测试name' + this.chartData);
    this.renderChart({
      labels: this.chartLabels,
      datasets: [{
        label: '视频数量',
        backgroundColor: '#f87979',
        data: this.chartData
      }]
    }, {
      responsive: true,
      maintainAspectRatio: false
    });
  }
};