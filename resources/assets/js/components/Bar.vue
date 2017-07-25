
<script>
  import { Bar, mixins  } from 'vue-chartjs'
  const { reactiveProp } = mixins

  export default Bar.extend({
    mixins: [reactiveProp],
    props: ['options', 'chartLabels','chartDataOne', 'titleOne','chartDataTwo', 'titleTwo','color'],
    data () {
      var dataset1 = {
              label: this.titleOne,
              backgroundColor: this.color ? this.color : '#f87979',
              data: eval(this.chartDataOne)
            };
      var datasets = [dataset1];
      if(this.title2) {
        var dataset2 = {
              label: this.titleTwo,
              backgroundColor: '#f87979',
              data: eval(this.chartDataTwo)
            };
        datasets = [dataset1,dataset2];
      }
      return {
        datacollection: {
          labels: eval(this.chartLabels),
          datasets: datasets
        }
      }
    },
    mounted () {
      // this.fillData();
      this.renderChart(this.datacollection);
    },
    methods: {
      fillData () {
        
      }
    }
  })
</script>